<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$ttbm_post_id     = $ttbm_post_id ?? get_the_id();
	$class_title = $class_title ?? '';
	$title       = get_the_title( $ttbm_post_id );
?>
    <?php do_action( 'ttbm_title_before', $ttbm_post_id ); ?>

	<h3 class="ttbm_list_title <?php echo esc_attr( $class_title ); ?>" data-placeholder>
		<a href="<?php echo esc_url( get_the_permalink( $ttbm_post_id ) ); ?>">
			<?php echo esc_html( $title ); ?>
		</a>
	</h3>
    <?php do_action( 'ttbm_title_after', $ttbm_post_id ); ?>