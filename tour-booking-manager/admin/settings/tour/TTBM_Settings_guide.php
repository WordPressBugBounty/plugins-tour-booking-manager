<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBM_Settings_guide')) {
		class TTBM_Settings_guide {
			public function __construct() {
				add_action('ttbm_meta_box_tab_content', [$this, 'guide_setting']);
			}
			public function guide_setting($tour_id) {
				$ttbm_label = TTBM_Function::get_name();
				$all_guides = TTBM_Global_Function::query_post_type('ttbm_guide');
				$display_guide = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_display_tour_guide', 'off');
				$active_guide = $display_guide == 'off' ? '' : 'mActive';
				$checked_guide = $display_guide == 'off' ? '' : 'checked';
				$guides = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_tour_guide', array());
				$ttbm_guide_style = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_guide_style', 'carousel');
				$ttbm_guide_image_style = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_guide_image_style', 'squire');
				$ttbm_guide_description_style = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_guide_description_style', 'full');
				?>
                <div class="tabsItem ttbm_settings_guide" data-tabs="#ttbm_settings_guide">
                    <h2><?php esc_html_e('Guide Settings', 'tour-booking-manager'); ?></h2>
                    <p><?php TTBM_Settings::des_p('guide_settings_description'); ?> </p>
                    <section>
                        <div class="ttbm-header">
                            <h4><i class="fas fa-hiking"></i><?php esc_html_e('Guide Settings', 'tour-booking-manager'); ?></h4>
							<?php TTBM_Custom_Layout::switch_button('ttbm_display_tour_guide', $checked_guide); ?>
                        </div>
                        <div data-collapse="#ttbm_display_tour_guide" class="<?php echo esc_attr($active_guide); ?>">
                            <label class="label">
                                <div>
                                    <p><?php esc_html_e('Guide Description Style', 'tour-booking-manager'); ?><i class="fas fa-question-circle tool-tips"><span><?php TTBM_Settings::des_p('ttbm_guide_description_style'); ?></span></i></p>
                                </div>
                                <select name="ttbm_guide_description_style" class=''>
                                    <option value="short" <?php echo esc_attr($ttbm_guide_description_style == 'short' ? 'selected' : ''); ?>><?php esc_html_e('Short', 'tour-booking-manager'); ?></option>
                                    <option value="full" <?php echo esc_attr($ttbm_guide_description_style == 'full' ? 'selected' : ''); ?>><?php esc_html_e('Full', 'tour-booking-manager'); ?></option>
                                </select>
                            </label>
                            <label class="label">
                                <div>
                                    <p><?php esc_html_e('Guide Style', 'tour-booking-manager'); ?><i class="fas fa-question-circle tool-tips"><span><?php TTBM_Settings::des_p('ttbm_guide_style'); ?></span></i></p>
                                </div>
                                <select name="ttbm_guide_style" class=''>
                                    <option value="all" <?php echo esc_attr($ttbm_guide_style == 'all' ? 'selected' : ''); ?>><?php esc_html_e('All Visible', 'tour-booking-manager'); ?></option>
                                    <option value="carousel" <?php echo esc_attr($ttbm_guide_style == 'carousel' ? 'selected' : ''); ?>><?php esc_html_e('Carousel', 'tour-booking-manager'); ?></option>
                                </select>
                            </label>
                            <label class="label">
                                <div>
                                    <p><?php esc_html_e('Guide Image Style', 'tour-booking-manager'); ?><i class="fas fa-question-circle tool-tips"><span><?php TTBM_Settings::des_p('ttbm_guide_image_style'); ?></span></i></p>
                                </div>
                                <select name="ttbm_guide_image_style" class=''>
                                    <option value="circle" <?php echo esc_attr($ttbm_guide_image_style == 'circle' ? 'selected' : ''); ?>><?php esc_html_e('Circle', 'tour-booking-manager'); ?></option>
                                    <option value="squire" <?php echo esc_attr($ttbm_guide_image_style == 'squire' ? 'selected' : ''); ?>><?php esc_html_e('Squire', 'tour-booking-manager'); ?></option>
                                </select>
                            </label>
                            <label class="label">
                                <div>
                                    <p><?php echo esc_html__('Select ', 'tour-booking-manager') . '  ' . esc_html($ttbm_label) . '  ' . esc_html__('guide', 'tour-booking-manager'); ?><i class="fas fa-question-circle tool-tips"><span><?php TTBM_Settings::des_p('ttip_tour_guide'); ?></span></i></p>
                                </div>
                                <div style="max-width: 70%;">
                                    <select name="ttbm_tour_guide[]" multiple='multiple' class='ttbm_select2 w-50' data-placeholder="<?php echo esc_html__('Please Select Guide', 'tour-booking-manager'); ?>">
										<?php
											if ($all_guides->post_count > 0) {
												foreach ($all_guides->posts as $guide) {
													$ttbm_id = $guide->ID;
													?>
                                                    <option value="<?php echo esc_attr($ttbm_id) ?>" <?php echo esc_attr(in_array($ttbm_id, $guides) ? 'selected' : ''); ?>><?php echo esc_html(get_the_title($ttbm_id)); ?></option>
													<?php
												}
											}
										?>
                                    </select>
                                </div>
                            </label>
                        </div>
                    </section>
                </div>
				<?php
			}
		}
		new TTBM_Settings_guide();
	}