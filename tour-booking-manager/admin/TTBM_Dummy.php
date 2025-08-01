<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Dummy' ) ) {
		class TTBM_Dummy {
			public function __construct() {
				add_action( 'admin_menu', array( $this, 'dummy_menu' ) );
			}
			public function dummy_menu() {
				add_submenu_page( 'edit.php?post_type=ttbm_tour', __( 'Dummy Import', 'tour-booking-manager' ), __( 'Dummy Import', 'tour-booking-manager' ), 'manage_options', 'ttbm_dummy_import_page', array( $this, 'import_page' ) );
			}
			public function import_page() {
				?>
				<div class="ttbm_style ttbm_welcome_page">
					<div class="ttbmTabs topTabs">
						<ul class="tabLists flexEqual">
							<li data-tabs-target="#ttbm_import">
								<h1><?php esc_html_e( 'Dummy Import', 'tour-booking-manager' ); ?></h1>
							</li>
						</ul>
						<div class="tabsContent">
							<div class="tabsItem" data-tabs="#ttbm_import">
								<?php $this->import_tab(); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			public function import_tab() {
				?>
				<div class="unlimited_section">
					<div class="mpContainer">
						<div class="mpRow">
							<div class="col_12">
								<span class="postDash buttonOutline allCenter" style="margin-bottom:30px;">Import Dummy Tour Data</span>
							</div>
							<div class="col_6 sd_12 alignCenter">
								<div>
									<h2>Download & Import Dummy Tour Data</h2>
									<p>Please follow the below process to import dummy tour data to your website.</p>
									<div>
										<div class="alignCenter content_area nowrap">
											<img src="<?php echo esc_url(TTBM_PLUGIN_URL . '/assets/helper/images/welcome/icon_1.png'); ?>" alt="icon"/>
											<div class="textContent">
												<h4>Download Dummy XML File</h4>
												<p>Please downlaod this <a href="https://tour.mage-people.com/tour-dummy-data.zip" target="_blank">Dummy Tours XML File</a></p>
											</div>
										</div>
										<div class="alignCenter content_area nowrap">
											<img src="<?php echo esc_url(TTBM_PLUGIN_URL . '/assets/helper/images/welcome/icon_2.png'); ?>" alt="icon"/>
											<div class="textContent">
												<h4>Import File </h4>
												<p>After Download the XML file, <br/>
													Go to: </br /></br />
													<b>Tools -> Import </b>
													<br/></br />
													In the bottom of this page there is a WordPress import option. If you have already enabled this you can see Run Import, if not click on the Install Now link. After that click on Run Importer and select the XML file you downlaod earlier.</p>
											</div>
										</div>
										<div class="alignCenter content_area nowrap">
											<img src="<?php echo esc_url(TTBM_PLUGIN_URL . '/assets/helper/images/welcome/icon_3.png'); ?>" alt="icon"/>
											<div class="textContent">
												<h4>Finish Import Process</h4>
												<p>Now select the user of your website to assign the new tours from the dropdown, and tick on the Download Attachemnt tick box & Run the Process. After a few minute you will see the Success message. All Done! Have Fun.</p>
											</div>
										</div>
									</div>
									<a href="https://tour.mage-people.com/tour-dummy-data.zip" target="_blank" class="buttonOutline allCenter_radius_transition customButton">Download Dummy Tour XML File</a>
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
		new TTBM_Dummy();
	}