<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Wp_Bulk_Post_Status_Update
 * @subpackage Wp_Bulk_Post_Status_Update/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Bulk_Post_Status_Update
 * @subpackage Wp_Bulk_Post_Status_Update/includes
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Wp_Bulk_Post_Status_Update_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        # uninstall option
        update_option('wpbpus_remove_on_uninstall','no');
        # default post
        $default_data = array('post');
        update_option('wp-bulk-post-status-support-types', $default_data);
	}

}
