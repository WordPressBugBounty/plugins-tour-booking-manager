<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$ttbm_post_id     = $ttbm_post_id ?? get_the_id();
	$start_price = TTBM_Function::get_tour_start_price( $ttbm_post_id );
	$count       = $count ?? 0;
	if ( $start_price && TTBM_Global_Function::get_post_info( $ttbm_post_id, 'ttbm_display_price_start', 'on' ) != 'off' ) {
		?>
			<div class="item_icon" title="<?php esc_html_e( 'Price', 'tour-booking-manager' ); ?>">
				<i class="mi mi-currnecy"></i>
				<?php
				if ( $start_price && TTBM_Global_Function::get_post_info( $ttbm_post_id, 'ttbm_display_price_start', 'on' ) != 'off' ) {
					?> 
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
					echo wc_price($start_price); ?>
				<?php } ?>
			</div>
		<?php
		$count ++; 
	}
?>