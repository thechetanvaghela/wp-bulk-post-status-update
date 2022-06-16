<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Wp_Bulk_Post_Status_Update
 * @subpackage Wp_Bulk_Post_Status_Update/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Bulk_Post_Status_Update
 * @subpackage Wp_Bulk_Post_Status_Update/admin
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Wp_Bulk_Post_Status_Update_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bulk_Post_Status_Update_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bulk_Post_Status_Update_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-bulk-post-status-update-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bulk_Post_Status_Update_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bulk_Post_Status_Update_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-bulk-post-status-update-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register bulk option callback function
	 *
	 * @since    1.0.0
	 */
	public function bulk_actions_edit_status_callback($bulk_actions) {

		$bulk_actions['change-to-draft'] = __('Change to Draft', 'wp-bulk-post-status-update');
		$bulk_actions['changed-to-published'] = __('Change to Publish', 'wp-bulk-post-status-update');
		return $bulk_actions;

	}

	/**
	 * Register bulk option to update callback function
	 *
	 * @since    1.0.0
	 */
	public function bulk_actions_handel_callback($redirect_url, $action, $post_ids) {
		# change to draft
		if ($action == 'change-to-draft') {
			foreach ($post_ids as $post_id) {
				wp_update_post([
					'ID' => $post_id,
					'post_status' => 'draft'
				]);
			}
			//$redirect_url = add_query_arg('changed-to-draft', count($post_ids), $redirect_url);
			$redirect_url = add_query_arg(array('changed-to-draft'=> count($post_ids),'changed-to-published'=> "false"), $redirect_url);
		}
		# change to publish
		if ($action == 'changed-to-published') {
			foreach ($post_ids as $post_id) {
				wp_update_post([
					'ID' => $post_id,
					'post_status' => 'publish'
				]);
			}
			//$redirect_url = add_query_arg('changed-to-published', count($post_ids), $redirect_url);
			$redirect_url = add_query_arg(array('changed-to-published'=> count($post_ids),'changed-to-draft'=> "false"), $redirect_url);
		}
		return $redirect_url;
	}

	public function wpbpus_add_removable_arg_callback($args)
	{
			array_push($args, 'changed-to-published','changed-to-draft','wpbpus-msg');
    	return $args;
	}

	/**
	 * Register bulk option to update callback function
	 *
	 * @since    1.0.0
	 */
	public function bulk_actions_admin_notice_callback() {

		# change to draft
		if (!empty($_REQUEST['changed-to-draft']) && $_REQUEST['changed-to-draft'] != "false") 
		{
			$draft_num_changed = (int) $_REQUEST['changed-to-draft'];
			# print admin notice
			printf('<div id="message" class="updated notice notice-success is-dismissible"><p>' . __('Drafted %d post(s).', 'wp-bulk-post-status-update') . '</p></div>', $draft_num_changed);
		}

		# change to publish
		if (!empty($_REQUEST['changed-to-published']) && $_REQUEST['changed-to-published'] != "false") 
		{
			$publish_num_changed = (int) $_REQUEST['changed-to-published'];
			# print admin notice
			printf('<div id="message" class="updated notice notice-success is-dismissible"><p>' . __('Published %d post(s).', 'wp-bulk-post-status-update') . '</p></div>', $publish_num_changed);
		}

		# admin notice for form submit
		if (!empty($_REQUEST['wpbpus-msg'])) 
		{
			if($_REQUEST['wpbpus-msg'] == 'success')
			{
				$message = 'Settings Saved.';
				$notice_class = 'updated notice-success';
			}
			else if($_REQUEST['wpbpus-msg'] == 'error')
			{
				$message = 'Sorry, your nonce did not verify';
				$notice_class = 'notice-error';
			}
			else
			{
				$message = 'Something went wrong!';
				$notice_class = 'notice-error';
			}
			# print admin notice
			printf('<div id="message" class="notice '.$notice_class.' is-dismissible"><p>' . __('%s.', 'wp-bulk-post-status-update') . '</p></div>', $message);
		}

	}

	/**
	 * Add menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function bulk_actions_admin_menu_callback() {
			# add menu page option to admin
			add_menu_page('Bulk Update Status','Bulk Update Status','manage_options','wp_bulk_update_status_settings_page',array($this,'wp_bulk_update_status_settings_page_callback'),'dashicons-post-status');
	}

	/**
	 * callback menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function wp_bulk_update_status_settings_save_page_callback() {
		# declare variables
		$form_msg = "";
		$selected_value = $save_selected = array();
		# check current user have manage options permission
		if ( current_user_can('manage_options') ) 
		{
			# check form submission
	        if (isset($_POST['wp-bulk-post-status-update-form-settings'])) 
	        {
	        	# current page url
		        $pluginurl = admin_url('admin.php?page=wp_bulk_update_status_settings_page');
	        	# check nonce
	        	if ( ! isset( $_POST['wpbpus_nonce'] ) || ! wp_verify_nonce( $_POST['wpbpus_nonce'], 'wpbpus_action_nonce' ) ) 
	        	{
	        		$redirect_url = add_query_arg('wpbpus-msg', 'error',$pluginurl);
		            wp_safe_redirect( $redirect_url);
		            exit();
				    //$form_msg = '<b style="color:red;">Sorry, your nonce did not verify.</b>';
				} 
				else 
				{
		        	if (isset($_POST['wp-bulk-post-status-support-types'])) 
		        	{
		                $selected_value = array_map( 'sanitize_text_field', $_POST['wp-bulk-post-status-support-types'] );
		                $save_selected = !empty($selected_value) ? $selected_value : array();
		                # save values to database
		                update_option('wp-bulk-post-status-support-types', $save_selected);

		                $unintall = sanitize_text_field($_POST['wpbpus-remove-data']);
		                $unintall = !empty($unintall) ? $unintall : 'no';
										update_option('wpbpus_remove_on_uninstall',$unintall);

		                $redirect_url = add_query_arg('wpbpus-msg', 'success',$pluginurl);
		                wp_safe_redirect( $redirect_url);
										exit();
		                //$form_msg = '<b style="color:green;">Settings Saved.</b>';
	            	}
	         	}
	    	}
		}
	}

	/**
	 * callback menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function wp_bulk_update_status_settings_page_callback() {
		# declare variables
		$selected = "";
		$selected_types = array();

		# get saved data
    $selected_types = get_option('wp-bulk-post-status-support-types');
    $selected_types = !empty($selected_types) ? $selected_types : array();

    $wpbpus_remove_on_uninstall = get_option('wpbpus_remove_on_uninstall');

    # get all the post types
    $args = array(
		   'public'   => true,
		   //'_builtin' => true
		);
    $types = get_post_types( $args, 'objects' );
    # remove media post type from list
    unset( $types['attachment'] );
		?>
		<div class="wrap">
				<h2><?php esc_html_e('Bulk Post Status Update Settings','wp-bulk-post-status-update'); ?></h2>
						<div id="wpbpus-setting-container">
								<div id="wpbpus-body">
										<div id="wpbpus-body-content">
												<div class="">
												<br/><?php //echo $form_msg; ?><hr/><br/>
														<form method="post">
                                <table>
                                  	<tr valign="top">
                                  			<td class="select wpbpus-select-multiple">
                                  					<label for="wp-bulk-post-status-support-types" class="wpbpus-lable"><?php _e('Select Post Types: ','wp-bulk-post-status-update'); ?></label>
                                  							<select id="wp-bulk-post-status-support-types" name="wp-bulk-post-status-support-types[]" multiple>
							                                  		<?php
							                                  		if(!empty($types))
							                                  		{
								                                  			foreach ($types as $key => $type) 
								                                  			{		
								                                  					if ( isset( $type->name ) ) 
							        																			{
											                                  				$selected = in_array($type->name, $selected_types) ? "selected" : "";
											                                  				echo '<option value="'.esc_attr($type->name).'" '.$selected.'>'.esc_attr($type->label).'</option>';
								                                  					}
								                                  			}
								                                  	}
							                                  		?>                                       
                                        				</select>
                                        				<span class="wpbpus-note"><strong><?php _e('Note: ','wp-bulk-post-status-update'); ?></strong> <?php _e('Post types must be selected for bulk status updates.','wp-bulk-post-status-update'); ?></span>
                                    		</td>
                                  	</tr>
                                  	<tr valign="top">
																		  	<td class="select wpbpus-select-multiple uninstall-wrap">
																		  			<label for="wpbpus-remove-data" class="wpbpus-lable"><?php _e('Delete data on uninstall: ','wp-bulk-post-status-update'); ?></label>
																				  			<?php 
																				  			$yes_selected = $no_selected  = '';
																				  			if($wpbpus_remove_on_uninstall == 'yes')
																				  			{
																				  				$yes_selected = 'selected';
																				  			}	
																				  			if($wpbpus_remove_on_uninstall == 'no')
																				  			{
																				  				$no_selected = 'selected';
																				  			}
																				  			 ?>
																			  			<select name="wpbpus-remove-data">
																			  					<option value="yes" <?php echo $yes_selected; ?>><?php _e('Yes','wp-bulk-post-status-update'); ?></option>
																			  					<option value="no" <?php echo $no_selected; ?>><?php _e('No','wp-bulk-post-status-update'); ?></option>
																			  			</select>
																		  	</td>
									  								</tr>	
                                </table>
                                <?php wp_nonce_field( 'wpbpus_action_nonce', 'wpbpus_nonce' ); ?>
                                <?php  submit_button( 'Save Settings', 'primary', 'wp-bulk-post-status-update-form-settings'  ); ?>
													</form>
											</div>
									</div>
							</div>
							<br class="clear">
					</div>
			</div>
			<?php
		}
}
