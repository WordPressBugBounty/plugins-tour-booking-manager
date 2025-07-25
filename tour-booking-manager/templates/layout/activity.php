<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$ttbm_post_id         = $ttbm_post_id ?? get_the_id();
	$tour_activities = TTBM_Global_Function::get_post_info( $ttbm_post_id, 'ttbm_tour_activities', array() );
	if ( sizeof( $tour_activities ) > 0 && TTBM_Global_Function::get_post_info( $ttbm_post_id, 'ttbm_display_activities', 'on' ) != 'off' ) {
		?>
		<div class='ttbm_default_widget'>
			<?php do_action( 'ttbm_section_title', 'ttbm_string_activities', esc_html__( 'Activities ', 'tour-booking-manager' ) ); ?>
			<div class="ttbm_widget_content">
				<ul>
					<?php foreach ( $tour_activities as $tour_activity ) {
						$term = get_term_by( 'id', $tour_activity, 'ttbm_tour_activities' );
						if ( $term ) {
							$icon = get_term_meta( $term->term_id, 'ttbm_activities_icon', true );
							$icon = $icon ?: 'far fa-check-circle';
							?>
							<li>
								<span class="circleIcon_xs <?php echo esc_attr( $icon ); ?>"></span>
								<?php echo esc_html( $term->name ); ?>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	<?php } ?>