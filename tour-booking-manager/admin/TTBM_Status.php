<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Status' ) ) {
		class TTBM_Status {
			public function __construct() {
				add_action( 'admin_menu', array( $this, 'status_menu' ) );
			}
			public function status_menu() {
				add_submenu_page( 'edit.php?post_type=ttbm_tour', __( 'Status', 'tour-booking-manager' ), __( 'Status', 'tour-booking-manager' ), 'manage_options', 'ttbm_status_page', array( $this, 'ttbm_status_page' ) );
			}
			public function ttbm_status_page() {
				$wp_v = get_bloginfo( 'version' );
				$wc_v = WC()->version;
				$wc_i = TTBM_Global_Function::check_woocommerce();
				$from_name  = get_option( 'woocommerce_email_from_name' );
				$from_email = get_option( 'woocommerce_email_from_address' );
				?>
				<!-- Create a header in the default WordPress 'wrap' container -->
				<div class="wrap" id="ttbm_status_page">
					<div class="ttbm_style">
						<?php do_action( 'ttbm_status_notice_sec' ); ?>
						<div class="dLayout mT">
							<h2>Tour Booking Manager For Woocommerce Environment Status</h2>
							<div class="divider"></div>
							<table>
								<tbody>
								<tr>
									<th data-export-label="WC Version">WordPress Version:</th>
									<th>
										<?php if ( $wp_v > 5.5 ) {
											echo '<span class="textSuccess"> <span class="far fa-check-circle mR_xs"></span>' . esc_html($wp_v ). '</span>';
										} else {
											echo '<span class="textWarning"> <span class="fas fa-exclamation-triangle mR_xs"></span>' . esc_html($wp_v ). '</span>';
										} ?>
									</th>
								</tr>
								<tr>
									<th data-export-label="WC Version">Woocommerce Installed:</th>
									<th>
										<?php if ( $wc_i == 1 ) {
											echo '<span class="textSuccess"> <span class="far fa-check-circle mR_xs"></span>Yes</span>';
										} else {
											echo '<span class="textWarning"> <span class="fas fa-exclamation-triangle mR_xs"></span>No</span>';
										} ?>
									</th>
								</tr>
								<?php if ( $wc_i == 1 ) { ?>
									<tr>
										<th data-export-label="WC Version">Woocommerce Version:</th>
										<th><?php if ( $wc_v > 4.8 ) {
												echo '<span class="textSuccess"> <span class="far fa-check-circle mR_xs"></span>' . esc_html($wc_v) . '</span>';
											} else {
												echo '<span class="textWarning"> <span class="fas fa-exclamation-triangle mR_xs"></span>' . esc_html($wc_v) . '</span>';
											} ?>
										</th>
									</tr>
									<tr>
										<th data-export-label="WC Version">Email From Name:</th>
										<th>
											<?php if ( $from_name ) {
												echo '<span class="textSuccess"> <span class="far fa-check-circle mR_xs"></span>' . esc_html($from_name) . '</span>';
											} else {
												echo '<span class="textWarning"> <span class="fas fa-exclamation-triangle"></span></span>';
											} ?>
										</th>
									</tr>
									<tr>
										<th data-export-label="WC Version">From Email Address:</th>
										<th>
											<?php if ( $from_email ) {
												echo '<span class="textSuccess"> <span class="far fa-check-circle mR_xs"></span>' . esc_html($from_email) . '</span>';
											} else {
												echo '<span class="textWarning"> <span class="fas fa-exclamation-triangle"></span></span>';
											} ?>
										</th>
									</tr>
								<?php }
									do_action( 'ttbm_status_table_item_sec' ); ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<?php
			}
		}
		new TTBM_Status();
	}