<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$ttbm_post_id = $ttbm_post_id ?? get_the_id();
	$tour_id=$tour_id??TTBM_Function::post_id_multi_language($ttbm_post_id);
	$class_duration = 'absolute_item';
?>
<?php include( TTBM_Function::template_path( 'layout/sale_price.php' ) ); ?>
<div class="fdColumn ttbm_list_details">
    <div class="ttbm_list_info_header">
        <?php include( TTBM_Function::template_path( 'layout/list_thumbnail.php' ) ); ?>
        <?php include( TTBM_Function::template_path( 'layout/list_title.php' ) ); ?>
    </div>
    <div class="ttbm_list_content_wrapper">
        <div class="ttbm_list_info_wrapper">
            <?php include( TTBM_Function::template_path( 'layout/location.php' ) ); ?>
            <?php include( TTBM_Function::template_path( 'layout/list_price.php' ) ); ?>
            <?php include( TTBM_Function::template_path( 'layout/list_duration.php' ) ); ?>
        </div>

        <div class="divider"></div>
        <?php include( TTBM_Function::template_path( 'layout/description_short.php' ) ); ?>
        <div class="divider"></div>
        <?php include( TTBM_Function::template_path( 'layout/list_bottom.php' ) ); ?>

        <div class="fdColumn absolute_item">
            <?php include( TTBM_Function::template_path( 'layout/expire_msg.php' ) ); ?>
        </div>
    </div>
</div>