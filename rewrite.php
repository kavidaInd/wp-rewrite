<?php

/**
 * Wordpress Rewrite Demo
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://teachcodes.com/?p=31
 * @since             1.0.0
 * @package           WordPress Rewrite Demo
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Rewrite Demo
 * Plugin URI:        https://teachcodes.com/?p=31
 * Description:       This is Demo for wp rewrite system. Read more about it on website.
 * Version:           1.0.0
 * Author:            Vikas Bhardwaj
 * Author URI:        https://teachcodes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_rewrite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rewrite-activator.php';
	rewrite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_rewrite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rewrite-deactivator.php';
	rewrite_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rewrite' );
register_deactivation_hook( __FILE__, 'deactivate_rewrite' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

 add_filter('teachCodes_rewrite_manager','teachcodes_demo_page');
function teachcodes_demo_page($rewrites) {
    $rewrites[]=array(
        'title'=>'Demo Theme',
        'slug'=>'account',
        'rules'=>array(
            'user'=>'string',
            'id'=>'number'
        ),
        'path'=>plugin_dir_path( __FILE__ ).'template/index.php',
        'is_private'=>false
    );
    
    return $rewrites;
}
require plugin_dir_path( __FILE__ ) . 'includes/teachCodesRewrite.php';
$techcodesRewrite = new teachCodesRewrite();