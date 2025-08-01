<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}
$ttbm_post_id = $ttbm_post_id ?? get_the_id();
$tour_id=TTBM_Function::post_id_multi_language($ttbm_post_id);
$thumbnail  = TTBM_Global_Function::get_image_url( $tour_id );
$term_count = 3;
?>

<?php include( TTBM_Function::template_path( 'layout/sale_price.php' ) ); ?>
<div class="bg_image_area">
    <div> <img src="<?php echo esc_attr( $thumbnail );?>"></div>
    <div class="fullAbsolute group_item">
        <div class="flexEqual">
            <?php include( TTBM_Function::template_path( 'layout/list_price.php' ) ); ?>
            <?php include( TTBM_Function::template_path( 'layout/list_max_people.php' ) ); ?>
        </div>
    </div>
    <div class="fdColumn absolute_item">
        <?php include( TTBM_Function::template_path( 'layout/list_duration.php' ) ); ?>
<!--        --><?php //include( TTBM_Function::template_path( 'layout/expire_msg.php' ) ); ?>
    </div>
</div>




<div class="fdColumn ttbm_list_details">
    <?php include( TTBM_Function::template_path( 'layout/list_title.php' ) ); ?>
    <?php include( TTBM_Function::template_path( 'layout/location.php' ) ); ?>
    <div class="divider"></div>
    <?php include( TTBM_Function::template_path( 'layout/description_short.php' ) ); ?>
    <div class="divider"></div>
    <?php include( TTBM_Function::template_path( 'layout/list_bottom.php' ) ); ?>
</div>
