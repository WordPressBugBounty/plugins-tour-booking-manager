<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBM_Settings_activity')) {
		class TTBM_Settings_activity {
			public function __construct() {
				add_action('ttbm_meta_box_tab_content', [$this, 'ttbm_settings_activities'], 10, 1);
				//*********Activity***************//
				add_action('wp_ajax_load_ttbm_activity_form', [$this, 'load_ttbm_activity_form']);
				add_action('wp_ajax_nopriv_load_ttbm_activity_form', [$this, 'load_ttbm_activity_form']);
				add_action('wp_ajax_ttbm_reload_activity_list', [$this, 'ttbm_reload_activity_list']);
				add_action('wp_ajax_nopriv_ttbm_reload_activity_list', [$this, 'ttbm_reload_activity_list']);
				//******************//
				add_action('wp_ajax_ttbm_new_activity_save', [$this, 'ttbm_new_activity_save']);
				add_action('wp_ajax_nopriv_ttbm_new_activity_save', [$this, 'ttbm_new_activity_save']);
			}
			public function ttbm_settings_activities($tour_id) {
				$display = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_display_activities', 'on');
				$active = $display == 'off' ? '' : 'mActive';
				$checked = $display == 'off' ? '' : 'checked';
				?>
                <div class="tabsItem ttbm_settings_area ttbm_settings_activities" data-tabs="#ttbm_settings_activies">
                    <h2><?php esc_html_e('Activity Settings', 'tour-booking-manager'); ?></h2>
                    <p><?php TTBM_Settings::des_p('activity_settings_description'); ?></p>
                    <section>
                        <div class="ttbm-header">
                            <h4><i class="fas fa-clipboard-list"></i><?php esc_html_e('Activities Settings', 'tour-booking-manager'); ?></h4>
							<?php TTBM_Custom_Layout::switch_button('ttbm_display_activities', $checked); ?>
                        </div>
                        <div data-collapse="#ttbm_display_activities" class="ttbm_activities_area <?php echo esc_attr($active); ?>">
							<?php $this->activities($tour_id); ?>
                        </div>
                        <input type="hidden" id="ttbm_checked_activities_holder" name="ttbm_checked_activities_holder" value="<?php echo esc_attr(implode(',', TTBM_Global_Function::get_post_info($tour_id, 'ttbm_tour_activities', []))); ?>"/>
                    </section>
                </div>
				<?php
				$this->add_new_activity_popup();
			}
			public function activities($tour_id) {
				$activities = TTBM_Global_Function::get_taxonomy('ttbm_tour_activities');
				$tour_activities = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_tour_activities', []);
				$tour_activities_array = $tour_activities;
				?>
                <div class="ttbm_activities_table">
                    <div class="includedd-features-section">
                        <div class="groupCheckBox">
							<?php foreach ($activities as $activity) { ?>
                                <label class="customCheckboxLabel">
                                    <input type="checkbox" name="ttbm_tour_activities[]" value="<?php echo esc_attr($activity->term_id); ?>" <?php echo in_array($activity->term_id, $tour_activities_array) ? 'checked' : ''; ?> />
                                    <span class="customCheckbox"><?php echo esc_html($activity->name); ?></span>
                                </label>
							<?php } ?>
                        </div>
						<?php TTBM_Custom_Layout::popup_button_xs('add_new_activity_popup', esc_html__('Create New Activity', 'tour-booking-manager')); ?>
                    </div>
                </div>
				<?php
			}
			public function add_new_activity_popup() {
				?>
                <div class="ttbm_popup" data-popup="add_new_activity_popup">
                    <div class="popupMainArea">
                        <div class="popupHeader">
                            <h4>
								<?php esc_html_e('Add New Activity', 'tour-booking-manager'); ?>
                                <p class="_textSuccess_ml_dNone ttbm_success_info">
                                    <span class="fas fa-check-circle mR_xs"></span>
									<?php esc_html_e('Activity is added successfully.', 'tour-booking-manager') ?>
                                </p>
                            </h4>
                            <span class="fas fa-times popupClose"></span>
                        </div>
                        <div class="popupBody ttbm_activity_form_area">
                        </div>
                        <div class="popupFooter">
                            <div class="buttonGroup">
                                <button class="_themeButton ttbm_new_activity_save" type="button"><?php esc_html_e('Save', 'tour-booking-manager'); ?></button>
                                <button class="_warningButton ttbm_new_activity_save_close" type="button"><?php esc_html_e('Save & Close', 'tour-booking-manager'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
			public function load_ttbm_activity_form() {
				wp_nonce_field('ttbm_add_new_activity_popup', 'ttbm_add_new_activity_popup');
				?>
                <label class="flexEqual">
                    <span><?php esc_html_e('Activity Name : ', 'tour-booking-manager'); ?><sup class="textRequired">*</sup></span> <input type="text" name="ttbm_activity_name" class="formControl" required>
                </label>
                <p class="textRequired" data-required="ttbm_activity_name">
                    <span class="fas fa-info-circle"></span>
					<?php esc_html_e('Activity name is required!', 'tour-booking-manager'); ?>
                </p>
				<?php TTBM_Settings::des_p('ttbm_activity_name'); ?>
                <div class="divider"></div>
                <label class="flexEqual">
                    <span><?php esc_html_e('Activity Description : ', 'tour-booking-manager'); ?></span> <textarea name="ttbm_activity_description" class="formControl" rows="3"></textarea>
                </label>
				<?php TTBM_Settings::des_p('ttbm_activity_description'); ?>
                <div class="divider"></div>
                <div class="flexEqual">
                    <span><?php esc_html_e('Activity Icon : ', 'tour-booking-manager'); ?><sup class="textRequired">*</sup></span>
					<?php do_action('ttbm_input_add_icon', 'ttbm_activity_icon'); ?>
                </div>
                <p class="textRequired" data-required="ttbm_activity_icon">
                    <span class="fas fa-info-circle"></span>
					<?php esc_html_e('Activity icon is required!', 'tour-booking-manager'); ?>
                </p>
				<?php
				die();
			}
			public function ttbm_reload_activity_list() {
				if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ttbm_admin_nonce')) {
					wp_send_json_error(['message' => 'Invalid nonce']);
					die;
				}
				$ttbm_id = isset($_POST['ttbm_id']) ? sanitize_text_field(wp_unslash($_POST['ttbm_id'])) : 0;
				$this->activities($ttbm_id);
				die();
			}
			public function ttbm_new_activity_save() {
				if (!isset($_POST['_wp_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wp_nonce'])), 'ttbm_add_new_activity_popup')) {
					die();
				}
				if (!current_user_can('manage_options')) { // Change this capability as needed
					wp_send_json_error('You do not have permission to perform this action.');
					wp_die();
				}
				$name = isset($_POST['activity_name']) ? sanitize_text_field(wp_unslash($_POST['activity_name'])) : '';
				$description = isset($_POST['activity_description']) ? sanitize_text_field(wp_unslash($_POST['activity_description'])) : '';
				$icon = isset($_POST['activity_icon']) ? sanitize_text_field(wp_unslash($_POST['activity_icon'])) : '';
				$query = wp_insert_term($name,   // the term
					'ttbm_tour_activities', // the taxonomy
					array('description' => $description));
				if (is_array($query) && $query['term_id'] != '') {
					$term_id = $query['term_id'];
					update_term_meta($term_id, 'ttbm_activities_icon', $icon);
				}
				die();
			}
		}
		new TTBM_Settings_activity();
	}