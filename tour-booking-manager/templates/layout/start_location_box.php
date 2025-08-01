<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$ttbm_post_id = $ttbm_post_id ?? get_the_id();
	$start_place = TTBM_Global_Function::get_post_info($ttbm_post_id, 'ttbm_travel_start_place');
	$tour_type = TTBM_Function::get_tour_type( $ttbm_post_id );
	$count = $count ?? 0;
	if ( $start_place && $tour_type == 'general' && TTBM_Global_Function::get_post_info( $ttbm_post_id, 'ttbm_display_start_location', 'on' ) != 'off' ) {
		?>
		
			<div class="item_icon" title="<?php esc_html_e( 'Start Location', 'tour-booking-manager' ); ?>">
				<i class="mi mi-location"></i>
				<?php echo esc_html( $start_place ); ?>
			</div>

		<?php
		$count ++;
	}
	?>