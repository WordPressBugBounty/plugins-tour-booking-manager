<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$ttbm_post_id = $ttbm_post_id ?? get_the_id();
?>
<div class="justifyBetween _wrap filter_item_footer" data-placeholder>
	<div class="include_service">
		<?php include( TTBM_Function::template_path( 'layout/include_feature_list.php' ) ); ?>
	</div>
	<button type="button" class="dButton_xs ttbm_explore_button" data-href="<?php echo esc_url( get_the_permalink( $ttbm_post_id ) ); ?>">
		<?php esc_html_e( 'Explore ', 'tour-booking-manager' ); ?>
	</button>
</div>