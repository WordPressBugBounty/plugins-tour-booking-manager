<?php
	//Template Name: Viator Theme

	if ( ! defined( 'ABSPATH' ) ) exit;
	$ttbm_post_id = $ttbm_post_id ?? get_the_id();
	$tour_id=$tour_id??TTBM_Function::post_id_multi_language($ttbm_post_id);
	$template_name = $template_name ?? TTBM_Global_Function::get_post_info( $tour_id, 'ttbm_theme_file', 'default.php' );
	$all_dates     = $all_dates ?? TTBM_Function::get_date( $tour_id );
	$travel_type   = $travel_type ?? TTBM_Function::get_travel_type( $tour_id );
	$available_seat = $available_seat??TTBM_Function::get_total_available( $tour_id );
?>
<div class="ttbm_viator_theme">
	<div class='ttbm_style ttbm_wraper viator_top_section'>
		<div class="ttbm_container shadow_one">
			<div class="ttbm_details_page">
				<?php do_action( 'ttbm_details_title' ); ?>
				<?php include( TTBM_Function::template_path( 'layout/location.php' ) ); ?>
				<div class="ttbm_content_area">
					<div class="ttbm_content__left">
						<?php do_action( 'ttbm_slider' ); ?>
						<?php do_action('ttbm_enquery_popup'); ?>
						<div class="item_section">
							<?php include( TTBM_Function::template_path( 'layout/seat_info.php' ) ); ?>
							<?php include( TTBM_Function::template_path( 'layout/duration_box.php' ) ); ?>
							<?php include( TTBM_Function::template_path( 'layout/max_people_box.php' ) ); ?>
							<?php include( TTBM_Function::template_path( 'layout/age_range_box.php' ) ); ?>
							<?php include( TTBM_Function::template_path( 'layout/start_location_box.php' ) ); ?>
						</div>
					</div>
					<div class="ttbm_content__right">
						<div class="booking-form-area">
							<?php do_action( 'ttbm_registration_before', $ttbm_post_id ); ?>
							<h4 class="booking-form-price"><?php include( TTBM_Function::template_path( 'layout/start_price.php' ) ); ?></h4>
							<div class="divider"></div>
							<?php
								if ( sizeof( $all_dates ) > 0 && $travel_type == 'particular' && $available_seat>0) {
									?>
									<button type="button" class="themeButton fullWidth ttbm_go_particular_booking">
										<?php esc_html_e( 'Book Now ', 'tour-booking-manager' ) ?>
									</button>
									<?php
								}
								if ( sizeof( $all_dates ) > 1 && $travel_type != 'particular' ) {
									?>
									<h5 class="booking-title">
										<?php esc_html_e( 'Select Date and Travelers', 'tour-booking-manager' ); ?>
									</h5>
									<?php
								} ?>
							<?php include( TTBM_Function::template_path( 'ticket/registration.php' ) ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='ttbm_style ttbm_wraper'>
		<div class="mpContainer">
			<div class="ttbm_details_page">
				<div class="ttbm_content_area">
					<div class="ttbm_content__left ">
						<?php do_action( 'ttbm_description' ); ?>
						<?php include( TTBM_Function::template_path( 'ticket/particular_item_area.php' ) ); ?>
						<?php do_action( 'ttbm_hiphop_place' ); ?>
						<?php do_action( 'ttbm_day_wise_details' ); ?>
						<?php do_action( 'ttbm_faq' ); ?>
					</div>
					<div class="ttbm_content__right">
						<?php do_action( 'ttbm_include_feature' ); ?>
						<?php do_action( 'ttbm_exclude_service' ); ?>
						<?php do_action( 'ttbm_activity' ); ?>
						<?php //do_action( 'ttbm_hotel_list' ); ?>
						<?php do_action( 'ttbm_why_choose_us' ); ?>
						<?php do_action( 'ttbm_get_a_question' ); ?>
						<?php do_action( 'ttbm_tour_guide' ); ?>
						<?php do_action( 'ttbm_dynamic_sidebar', $ttbm_post_id ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php do_action( 'ttbm_single_tour_after' ); ?>

	<?php do_action( 'ttbm_related_tour' ); ?>
</div>