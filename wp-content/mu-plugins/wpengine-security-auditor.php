<?php
/**
 * Plugin Name:     WP Engine Security Auditor
 * Description:     WP Engine-specific security auditing and logging
 * Author:          wpengine
 * Author URI:      https://wpengine.com
 * Version:         1.0
 */

if (!defined('ABSPATH')) {
  die;
}

if (defined('WPENGINE_SECURITY_AUDITOR_ENABLED') && WPENGINE_SECURITY_AUDITOR_ENABLED !== true) {
  return;
}

WPEngineSecurityAuditor_Events::initialize();
WPEngineSecurityAuditor_Scans::initialize();

class WPEngineSecurityAuditor_Events {

  static $instance;

  public static function initialize() {
    if (defined('WP_CLI') && WP_CLI && getenv('WPENGINE_SECURITY_AUDITOR_LOGGING') !== "enabled") {
      // logging events to an interactive session is just noise
      return;
    }
    self::$instance = new self;
    foreach (self::$instance->events as $event => $nargs) {
      if (is_array($nargs)) {
        $nargs = count($nargs);
      }
      add_action($event, [self::$instance, "on_$event"], 10, $nargs);
    }
  }

  public function __construct() {
    $this->events = [
      // code changes
      'activated_plugin' => ['plugin', 'network_activation'],
      'deactivated_plugin' => ['plugin', 'network_activation'],
      'switch_theme' => ['new_name'],
      'upgrader_process_complete' => ['upgrader', 'info'], // special cased
      // access changes
      'add_user_role' => ['user_id', 'role'],
      'remove_user_role' => ['user_id', 'rold'],
      'set_user_role' => ['user_id', 'role', 'old_roles'],
      'granted_super_admin' => ['user_id'],
      'revoked_super_admin' => ['user_id'],
      // account changes
      'wp_login' => ['user_login', 'user'], // special cased
      'user_register' => ['user_id'],
      'profile_update' => ['user_id'],
      'deleted_user' => ['user_id', 'reassign'],
      'retrieve_password_key' => ['user_login'],
    ];
  }

  public function log_event($event, $attrs) {
    $attrs['blog_id'] = get_current_blog_id();
    $attrs['event'] = $event;
    if ($u = wp_get_current_user()) {
      $attrs['current_user_id'] = $u->ID;
    }
    if (isset($_SERVER['REMOTE_ADDR'])) {
      $attrs['remote_addr'] = $_SERVER['REMOTE_ADDR'];
    }
    error_log("auditor:event=$event ".json_encode($attrs, JSON_UNESCAPED_SLASHES));
  }

  public function __call($name, $args) {
    if ('on_' !== substr($name, 0, 3) || !array_key_exists(substr($name, 3), $this->events)) {
      error_log("unexpected invocation of WPEngineSecurityAuditor_Events::$name");
      return;
    }
    $event = substr($name, 3);
    $attrs = array_combine($this->events[$event], $args);
    $this->log_event($event, $attrs);
  }

  public function on_wp_login($user_login, $user) {
    $this->log_event('wp_login', ['user_id' => $user->ID]);
  }

  public function on_upgrader_process_complete($upgrader, $info) {
    $action = $info['action'];
    $type = $info['type'];
    switch ($type) {
    case 'core':
      global $wp_version, $wp_db_version;
      $info['wp_version'] = $wp_version;
      $info['wp_db_version'] = $wp_db_version;
      $this->log_event("upgrader_process_complete_core", $info);
      break;
    case 'plugin':
      $plugins = $info['plugins'];
      unset($info['plugins']);
      if (!is_array($plugins)) {
        $plugins = [$upgrader->result['destination_name']];
      }
      foreach ($plugins as $plugin) {
        $info['plugin'] = $plugin;
        $this->log_event("upgrader_process_complete_plugin", $info);
      }
      break;
    case 'theme':
      error_log(print_r($info, 1));
      $themes = $info['themes'];
      unset($info['themes']);
      if (!is_array($themes)) {
        $themes = [$themes];
      }
      foreach ($themes as $theme) {
        $info['theme'] = $theme;
        $this->log_event("upgrader_process_complete_theme", $info);
      }
      break;
    default:
      error_log("unrecognized upgrade type=$type");
    }
  }

}

class WPEngineSecurityAuditor_Scans {

  static $instance;

  public static function initialize() {
    self::$instance = new self;
    add_action('WPEngineSecurityAuditor_Scans_scheduler', [self::$instance, 'schedule_fingerprint']);
    add_action('WPEngineSecurityAuditor_Scans_fingerprint', [self::$instance, 'fingerprint']);
    self::$instance->ensure_scheduled();
  }

  public function __construct() {
  }

  public function ensure_scheduled() {
    if (!wp_next_scheduled('WPEngineSecurityAuditor_Scans_scheduler')) {
      wp_schedule_event(time(), 'twicedaily', 'WPEngineSecurityAuditor_Scans_scheduler');
    }
  }

  public function schedule_fingerprint() {
    // do a scan at a random time in the next 12 hours
    wp_schedule_single_event(time() + random_int(1, 60*60*12), 'WPEngineSecurityAuditor_Scans_fingerprint');
  }

  static function sig_tree($path) {
    $hc = hash_init('sha256');
    $queue = [[$path, '.', 0]];
    $n = 0;

    while ($queue) {
      list($path, $name, $parent) = array_pop($queue);
      if (is_link($path)) {
        $content = readlink($path);
      } elseif (is_file($path)) {
        $content = "sha256\0" . hash_file('sha256', $path);
      } elseif (is_dir($path)) {
        $me = $n++;
        $content = "\0 0";
        foreach (scandir($path) as $name) {
          if ('.' === $name or '..' === $name) continue;
          $queue[] = ["$path/$name", $name, $me];
        }
      } else {
        $content = "\0 1"; // wtf
      }
      hash_update($hc, "$name\0$parent\0$content\0");
    }
    hash_update($hc, $n);
    return 'v1:' . hash_final($hc);
  }

  public function log_info($kind, $name, $slug, $ver, $sig) {
    $data = [
      'blog_id' => get_current_blog_id(),
      'kind' => $kind,
      'name' => $name,
      'slug' => $slug,
      'ver' => $ver,
      'sig' => $sig,
    ];
    error_log('auditor:scan=fingerprint ' . json_encode($data, JSON_UNESCAPED_SLASHES));
  }

  private static function plugin_root($plugin_file) {
    $dir = dirname($plugin_file);
    if ($dir === WP_PLUGIN_DIR || $dir === WPMU_PLUGIN_DIR) {
      return $plugin_file;
    }
    return $dir;
  }

  public function fingerprint() {
    $plugin_headers = ['name' => 'Plugin Name', 'ver' => 'Version'];
    global $wp_version;

//    foreach (get_loaded_extensions() as $ext) {
//      $ext_path = ini_get('extension_dir') . '/' . $ext . '.so';
//      $this->log_info('php-ext', $ext, $ext, strval(phpversion($ext)), self::sig_tree($ext_path));
//    }

    foreach (wp_get_mu_plugins() as $path) {
      $pd = get_file_data($path, $plugin_headers);
      $slug = plugin_basename($path);
      $this->log_info('mu-plugin', $pd['name'], $slug, $pd['ver'], self::sig_tree($path));
    }

    $scanned_path = [];
    foreach ((is_multisite() ? wp_get_active_network_plugins() : []) as $path) {
      $pd = get_file_data($path, $plugin_headers);
      $slug = plugin_basename($path);
      $path = self::plugin_root($path);
      $scanned_path[$path] = 1;
      $this->log_info('active-network-plugin', $pd['name'], $slug, $pd['ver'], self::sig_tree($path));
    }

    foreach (wp_get_active_and_valid_plugins() as $full_path) {
      $pd = get_file_data($full_path, $plugin_headers);
      $slug = plugin_basename($full_path);
      $path = self::plugin_root($full_path);
      if (array_key_exists($path, $scanned_path)) continue;
      $scanned_path[$path] = 1;
      $this->log_info('active-plugin', $pd['name'], $slug, $pd['ver'], self::sig_tree($path));
    }

    foreach (get_plugins() as $plugin_path => $data) {
      $full_path = WP_PLUGIN_DIR . '/' . $plugin_path;
      $slug = plugin_basename($full_path);
      $path = self::plugin_root($full_path);
      if (array_key_exists($path, $scanned_path)) continue;
      $this->log_info('installed-plugin', $data['Name'], $slug, $data['Version'], self::sig_tree($path));
    }

    foreach (wp_get_themes() as $theme_name => $theme) {
      $this->log_info('theme', $theme->name, $theme->get_stylesheet(), $theme->version, self::sig_tree($theme->get_stylesheet_directory()));
    }

    $this->log_info('wp-core', 'wp-includes', 'wp-includes', $wp_version, self::sig_tree(ABSPATH . WPINC));
    $this->log_info('wp-core', 'wp-admin', 'wp-admin', $wp_version, self::sig_tree(ABSPATH . '/wp-admin'));
  }
}
