<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.
if (!class_exists('TTBM_Hotel_Booking_Lists')) {
    class TTBM_Hotel_Booking_Lists{

        public function __construct() {
            add_action('admin_menu', array($this, 'hotel_booking_list_menu'), 1);
        }

        public function hotel_booking_list_menu() {

            $label = __('Hotel Lists', 'tour-booking-manager');
            add_submenu_page(
                'edit.php?post_type=ttbm_tour',
                $label,
                $label,
                'manage_options',
                'ttbm_hotel_booking_lists',
                array($this, 'ttbm_hotel_order')
            );

        }

        public static function ttbm_hotel_order_query( $selected_date ='', $selected_hotel_id = [], $excluded_post_ids = [], $posts_per_page = 20 ) {

            if( $selected_date !== '' ){
                $checkin_date_filter = array(
                    'key'     => '_ttbm_hotel_booking_checkin_date',
                    'value'   => $selected_date,
                    'compare' => '<=',
                    'type'    => 'DATE',
                );
                $checkout_date_filter =  array(
                    'key'     => '_ttbm_hotel_booking_checkout_date',
                    'value'   => $selected_date,
                    'compare' => '>',
                    'type'    => 'DATE',
                );
            }else{
                $checkin_date_filter = '';
                $checkout_date_filter = '';
            }

            if( !empty($selected_hotel_id) ){
                $hotel_filter = array(
                    'key'     => '_ttbm_hotel_id',
                    'value'   => $selected_hotel_id, // এখানে array দিবে
                    'compare' => 'IN',
                );
            } else {
                $hotel_filter = '';
            }

            $args = array(
                'post_type'      => 'ttbm_hotel_booking',
                'posts_per_page' => $posts_per_page,
                'meta_query'     => array(
                    'relation' => 'AND',
                    $checkin_date_filter,
                    $checkout_date_filter,
                    $hotel_filter,
                ),
                'post__not_in'   => $excluded_post_ids,
            );

            return new WP_Query($args);
        }

        public static function ttbm_hotel_list_query( $posts_per_page = 20, $excluded_post_ids=[] ) {

            $args = array(
                'post_type'      => 'ttbm_hotel',
                'posts_per_page' => $posts_per_page,
                'post_status'    => array( 'publish', 'draft' ),
                'post__not_in'   => $excluded_post_ids,
            );

            return new WP_Query($args);
        }

        public static function ttbm_get_all_hotel_analysis_data(){

            $args = array(
                'post_type'      => 'ttbm_hotel',
                'post_status'    => 'publish',
            );

            $query = new WP_Query($args);
            $total_hotel = $query->found_posts;
            $hotel_room_details = $allRoomsData = $ttbm_hotel_bookings = [];
            $totalRooms = 0;

            $today_date = current_time('Y-m-d');
            $all_booked_rooms = $today_booked_rooms = 0;

            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
                    $post_id   = get_the_ID();
                    $hotel_room_details = get_post_meta( $post_id, 'ttbm_room_details', true );
                    $ttbm_hotel_bookings = get_post_meta( $post_id, 'ttbm_hotel_bookings', true );

                    if ( is_array($hotel_room_details) ) {
                        $allRoomsData[] = $hotel_room_details;
                    }

                    if ( ! empty( $ttbm_hotel_bookings ) && is_array( $ttbm_hotel_bookings ) ) {
                        foreach ( $ttbm_hotel_bookings as $booking ) {
                            if ( isset($booking['check_in'], $booking['rooms_booked']) && $booking['check_in'] >= $today_date ) {
                                $all_booked_rooms += (int) $booking['rooms_booked'];
                            }

                            if ( isset($booking['check_in'], $booking['rooms_booked']) && $booking['check_in'] === $today_date ) {
                                $today_booked_rooms += (int) $booking['rooms_booked'];
                            }
                        }
                    }

                endwhile;
            endif;

            foreach ( $allRoomsData as $roomSet ) {
                foreach ( $roomSet as $room ) {
                    $qty = isset($room['ttbm_hotel_room_qty']) ? (int)$room['ttbm_hotel_room_qty'] : 0;
                    $totalRooms += $qty;
                }
            }

            return array(
                   'total_hotel' => $total_hotel,
                   'totalRooms' => $totalRooms,
                   'today_booked_rooms' => $today_booked_rooms,
                   'all_booked_rooms' => $all_booked_rooms,
            );

        }

        public function ttbm_hotel_order() {

            $excluded_post_ids = array();
            $selected_date = '';
            $selected_hotel_id = array();
            $posts_per_page = 10;
            $query = self::ttbm_hotel_order_query( $selected_date, $selected_hotel_id, $excluded_post_ids, $posts_per_page );
            $hotel_list_query = self::ttbm_hotel_list_query( $posts_per_page );

            $counts = wp_count_posts('ttbm_hotel');

            $published_count = isset($counts->publish) ? $counts->publish : 0;
            $trash_count     = isset($counts->trash) ? $counts->trash : 0;
            $draft_count     = isset($counts->draft) ? $counts->draft : 0;
            $total_count = $published_count + $trash_count + $draft_count;

            $trash_link = add_query_arg([
                'post_status' => 'trash',
                'post_type'   => 'ttbm_hotel',
            ], admin_url('edit.php'));

            $draft_link = add_query_arg([
                'post_status' => 'draft',
                'post_type'   => 'ttbm_hotel',
            ], admin_url('edit.php'));

            ?>

            <div class="ttbm_hotel_tab_holder">

                <?php
                $hotel_data = self::ttbm_get_all_hotel_analysis_data();

                ?>
                <div class="ttbm_hotel_list_header_stats-container">
                    <div class="ttbm_hotel_list_header_stat-card ttbm_hotel_list_header_blue-card">
                        <div class="ttbm_hotel_list_header_stat-icon ttbm_hotel_list_header_blue-bg">
                            <i class="fas fa-hotel"></i>
                        </div>
                        <div class="ttbm_hotel_list_header_stat-info">
                            <div class="ttbm_hotel_list_header_stat-label"><?php echo esc_attr__( 'Total Hotels', 'tour-booking-manager' )?></div>
                            <div class="ttbm_hotel_list_header_stat-value ttbm_hotel_list_header_blue-value"><?php echo esc_attr( $hotel_data['total_hotel']);?></div>
                        </div>
                    </div>

                    <div class="ttbm_hotel_list_header_stat-card ttbm_hotel_list_header_green-card">
                        <div class="ttbm_hotel_list_header_stat-icon ttbm_hotel_list_header_green-bg">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="ttbm_hotel_list_header_stat-info">
                            <div class="ttbm_hotel_list_header_stat-label"><?php echo esc_attr__( 'Available Rooms', 'tour-booking-manager' )?></div>
                            <div class="ttbm_hotel_list_header_stat-value ttbm_hotel_list_header_green-value"><?php echo esc_attr( $hotel_data['totalRooms']);?></div>
                        </div>
                    </div>

                    <div class="ttbm_hotel_list_header_stat-card ttbm_hotel_list_header_purple-card">
                        <div class="ttbm_hotel_list_header_stat-icon ttbm_hotel_list_header_purple-bg">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="ttbm_hotel_list_header_stat-info">
                            <div class="ttbm_hotel_list_header_stat-label"><?php echo esc_attr__( 'Bookings Today', 'tour-booking-manager' )?></div>
                            <div class="ttbm_hotel_list_header_stat-value ttbm_hotel_list_header_purple-value"><?php echo esc_attr( $hotel_data['today_booked_rooms']);?></div>
                        </div>
                    </div>

                    <div class="ttbm_hotel_list_header_stat-card ttbm_hotel_list_header_orange-card">
                        <div class="ttbm_hotel_list_header_stat-icon ttbm_hotel_list_header_orange-bg">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="ttbm_hotel_list_header_stat-info">
                            <div class="ttbm_hotel_list_header_stat-label"><?php echo esc_attr__( 'Avg Rating', 'tour-booking-manager' )?></div>
                            <div class="ttbm_hotel_list_header_stat-value ttbm_hotel_list_header_orange-value">4.7</div>
                        </div>
                    </div>
                </div>


                <ul class="ttbm_hotel_tab_menu">
                    <li class="ttbm_hotel_tab_item active" data-tab="ttbm_hotel_list_tab"><?php echo esc_attr__( 'Hotel Lists', 'tour-booking-manager' )?></li>
                    <li class="ttbm_hotel_tab_item" data-tab="ttbm_hotel_booking_tab"><?php echo esc_attr__( 'Hotel Booking Lists', 'tour-booking-manager' )?></li>
                </ul>
                <div class="ttbm_hotel_tab_content_holder">

                    <div id="ttbm_hotel_list_tab" class="ttbm_hotel_tab_content active">
                        <div class="ttbm_total_booking_wrapper" style="display: block">

                            <div class="ttbm_hotel_list_header">


                                <div class="ttbm_tour_list_text_header">

                                    <div class="ttbm_travel_list_header_text">
                                        <h2 class="ttbm_total_booking_title"><?php echo esc_attr__( 'Hotel List', 'tour-booking-manager' )?></h2>
                                    </div>

                                    <div class="ttbm_hotel_count_holder">
                                        <div class="ttbm_travel_filter_item ttbm_filter_btn_active_bg_color" data-filter-item="all">All (<?php echo esc_attr( $total_count )?>)</div>
                                        <div class="ttbm_travel_filter_item ttbm_filter_btn_bg_color" data-filter-item="publish">Publish (<?php echo esc_attr( $published_count )?>)</div>
                                        <div class="ttbm_travel_filter_item ttbm_filter_btn_bg_color" data-filter-item="draft">Draft (<?php echo esc_attr( $draft_count )?>)</div>

                                        <a class="ttbm_trash_link" href="<?php echo esc_url( $trash_link )?>" target="_blank">
                                            <div class="ttbm_total_trash_display">Trash Tour (<?php echo esc_attr( $trash_count )?>) </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="ttbm_hotel_search_addHolder">
                                    <div class="ttbm_add_new_hotel_header">
                                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=ttbm_hotel')); ?>" class="ttbm_add_new_hotel_text">
                                            <i class="fas fa-plus"></i> <?php echo esc_attr__( 'Add New', 'tour-booking-manager' )?></a>
                                    </div>
                                    <div class="ttbm_hotel_titleSearchContainer">
                                        <input type="text" class="ttbm_hotel_title_SearchBox" id="ttbm_hotel_title_SearchBox" placeholder="Hotel Name Search...">
                                    </div>
                                </div>
                            </div>

                            <?php echo wp_kses_post( self::ttbm_display_Hotel_lists( $hotel_list_query, $posts_per_page ) )?>
                        </div>
                    </div>


                    <!--Booking List Display-->
                    <div id="ttbm_hotel_booking_tab" class="ttbm_hotel_tab_content">
                        <div class="ttbm_total_booking_wrapper" style="display: block">
                            <h2 class="ttbm_total_booking_title"><?php echo esc_attr__( 'Hotel Booking List', 'tour-booking-manager' )?></h2>

                            <div class="ttbm_total_booking_filter_section">
                                <span class="ttbm_total_booking_filter_label"><?php echo esc_attr__( 'Filter List By:', 'tour-booking-manager' )?></span>
                                <div class="ttbm_total_booking_filter_options">
                                    <label class="ttbm_total_booking_radio_container">
                                        <input type="radio" name="filter_type" value="travel" checked>
                                        <span class="ttbm_total_booking_radio_text"><?php echo esc_attr__( 'Hotel', 'tour-booking-manager' )?></span>
                                    </label>
                                </div>
                                <div class="ttbm_total_booking_filter_controls">

                                    <div data-collapse="#ttbm_list_id" class="mActive">
                                        <?php TTBM_Layout::hotel_list_in_select( 'all_hotel_list' ); ?>
                                    </div>

                                    <input type="text" name="ttbm_booking_date_filter" id="ttbm_booking_date_filter" class="ttbm_booking_date_filter" placeholder="Select date">
                                    <button class="ttbm_total_booking_filter_btn"><?php  esc_html_e( 'Filter', 'tour-booking-manager' )?></button>
                                    <button class="ttbm_total_booking_reset_btn"><?php  esc_html_e( 'Reset', 'tour-booking-manager' )?></button>
                                </div>
                            </div>

                            <div class="ttbm_total_booking_summary">
                                <div class="ttbm_total_booking_found"><?php echo esc_attr__( 'Total ', 'tour-booking-manager' )?> <span class="ttbm_total_posts"><?php echo esc_attr( $query->found_posts ); ?></span><?php echo esc_attr__( ' Order Found', 'tour-booking-manager' )?></div>
                                <div class="ttbm_total_booking_showing"><?php echo esc_attr__( 'Showing ', 'tour-booking-manager' )?>  <span class="ttbm_showing_items"><?php echo esc_attr( $query->post_count ) ; ?></span><?php echo esc_attr__( ' Order', 'tour-booking-manager' )?> </div>
                                <div class="ttbm_total_booking_per_page">
                                    <span><?php echo esc_attr__( 'Guest Per Page', 'tour-booking-manager' )?></span>
                                    <input type="number" value="<?php echo esc_attr( $posts_per_page )?>" class="ttbm_total_booking_page_input" readonly>
                                </div>
                            </div>

                            <table class="ttbm_total_booking_table" >
                                <thead class="ttbm_total_booking_thead">
                                <tr>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Order ID', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Billing Information', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Hotel', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Total Days', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Rooms', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Check In Date', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Check Out Date', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Order Date', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Order Status', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Paid Amount', 'tour-booking-manager'); ?></th>
                                    <th class="ttbm_total_booking_th"><?php echo esc_attr__('Payment Method', 'tour-booking-manager'); ?></th>
                                </tr>
                                </thead>
                                <tbody class="ttbm_total_booking_tbody" id="ttbm_total_booking_tbody">
                                <?php echo wp_kses_post( self::ttbm_display_booking_lists( $query ) )?>
                                </tbody>
                            </table>

                            <?php if( $query->found_posts > $posts_per_page ){?>
                                <div class="ttbm_hotel_booking_load_more_holder">
                                    <button class="ttbm_hotel_booking_load_more_btn"><?php esc_attr_e( 'Load More', 'tour-booking-manager');?></button>
                                </div>
                            <?php }?>

                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        public static function ttbm_display_booking_lists( $query, $load_more='' ) {
            ob_start();
            ?>
            <?php
                $sl = 1;
                $billing_email = $billing_phone = $billing_address = '';

                if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
                    $order_id       = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_order_id', true);
                    $billing_name   = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_customer_name', true);
                    $travel_name    = get_post_meta(get_the_ID(), '_ttbm_hotel_title', true);
                    $booking_days   = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_days', true);
                    $hotel_infos    = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_room_info', true);
                    $check_in       = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_checkin_date', true);
                    $check_in       = gmdate('F j, Y', strtotime($check_in));
                    $check_out      = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_checkout_date', true);
                    $check_out      = gmdate('F j, Y', strtotime($check_out));
                    $order_date     = get_the_date('F j, Y');
                    $order_status   = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_status', true);
                    $paid_amount    = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_price', true);
                    $paid_amount    = str_replace(',', '', $paid_amount);
                    $payment_method = get_post_meta(get_the_ID(), '_ttbm_hotel_booking_payment_method', true);

                    $order = wc_get_order( $order_id );
                    if ( $order ) {
                        $billing_email   = $order->get_billing_email();
                        $billing_phone   = $order->get_billing_phone();
                        $billing_address = $order->get_formatted_billing_address();
                    }
                    ?>
                    <tr class="ttbm_total_booking_tr" id="<?php echo esc_attr( get_the_ID() )?>">
                        <td class="ttbm_total_booking_td ttbm_total_booking_order_id">#<?php echo esc_html($order_id); ?></td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_billing">
                            <?php echo esc_html($billing_name); ?>
                            <div class="ttbm_booking_user_more_info_holder">
                                <div class="ttbm_booking_user_more_info" style="display: none">
                                    <div class="ttbm_billing_name"><?php esc_attr_e( 'Name:', 'tour-booking-manager'); echo esc_attr( $billing_name )?></div>
                                    <div class="ttbm_billing_email"><?php esc_attr_e( 'Email:', 'tour-booking-manager'); echo esc_attr( $billing_email )?></div>
                                    <div class="ttbm_billing_phone"><?php esc_attr_e( 'Phone:', 'tour-booking-manager'); echo esc_attr( $billing_phone )?></div>
                                    <div class="ttbm_billing_address"><?php esc_attr_e( 'Address:', 'tour-booking-manager'); echo wp_kses_post( $billing_address )?></div>
                                </div>
                                <button class="ttbm_total_booking_view_more"><?php echo esc_attr__('View More', 'tour-booking-manager'); ?></button>
                            </div>
                        </td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_travel">
                            <?php echo esc_html($travel_name); ?>
                        </td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_qty"><?php echo esc_html($booking_days); ?></td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_ticket">
                            <?php
                            if ( is_array( $hotel_infos ) && !empty( $hotel_infos ) ) {
                                foreach ($hotel_infos as $room_name => $room_infos) {
                                    ?>
                                    <div class="ttbm_booking_room_data"><?php echo esc_attr($room_name); ?>(<?php echo esc_attr($room_infos['quantity']); ?>)</div>
                                    <?php
                                }
                            }
                            ?>
                        </td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_tour_date"><?php echo esc_html($check_in); ?></td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_tour_date"><?php echo esc_html($check_out); ?></td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_order_date"><?php echo esc_html($order_date); ?></td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_status"><?php echo esc_html($order_status); ?></td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_amount">
                            <?php echo wp_kses_post( wc_price( $paid_amount ) ); ?>
                        </td>
                        <td class="ttbm_total_booking_td ttbm_total_booking_payment"><?php echo esc_html($payment_method); ?></td>
                    </tr>

                <?php endwhile;
                wp_reset_postdata();
            else :
                if( $load_more === '' ){
                ?>
                <tr><td colspan="14"><?php echo esc_attr__('No bookings found.', 'tour-booking-manager'); ?></td></tr>
            <?php } endif; ?>
            <?php

            return ob_get_clean();
        }

        public static function display_hotel_lists_as_table( $query, $is_load_more = '' ) {
            $tag_color = array(
              'first-tag', 'second-tag', 'third-tag',
            );

            ob_start();
            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
                    $post_id   = get_the_ID();
                    $title     = get_the_title();
                    $desc      = get_the_excerpt();
                    $image     = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
                    $image     = $image ? $image : esc_url( includes_url( 'images/media/default.png' ) ); // fallback WP default
                    $services  = get_post_meta( $post_id, 'ttbm_service_included_in_price', true );
                    $location  = get_post_meta( $post_id, 'ttbm_hotel_location', true );

                    $post_status = get_post_status();
                    if( $post_status === 'publish' ){
                        $status_background = '#b7f1ba';
                        $status_icon = 'far fa-paper-plane';
                    }else{
                        $status_background = '#ede2b4';
                        $status_icon = 'far fa-file-alt';
                    }

                    ?>
                    <tr id="hotel_<?php echo esc_attr( $post_id ); ?>" class="ttbm-tour-card" data-travel-type="<?php echo esc_attr( $post_status )?>">
                        <td class="ttbm-hotel-list-column-image">
                            <div class="ttbm-hotel-list-image-placeholder">
                                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" width="60" height="60" />
                            </div>
                        </td>
                        <td class="ttbm-hotel-list-column-hotel">
                            <div class="ttbm-hotel-list-hotel-title">
                                <?php echo esc_html( $title ); ?> <span class="hotel-id">(ID: <?php echo esc_html( $post_id ); ?>)</span>
                            </div>
                            <?php if ( $location ) : ?>
                                <div class="ttbm-hotel-list-hotel-location">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ( $desc ) : ?>
                                <div class="ttbm-hotel-list-hotel-description">
                                    <?php echo esc_html( $desc ); ?>
                                </div>
                            <?php endif; ?>
                            <?php
                            $icon = '';
                            if ( ! empty( $services ) && is_array( $services ) ) : ?>
                                <div class="ttbm-hotel-list-hotel-features">
                                    <?php
                                    $count = 0;
                                    foreach ( $services as $key => $service ) {
                                        $term = get_term_by('name', $service, 'ttbm_tour_features_list');
                                        if ($term) {
                                            $icon = get_term_meta($term->term_id, 'ttbm_feature_icon', true);
                                            $icon = $icon ?: 'fas fa-forward';
                                        }
                                        if( $count < 3){
                                        ?>
                                        <span class="ttbm-hotel-list-feature-tag <?php echo esc_attr( $tag_color[$count] );?>">
                                            <i class=" <?php echo esc_attr( $icon )?>"></i>
                                            <?php echo esc_html( $service ); ?>
                                        </span>
                                    <?php }
                                        $count++;
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="ttbm-hotel-list-column-actions">
                            <div class="ttbm-hotel-lists-status">
                                <div class="ttbm_hotel_status_items" >
                                    <div class="meta-icon" style="background-color: <?php echo esc_attr( $status_background );?>"><i class="<?php echo esc_attr( $status_icon )?>"></i></div>
                                    <div class="ttbm_travel_status"><?php echo esc_attr( $post_status )?></div>
                                </div>
                            </div>
                        </td>
                        <td class="ttbm-hotel-list-column-actions">
                            <div class="ttbm-hotel-list-action-buttons">
                                <a href="<?php echo esc_url(get_permalink( $post_id )); ?>"
                                   class="ttbm-hotel-list-action-btn ttbm-hotel-list-view-btn"
                                   title="View" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="<?php echo esc_url( get_edit_post_link( $post_id )); ?>"
                                   class="ttbm-hotel-list-action-btn ttbm-hotel-list-edit-btn"
                                   title="Edit" target="_blank">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a title="<?php echo esc_attr__('Duplicate Hotel ', 'tour-booking-manager') . ' : ' . esc_attr(get_the_title($post_id)); ?>"  class="ttbm-hotel-list-action-btn ttbm_hotel_duplicate_post" href="<?php echo esc_attr(wp_nonce_url(
                                    admin_url('admin.php?action=ttbm_duplicate_post&post_id=' . $post_id),
                                    'ttbm_duplicate_post_' . $post_id
                                )); ?>">
                                    <i class="fa fa-clone"></i>
                                </a>

                                <a href="<?php echo get_delete_post_link( $post_id ); ?>"
                                   class="ttbm-hotel-list-action-btn ttbm-hotel-list-delete-btn"
                                   data-confirm="Are you sure you want to delete this hotel?"
                                   title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>

                        </td>
                    </tr>
                <?php
                endwhile;
            endif;
            wp_reset_postdata();

            return ob_get_clean();
        }

        public static function ttbm_display_Hotel_lists( $query, $posts_per_page ) {
            ob_start(); ?>
                <div class="hotel-list-container">
                    <table class="ttbm-hotel-list-table">
                        <thead>
                        <tr>
                            <th scope="col" class="ttbm-hotel-list-column-image"><?php echo esc_attr__('Image', 'tour-booking-manager'); ?></th>
                            <th scope="col" class="ttbm-hotel-list-column-hotel"><?php echo esc_attr__('Hotel', 'tour-booking-manager'); ?></th>
                            <th scope="col" class="ttbm-hotel-list-column-hotel"><?php echo esc_attr__('Status', 'tour-booking-manager'); ?></th>
                            <th scope="col" class="ttbm-hotel-list-column-actions"><?php echo esc_attr__('Actions', 'tour-booking-manager'); ?></th>
                        </tr>
                        </thead>
                        <tbody class="ttbm_hotel_list_view" id="ttbm_hotel_list_view">
                            <?php echo wp_kses_post( self::display_hotel_lists_as_table( $query ) )?>
                        </tbody>
                    </table>
                    <?php if( $query->found_posts > $posts_per_page ){?>
                        <div class="ttbm_hotel_list_load_more_btn_holder">
                            <button class="ttbm_hotel_list_load_more_btn" id="ttbm_hotel_list_load_more_btn"><?php esc_attr_e( 'Load More', 'tour-booking-manager');?></button>
                        </div>
                    <?php }?>
                </div>
            <?php
            return ob_get_clean();
        }
    }

    new TTBM_Hotel_Booking_Lists();
}