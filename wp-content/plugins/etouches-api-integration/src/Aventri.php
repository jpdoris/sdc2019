<?php

namespace AventriEventSync;

use AventriEventSync\Exception\TemplateNotFound;
use AventriEventSync\Service\Synchronizer;
use WP_Post;
use function dirname;

class Aventri
{
    const KEY = 'aventri_event_sync';
    const NAME = 'Etouches (Aventri) - Event Sync';
    const VERSION = '1.3.1';
    const MINIMUM_WP_VERSION = '4.0';
    const MINIMUM_PHP_VERSION = '7.0';

    const CRON_EVERY_MINUTE = 'every_minute';
    const CRON_EVERY_30_MINUTES = 'every_30_minutes';
    const CRON_HOURLY = 'hourly';
    const CRON_EVERY_2_HOURS = 'every_2_hours';
    const CRON_TWICE_DAILY = 'twicedaily';
    const CRON_DAILY = 'daily';

    const POST_TYPE_SESSION = 'aventri_session';
    const POST_TYPE_SPEAKER = 'aventri_speaker';

    /**
     * @var array
     */
    private static $availableCronjobs = [
        SyncCronjob::class,
    ];

    /**
     * @var array
     */
    private static $availableCronIntervals = [
        self::CRON_EVERY_MINUTE => 'Once every minute',
        self::CRON_EVERY_30_MINUTES => 'Once every 30 minutes',
        self::CRON_HOURLY => 'Hourly',
        self::CRON_EVERY_2_HOURS => 'Once every 2 hours',
        self::CRON_TWICE_DAILY => 'Once every 12 hours',
        self::CRON_DAILY => 'Once daily',
    ];

    /**
     * Run the plugin and register all necessary hooks.
     */
    public function run()
    {
        // Check and handle PHP and WP version when the plugin is activated
        register_activation_hook(self::get_plugin_file(), [
            $this,
            'check_and_handle_version_compatibility',
        ]);

        // Register custom cronjob intervals
        add_filter('cron_schedules', [
            $this,
            'add_cron_every_minute',
        ]);
        add_filter('cron_schedules', [
            $this,
            'add_cron_every_30minutes',
        ]);
        add_filter('cron_schedules', [
            $this,
            'add_cron_every_2hours',
        ]);

        // Stop cronjobs scheduling, when the plugin is deactivated
        register_deactivation_hook(self::get_plugin_file(), [
            $this,
            'deactivate_cronjobs',
        ]);

        // Schedule cronjobs on plugin activation
        register_activation_hook(self::get_plugin_file(), [
            $this,
            'schedule_cronjobs',
        ]);

        // Add the repeat functions for each cronjobs
        foreach (self::$availableCronjobs as $cronjob) {
            $cronjobInstance = new $cronjob();

            add_action($cronjob::KEY, [
                $cronjobInstance,
                'run',
            ]);
        }

        // Add the menu item for the settings
        add_action('admin_menu', [
            $this,
            'add_admin_menu_settings',
        ]);

        // Register plugin settings
        add_action('admin_init', [
            $this,
            'register_settings',
        ]);

        // Add a link to go to from the plugins page to the settings page
        add_filter(
            'plugin_action_links_' . plugin_basename(self::get_plugin_file()),
            [
                $this,
                'register_plugin_settings_link'
            ]
        );

        // Register custom post types
        add_action('init', [
            $this,
            'register_post_types',
        ]);

        // Unregister custom post types on plugin deactivation
        register_deactivation_hook(self::get_plugin_file(), [
            $this,
            'unregister_post_types',
        ]);
    }

    /**
     * Loads a template, by extracting the passed variables,
     * allowing them to be used inside the template.
     *
     * @param string $template_name
     * @param array  $variables
     *
     * @throws TemplateNotFound
     */
    public static function load_template($template_name, array $variables = [])
    {
        extract($variables, EXTR_SKIP);

        $template_path = dirname(__DIR__)
            . DIRECTORY_SEPARATOR
            . 'views'
            . DIRECTORY_SEPARATOR
            . $template_name
            . '.php';

        if (!file_exists($template_path) || !is_file($template_path)) {
            throw new TemplateNotFound($template_name);
        }

        /** @noinspection PhpIncludeInspection */
        include $template_path;
    }

    /**
     * @return string
     */
    public static function get_plugin_file()
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . self::KEY . '.php';
    }

    /**
     * Checks if the plugin is satisfied by the PHP and WP versions available.
     * If not, the plugin will be automatically disabled and an error will be
     * printed out to the user.
     *
     * @throws TemplateNotFound
     */
    public function check_and_handle_version_compatibility()
    {
        $incompatible_php_version = version_compare(
            PHP_VERSION,
            self::MINIMUM_PHP_VERSION,
            '<'
        );

        $incompatible_wp_version = version_compare(
            $GLOBALS['wp_version'],
            self::MINIMUM_WP_VERSION,
            '<'
        );

        if ($incompatible_php_version || $incompatible_wp_version) {
            load_plugin_textdomain(self::KEY);

            $message = '';

            if ($incompatible_php_version) {
                $message .= sprintf(
                    __(
                        '%s %s requires PHP %s or higher. Your current PHP version is %s.',
                        self::KEY
                    ),
                    self::NAME,
                    self::VERSION,
                    self::MINIMUM_PHP_VERSION,
                    (float)PHP_VERSION
                );
            }

            if ($incompatible_php_version && $incompatible_wp_version) {
                $message .= '<br>';
            }

            if ($incompatible_wp_version) {
                $message .= sprintf(
                    __(
                        '%s %s requires WordPress %s or higher. Your current WordPress version is %s.',
                        self::KEY
                    ),
                    self::NAME,
                    self::VERSION,
                    self::MINIMUM_WP_VERSION,
                    $GLOBALS['wp_version']
                );
                $message .= '<br>';
            }

            self::load_template('partial/error', ['message' => $message]);

            $plugins = get_option('active_plugins');

            $aventri_event_sync_plugin = plugin_basename(self::get_plugin_file());

            $update = false;

            foreach ($plugins as $i => $plugin) {
                if ($plugin === $aventri_event_sync_plugin) {
                    $plugins[$i] = false;
                    $update = true;
                    break;
                }
            }

            if ($update) {
                update_option('active_plugins', array_filter($plugins));
            }
            exit;
        }
    }

    /**
     * Iterate over all available cronjobs and schedule them.
     */
    public function schedule_cronjobs()
    {
        foreach (self::$availableCronjobs as $cronjob) {
            if (!wp_next_scheduled($cronjob::KEY)) {
                wp_schedule_event(
                    time(),
                    $cronjob::getInterval(),
                    $cronjob::KEY
                );
            }
        }
    }

    /**
     * Iterate over all available cronjobs and deactivate them.
     */
    public function deactivate_cronjobs()
    {
        foreach (self::$availableCronjobs as $cronjob) {
            // Deactivate all scheduled events
            wp_unschedule_hook($cronjob::KEY);
        }
    }

    /**
     * Add custom cron recurrence: 1 minute
     *
     * @param array $schedules
     *
     * @return mixed
     */
    public function add_cron_every_minute(array $schedules)
    {
        $schedules[self::CRON_EVERY_MINUTE] = [
            'interval' => 60,
            'display' => __(self::$availableCronIntervals[self::CRON_EVERY_MINUTE]),
        ];

        return $schedules;
    }

    /**
     * Add custom cron recurrence: 30 minutes
     *
     * @param array $schedules
     *
     * @return mixed
     */
    public function add_cron_every_30minutes(array $schedules)
    {
        $schedules[self::CRON_EVERY_30_MINUTES] = [
            'interval' => 30 * 60,
            'display' => __(self::$availableCronIntervals[self::CRON_EVERY_30_MINUTES]),
        ];

        return $schedules;
    }

    /**
     * Add custom cron recurrence: 2 hours
     *
     * @param array $schedules
     *
     * @return mixed
     */
    public function add_cron_every_2hours(array $schedules)
    {
        $schedules[self::CRON_EVERY_2_HOURS] = [
            'interval' => 2 * 60 * 60,
            'display' => __(self::$availableCronIntervals[self::CRON_EVERY_2_HOURS]),
        ];

        return $schedules;
    }

    /**
     * Responsible for adding a new menu item in the admin panel's menu.
     */
    public function add_admin_menu_settings()
    {
        add_options_page(
            'Etouches (Aventri) - Event Sync Settings',
            'Etouches API Sync (Aventri)',
            'manage_options',
            self::KEY,
            [
                $this,
                'display_settings_page',
            ]
        );

        add_management_page(
            'Etouches (Aventri) - Manual Update',
            'Etouches (Aventri) - Manual Update',
            'install_plugins',
            'aventri_manual_update',
            [
                $this,
                'display_manual_update_page',
            ]
        );
    }

    /**
     * Displays the template with plugin settings.
     *
     * @throws TemplateNotFound
     */
    public function display_settings_page()
    {
        self::load_template('partial/settings');
    }

    /**
     * Displays the template with manual update settings.
     *
     * @throws TemplateNotFound
     */
    public function display_manual_update_page()
    {
        $synchronizer = new Synchronizer();
        $error = null;

        try {
            $synchronizer->sync();
        } catch (\Throwable $throwable) {
            $error = $throwable->getMessage();
        }

        self::load_template('partial/manual-update', [
            'error' => $error,
        ]);
    }

    /**
     * Registers settings, available for this plugin.
     */
    public function register_settings()
    {
        register_setting(self::KEY, self::KEY);

        $options = get_option(self::KEY);

        add_settings_section(
            self::KEY . '_api',
            'API Settings',
            function () {
                $html = '<p>';
                $html .= __(
                    'General information, regarding the connection to the Aventri endpoint.'
                    . '<br>'
                    . 'Both values can be found in the client area of Aventri.'
                    . '<br>'
                    . 'From the side bar menu, go to <kbd>Settings > Account Settings</kbd>, then select <kbd>Integrations > API</kbd>.'
                );
                $html .= '</p>';

                echo $html;
            },
            self::KEY
        );

        add_settings_field(
            self::KEY . '_account_id',
            'Account ID',
            function () use ($options) {
                $html = '<input id="' . self::KEY . '_account_id' . '"';
                $html .= ' ';
                $html .= 'name="' . self::KEY . '[account_id]" type="number"';
                $html .= ' ';
                $html .= 'value="' . esc_attr($options['account_id']) . '" />';

                echo $html;
            },
            self::KEY,
            self::KEY . '_api'
        );

        add_settings_field(
            self::KEY . '_api_key',
            'API Key',
            function () use ($options) {
                $html = '<input id="' . self::KEY . '_api_key' . '"';
                $html .= ' ';
                $html .= 'name="' . self::KEY . '[api_key]" type="text"';
                $html .= ' ';
                $html .= 'value="' . esc_attr($options['api_key']) . '" />';
                $html .= '<p class="description">';
                $html .= '</p>';

                echo $html;
            },
            self::KEY,
            self::KEY . '_api'
        );

        add_settings_section(
            self::KEY . '_separator1',
            '<hr>',
            null,
            self::KEY
        );

        add_settings_section(
            self::KEY . '_sync',
            'Synchronization Settings',
            function () {
                $html = '<p>';
                $html .= __('Settings, regarding the synchronization process between Aventri and Wordpress.');
                $html .= '</p>';

                echo $html;
            },
            self::KEY
        );

        add_settings_field(
            self::KEY . '_event_id',
            'Event ID',
            function () use ($options) {
                $html = '<input id="' . self::KEY . '_event_id' . '"';
                $html .= ' ';
                $html .= 'name="' . self::KEY . '[event_id]" type="number"';
                $html .= ' ';
                $html .= 'value="' . esc_attr($options['event_id']) . '" />';
                $html .= '<p class="description">';
                $html .= __(
                    'It can be copied from the client area of Aventri.'
                    . '<br>'
                    . 'Go to the list of events and copy the value from the first column of the event you want to sync.'
                );
                $html .= '</p>';

                echo $html;
            },
            self::KEY,
            self::KEY . '_sync'
        );

        add_settings_section(
            self::KEY . '_separator2',
            '<hr>',
            null,
            self::KEY
        );

        add_settings_section(
            self::KEY . '_cronjob_intervals',
            'Cron-job',
            function () {
                $html = '<p>';
                $html .= __('Settings, regarding any active cron-jobs within this plugin');
                $html .= '</p>';

                echo $html;
            },
            self::KEY
        );

        foreach (self::$availableCronjobs as $availableCronjob) {
            add_settings_field(
                $availableCronjob::KEY . '_interval',
                $availableCronjob::NAME,
                function () use ($options, $availableCronjob) {
                    $selectedOption = $options['cronjob_intervals'][$availableCronjob::KEY]
                        ?? $availableCronjob::DEFAULT_INTERVAL;

                    $html = '<select id="' . $availableCronjob::KEY . '_interval' . '"';
                    $html .= ' ';
                    $html .= 'name="' . self::KEY . '[cronjob_intervals][' . $availableCronjob::KEY . ']"';
                    $html .= '>';

                    foreach (self::$availableCronIntervals as $value => $label) {
                        $html .= '<option value="' . esc_attr($value) . '"';

                        if ($value === $selectedOption) {
                            $html .= ' ';
                            $html .= 'selected="selected"';
                        }

                        $html .= '>';
                        $html .= __($label);

                        if ($value === $availableCronjob::DEFAULT_INTERVAL) {
                            $html .= sprintf(
                                ' (%s)',
                                __('Default')
                            );
                        }

                        $html .= '</option>';
                    }

                    $html .= '</select>';

                    $html .= '<p class="description">';
                    $html .= __('The plugin must be re-activated after changing this value.');
                    $html .= '</p>';

                    echo $html;
                },
                self::KEY,
                self::KEY . '_cronjob_intervals'
            );
        }
    }

    /**
     * Add plugin settings link to the plugin managment page of WordPress
     *
     * @param array $links
     */
    public function register_plugin_settings_link($links)
    {
        $links[] = sprintf(
            '<a href="%s">%s</a>',
            esc_url(
                get_admin_url(null, 'options-general.php?page=' . self::KEY)
            ),
            __('Settings')
        );

        return $links;
    }

    /**
     * Register custom post types, used by this plugin.
     */
    public function register_post_types()
    {
        register_post_type(
            self::POST_TYPE_SESSION,
            [
                'labels' => [
                    'name' => __('Sessions', self::KEY),
                    'singular_name' => __('Session', self::KEY),
                    'add_new' => _x('Add New', 'session', self::KEY),
                    'add_new_item' => __('Add New Session', self::KEY),
                    'edit_item' => __('Edit Session', self::KEY),
                    'new_item' => __('New Session', self::KEY),
                    'view_item' => __('View Session', self::KEY),
                    'view_items' => __('View Sessions', self::KEY),
                    'search_items' => __('Search Sessions', self::KEY),
                    'not_found' => __('No sessions found', self::KEY),
                    'not_found_in_trash' => __('No sessions found in Trash', self::KEY),
                    'parent_item_colon' => __('Parent Session', self::KEY),
                    'all_items' => __('All Sessions', self::KEY),
                    'archives' => __('Session Archives', self::KEY),
                    'attributes' => __('Session Attributes', self::KEY),
                    'insert_into_item' => __('Insert into session', self::KEY),
                    'uploaded_to_this_item' => __('Uploaded to this session', self::KEY),
                    'filter_items_list' => __('Filter sessions list', self::KEY),
                    'items_list_navigation' => __('Sessions list navigation', self::KEY),
                    'items_list' => __('Sessions list', self::KEY),
                ],
                'supports' => [
                    'title',
                    'editor',
                    'custom-fields',
                ],
                'description' => 'Sessions, synchronized from Aventri',
                'public' => false,
                'show_ui' => true,
                'menu_icon' => 'dashicons-format-status',
            ]
        );

        register_post_type(
            self::POST_TYPE_SPEAKER,
            [
                'labels' => [
                    'name' => __('Speakers', self::KEY),
                    'singular_name' => __('Speaker', self::KEY),
                    'add_new' => _x('Add New', 'speaker', self::KEY),
                    'add_new_item' => __('Add New Speaker', self::KEY),
                    'edit_item' => __('Edit Speaker', self::KEY),
                    'new_item' => __('New Speaker', self::KEY),
                    'view_item' => __('View Speaker', self::KEY),
                    'view_items' => __('View Speakers', self::KEY),
                    'search_items' => __('Search Speakers', self::KEY),
                    'not_found' => __('No speakers found', self::KEY),
                    'not_found_in_trash' => __('No speakers found in Trash', self::KEY),
                    'parent_item_colon' => __('Parent Speaker', self::KEY),
                    'all_items' => __('All Speakers', self::KEY),
                    'archives' => __('Speaker Archives', self::KEY),
                    'attributes' => __('Speaker Attributes', self::KEY),
                    'insert_into_item' => __('Insert into speaker', self::KEY),
                    'uploaded_to_this_item' => __('Uploaded to this speaker', self::KEY),
                    'filter_items_list' => __('Filter speakers list', self::KEY),
                    'items_list_navigation' => __('Speakers list navigation', self::KEY),
                    'items_list' => __('Speakers list', self::KEY),
                ],
                'supports' => [
                    'title',
                    'editor',
                    'custom-fields',
                ],
                'description' => 'Speakers, synchronized from Aventri',
                'public' => false,
                'show_ui' => true,
                'menu_icon' => 'dashicons-groups',
            ]
        );
    }

    /**
     * Unregister custom post types, used by this plugin.
     */
    public function unregister_post_types()
    {
        unregister_post_type(self::POST_TYPE_SESSION);
        unregister_post_type(self::POST_TYPE_SPEAKER);
    }

    /**
     * Returns all speaker posts for a particular session.
     * If no speakers have been found, an empty array will be returned.
     *
     * @param int $session_id
     *
     * @return WP_Post[]
     */
    public static function get_session_speakers($session_id)
    {
        $session_post = get_post($session_id);

        if (!$session_post) {
            return [];
        }

        $aventri_speaker_ids_meta = get_post_meta(
            $session_post->ID,
            'aventri_speaker_ids'
        );

        $aventri_speaker_ids = isset($aventri_speaker_ids_meta[0])
            ? json_decode($aventri_speaker_ids_meta[0], true)
            : null;

        if (!$aventri_speaker_ids) {
            return [];
        }

        return get_posts([
            'post_type' => self::POST_TYPE_SPEAKER,
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'aventri_speaker_id',
                    'value' => $aventri_speaker_ids,
                    'compare' => 'IN',
                ],
            ],
        ]);
    }

    /**
     * Returns all session posts for a particular speaker.
     * If no sessions have been found, an empty array will be returned.
     *
     * @param int $speaker_id
     *
     * @return WP_Post[]
     */
    public static function get_speaker_sessions($speaker_id)
    {
        $speaker_post = get_post($speaker_id);

        if (!$speaker_post) {
            return [];
        }

        $aventri_session_ids_meta = get_post_meta(
            $speaker_post->ID,
            'aventri_session_ids'
        );

        $aventri_session_ids = isset($aventri_session_ids_meta[0])
            ? json_decode($aventri_session_ids_meta[0], true)
            : null;

        if (!$aventri_session_ids) {
            return [];
        }

        return get_posts([
            'post_type' => self::POST_TYPE_SESSION,
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'aventri_session_id',
                    'value' => $aventri_session_ids,
                    'compare' => 'IN',
                ],
            ],
        ]);
    }
}
