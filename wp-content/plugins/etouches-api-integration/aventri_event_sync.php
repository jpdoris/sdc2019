<?php
/*
Plugin Name: Etouches API Event Sync Integration (Aventri)
Plugin URI: https://www.aventri.com/
description: A plugin to synchronize sessions and speakers for a particular event.
Version: 1.3.1
Author: Tsvetomir Lazarov
Author URI: https://www.linkedin.com/in/tsvetomir-lazarov-11450a109/
License: GPL2
*/

// Make sure we do not expose any info if called directly
use AventriEventSync\Aventri;

if (!function_exists('add_action')) {
    echo 'Etouches (Aventri) - Event Sync' . PHP_EOL;
    exit;
}

spl_autoload_register('aventri_event_sync_autoloader');

/**
 * Class auto-loading function
 *
 * @param string $class_name
 */
function aventri_event_sync_autoloader($class_name)
{
    if (false !== strpos($class_name, 'AventriEventSync')) {
        $plugin_dir = realpath(plugin_dir_path(__FILE__));
        $classes_dir = $plugin_dir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

        // Remove the first part of the namespace (the plugin namespace)
        $class_name_parts = explode('\\', $class_name);
        array_shift($class_name_parts);
        $class_name = implode('\\', $class_name_parts);

        $class_file_name = str_replace(
            '\\',
            DIRECTORY_SEPARATOR,
            $class_name
        );

        $class_file = $class_file_name . '.php';
        $class_file_path = $classes_dir . $class_file;

        if (file_exists($class_file_path) && is_file($class_file_path)) {
            /** @noinspection PhpIncludeInspection */
            require_once $class_file_path;
        }
    }
}

// Instantiate the main plugin class and run all necessary initialization logic.
$plugin = new Aventri();
$plugin->run();
