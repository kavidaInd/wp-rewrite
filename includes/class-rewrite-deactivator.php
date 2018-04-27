<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WordPress Rewrite Demo
 * @subpackage WordPress Rewrite Demo/includes
 * @author     Vikas Bhardwaj <doctor.wordpress@gmail.com>
 */
class rewrite_Deactivator {
	public static function deactivate() {
              flush_rewrite_rules();
	}

}
