<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBM_Welcome')) {
		class TTBM_Welcome {
			public function __construct() {
				add_action('admin_menu', array($this, 'welcome_menu'));
			}
			public function welcome_menu() {
				add_submenu_page('edit.php?post_type=ttbm_tour', __('Welcome', 'tour-booking-manager'), __('Welcome', 'tour-booking-manager'), 'manage_options', 'ttbm_welcome_page', array($this, 'welcome_page'));
			}
			public function welcome_page() {
				?>
                <div class="ttbm_style ttbm_welcome_page">
                    <div class="ttbmTabs topTabs">
                        <ul class="tabLists flexEqual">
                            <li data-tabs-target="#ttbm_welcome"><h1><?php esc_html_e('Welcome', 'tour-booking-manager'); ?></h1></li>
                        </ul>
                        <div class="tabsContent">
                            <div class="tabsItem" data-tabs="#ttbm_welcome">
								<?php $this->welcome_tab(); ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
			public function welcome_tab() {
				?>
                <div class="unlimited_section">
                    <div class="mpContainer">
                        <div class="mpRow">
                            <div class="col_12">
                                <span class="postDash buttonOutline allCenter" style="margin-bottom:30px;">PRO VERSION FEATURES</span>
                            </div>
                            <div class="col_6 sd_12 alignCenter">
                                <div>
                                    <h2>Traveler Form builder for Traveler's custom information form building and email features with PDF ticketing.</h2>
                                    <p>Tour Booking Manager For Woocommerce Pro feature include traveler form builder and pdf ticket and emailing feature. In pro version traver list can export with CSV and pdf ticket can download from traveler list.</p>
                                    <div>
                                        <div class="alignCenter content_area nowrap">
                                            <img src="<?php echo esc_url(TTBM_PLUGIN_URL . '/assets/helper/images/welcome/icon_1.png'); ?>" alt="icon"/>
                                            <div class="textContent">
                                                <h4>Traveler Management</h4>
                                                <p>Traveler can be managed easily with Traveler form builder and Traveler information can edit also can export as CSV</p>
                                            </div>
                                        </div>
                                        <div class="alignCenter content_area nowrap">
                                            <img src="<?php echo esc_attr(TTBM_PLUGIN_URL . '/assets/helper/images/welcome/icon_2.png'); ?>" alt="icon"/>
                                            <div class="textContent">
                                                <h4>PDF Ticketing </h4>
                                                <p>every ticket purchased a pdf ticket will be generate that can print as entry document, different pdf template possible.</p>
                                            </div>
                                        </div>
                                        <div class="alignCenter content_area nowrap">
                                            <img src="<?php echo esc_url(TTBM_PLUGIN_URL . '/assets/helper/images/welcome/icon_3.png'); ?>" alt="icon"/>
                                            <div class="textContent">
                                                <h4>Emailing Features</h4>
                                                <p>Pro version has email feature, after purchase complete, pdf ticket can send to buyer by email, it has customize email shortcode.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://mage-people.com/product/woocommerce-tour-and-travel-booking-manager-pro/" target="_blank" class="buttonOutline allCenter_radius_transition customButton">Buy Pro Today</a>
                                    <a href="https://docs.mage-people.com/tour-travel-booking-manager/" target="_blank" class="buttonOutline allCenter_radius_transition customButton">Find Documentation Here</a>
                                </div>
                            </div>
                            <div class="col_6 sd_12">
                                <img src="<?php echo esc_url(TTBM_PLUGIN_URL . '/assets/helper/images/welcome/ullimited_img.png'); ?>" alt="unlimited"/>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
		}
		new TTBM_Welcome();
	}