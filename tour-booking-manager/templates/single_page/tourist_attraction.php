<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.
if ( wp_is_block_theme() )
{
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <?php
        $block_content = do_blocks( '
			<!-- wp:group {"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
			<!-- wp:post-content /-->
			</div>
			<!-- /wp:group -->'
        );
        wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="wp-site-blocks">
        <header class="wp-block-template-part site-header">
            <?php block_header_area(); ?>
        </header>
    </div>
    <?php
}
else
{
    get_header();
    the_post();
}
$post_id = get_the_id();
do_action( 'ttbm_single_category_page_before_wrapper' );
echo do_shortcode( "[travel-list attraction='" . $post_id . "' style='modern' show='10'  pagination='yes' sidebar-filter='yes']" );
do_action( 'ttbm_single_category_page_after_wrapper' );
if ( wp_is_block_theme() )
{
    // Code for block themes goes here.
    ?>
    <footer class="wp-block-template-part">
        <?php block_footer_area(); ?>
    </footer>
    <?php wp_footer(); ?>
    </body>
    <?php
}
else
{
    get_footer();
}
