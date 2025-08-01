<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$ttbm_post_id     = $ttbm_post_id ?? get_the_id();
	$hotel_lists = TTBM_Function::get_hotel_list( $ttbm_post_id );
	if ( sizeof( $hotel_lists ) > 0 && TTBM_Global_Function::get_post_info( $ttbm_post_id, 'ttbm_display_hotels', 'on' ) != 'off' ) {
		?>
		<div class='ttbm_default_widget'>
			<?php do_action( 'ttbm_section_title', 'ttbm_string_choice_hotel', esc_html__( 'Available Hotel ', 'tour-booking-manager' ) ); ?>
			<div class="ttbm_widget_content">
				<div class="superSlider">
					<div class="sliderAllItem">
						<?php
							$count = 1;
							foreach ( $hotel_lists as $hotel_id ) {
								$image_url = get_the_post_thumbnail_url( $hotel_id, 'full' );
								?>
								<div class="sliderItem" data-slide-index="<?php echo esc_html( $count ); ?>">
									<div data-bg-image="<?php echo esc_html( $image_url ); ?>" data-href="<?php the_permalink( $hotel_id ); ?>">
									<h6><?php echo esc_html( get_the_title( $hotel_id ) ); ?></h6>
									</div>
								</div>
								<?php
								$count ++;
							}
						?>

						<?php do_action( 'add_ttbm_custom_slider_icon_indicator' ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>