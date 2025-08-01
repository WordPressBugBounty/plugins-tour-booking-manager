<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! class_exists( 'TtbmAddMetaBox' ) ) {
	class TtbmAddMetaBox {

		public $data = array();
		public function __construct( $args ) {
			$this->data = &$args;
			if ( $this->get_meta_box_screen()[0] == 'ttbm_tour' && $this->get_meta_box_context() == 'normal' ) {
				add_action( 'ttbm_meta_box_tab_name', array( $this, 'mp_event_all_in_tab_menu_list' ) );
				add_action( 'ttbm_meta_box_tab_content', array( $this, 'meta_box_callback' ), 10, 1 );
			}else{
				add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 12 );
			}
			add_action( 'save_post', array( $this, 'save_post' ), 12 );
		}

		public function add_meta_boxes() {
			add_meta_box( $this->get_meta_box_id(), $this->get_meta_box_title(), array( $this, 'meta_box_callback' ),
			$this->get_meta_box_screen(), $this->get_meta_box_context(), $this->get_meta_box_priority(), $this->get_callback_args() );
		}


		public function save_post( $post_id ) {
			if (
				!isset($_POST['ttbm_ticket_type_nonce']) ||
				!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ttbm_ticket_type_nonce'])), 'ttbm_ticket_type_nonce')
			) {
				return;
			}	
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}		
			if (!current_user_can('edit_post', $post_id)) {
				return;
			}				
			if (get_post_type($post_id) == 'ttbm_tour' || get_post_type($post_id) == 'ttbm_hotel' )  {
			$get_option_name = $this->get_option_name();
			$post_id         = $this->get_post_id();
			if ( ! empty( $get_option_name ) ):
				$option_value = isset( $_POST[ $get_option_name ]) ? maybe_serialize( sanitize_text_field(wp_unslash($_POST[ $get_option_name ])) ):'';
				update_post_meta( $post_id, $get_option_name, $option_value );
			else:
				foreach ( $this->get_panels() as $panelsIndex => $panel ):
					foreach ( $panel['sections'] as $sectionIndex => $section ):
						foreach ( $section['options'] as $option ):
							$option_value = isset( $_POST[ $option['id'] ] ) ? sanitize_text_field(wp_unslash($_POST[ $option['id'] ])) : '';
							if ( is_array( $option_value ) ) {
								$option_value = maybe_serialize( $option_value );
							}
							if(!empty($option['id'])){
								update_post_meta( $post_id, $option['id'], $option_value );
							}
						endforeach;
					endforeach;
				endforeach;
			endif;
		}
		}

		public function mp_event_all_in_tab_menu_list() {
			?>
            <li data-tabs-target="#<?php echo esc_html($this->get_meta_box_id()); ?>">
				<?php 
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo mep_esc_html($this->get_meta_box_title()); ?>
            </li>
			<?php
		}


		public function meta_box_callback( $post_id = null ) {
			$get_nav_position = $this->get_nav_position();
			if ( $this->get_meta_box_screen()[0] == 'ttbm_tour' && $this->get_meta_box_context() == 'normal' && !isset( $this->data['seat_plan'] )) {
				?>
                <div class="tabsItem" data-tabs="#<?php echo esc_html($this->get_meta_box_id()); ?>">
				<?php
			}
			?>
            <div class='wrap ppof-settings ppof-metabox'>
                <div class='navigation <?php echo esc_html($get_nav_position); ?>'>
                    <div class="nav-items">
						<?php
						do_action( 'nav_nav_items_top' );
						$current_page = 1;
						foreach ( $this->get_panels() as $page_id => $page ):
							$page_settings = ! empty( $page['sections'] ) ? $page['sections'] : array();
							$page_settings_count = count( $page_settings );
							?>
                            <li class="nav-item-wrap <?php if ( ( $page_settings_count > 1 ) ) {
								echo 'has-child';
							} ?> <?php if ( $current_page == $page_id ) {
								echo 'active';
							} ?>">
                                <a dataid="<?php echo esc_html($page_id); ?>" href='#<?php echo
								esc_html($page_id); ?>' class='nav-item'><?php echo esc_html($page['page_nav']); ?>

									<?php if ( ( $page_settings_count > 1 ) ) {
										echo '<i class="child-nav-icon fas fa-angle-down"></i>';
									} ?>
                                </a>
								<?php
								if ( ( $page_settings_count > 1 ) ):
									?>
                                    <ul class="child-navs">
										<?php
										foreach ( $page_settings as $section_id => $nav_sections ):
											$nav_sections_title = ! empty( $nav_sections['nav_title'] ) ? $nav_sections['nav_title'] : $nav_sections['title'];
											?>
                                            <li>
                                                <a sectionId="<?php echo esc_html($section_id); ?>" dataid="<?php echo esc_html($page_id); ?>" href='#<?php echo esc_html($section_id); ?>' class='nav-item <?php if ( $current_page == $page_id ) {
													echo 'active';
												} ?>'><?php 
												// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
												echo mep_esc_html($nav_sections_title); ?>
                                                </a>
                                            </li>
										<?php
										endforeach;
										?>
                                    </ul>
								<?php
								endif;
								?>
                            </li>
							<?php
							$current_page ++;
						endforeach;
						?>
						<?php
						do_action( 'nav_nav_items_bottom' );
						?>
                    </div>
                    <div class="nav-footer">
						<?php
						do_action( 'nav_footer_top' );	
						do_action( 'nav_footer_bottom' );
						?>
                    </div>
                </div>
				<?php
				if ( $get_nav_position == 'right' ) {
					$form_wrapper_position = 'left';
				} elseif ( $get_nav_position == 'left' ) {
					$form_wrapper_position = 'right';
				} elseif ( $get_nav_position == 'top' ) {
					$form_wrapper_position = 'full-width-top';
				} else {
					$form_wrapper_position = 'full-width';
				}
				?>

			<?php
				$current_page = 1;
				foreach ( $this->get_panels() as $panelsIndex => $panel ):
					?>
					<div class="tab-content <?php echo esc_attr($current_page == 1 ? 'active':'');  ?>  tab-content-<?php echo esc_html($panelsIndex); ?>" id="<?php echo esc_html($panelsIndex); ?>">
						<?php
						wp_nonce_field('ttbm_ticket_type_nonce', 'ttbm_ticket_type_nonce');
						foreach ( $panel['sections'] as $sectionIndex => $section ):
							?>
								<h2 class="date-configeration"><?php echo esc_html($section['title']); ?></h2>
								<p class="date-configeration-desc"><?php echo esc_html($section['description']); ?></p>
								<section>
									<div class="ttbm-header">
										<h4><i class="fas fa-calendar"></i><?php esc_html_e('Date Settings', 'tour-booking-manager'); ?></h4>
									</div>
									<table class="form-table">
										<tbody>
										<?php
										foreach ( $section['options'] as $option ):
											$details = isset( $option['details'] ) ? $option['details'] : "";
											?>
											<tr id='mage_row_<?php echo esc_html($option['id']); ?>' class='mage_row_<?php echo esc_html($option['id']); ?>'>
												<th scope="row">
													<label><?php echo esc_html($option['title']); ?><?php if(!empty($details)){ ?><?php } ?></label>
													<span class="span-row"><?php echo esc_html($details);?></span>
												</th>
												<td>
													<?php
													$option_value = get_post_meta( $this->get_post_id(), $option['id'], true );
													if ( is_serialized( $option_value ) ) {
														$option_value = @unserialize( $option_value, ['allowed_classes' => false] );
													}
													$option['value'] = $option_value;
													$this->field_generator( $option )
													?>
												</td>
											</tr>
										<?php
										endforeach;
										?>
										</tbody>
									</table>
								</section>
							
						<?php
						endforeach;
						?>
					</div>
					<?php
					$current_page ++;
				endforeach;
			?>

			<?php 
				$dynamic_action_name = 'mage_meta_tab_details_after_'.$this->get_meta_box_id();
				do_action($dynamic_action_name,$post_id);
			?>
            </div>
			<?php
			if ( $this->get_meta_box_screen()[0] == 'ttbm_tour' && $this->get_meta_box_context() == 'normal'  && !isset( $this->data['seat_plan'] )) {
				?>
                </div>
				<?php
			}
		}


		public function field_generator( $option ) {
			$id      = isset( $option['id'] ) ? $option['id'] : "";
			$type    = isset( $option['type'] ) ? $option['type'] : "";
			$details = isset( $option['details'] ) ? $option['details'] : "";
			$post_id = $this->get_post_id();
			if ( empty( $id ) ) {
				return;
			}
			$prent_option_name   = $this->get_option_name();
			$FormFieldsGenerator = new FormFieldsGenerator();
			if ( ! empty( $prent_option_name ) ):
				$field_name           	= $prent_option_name . '[' . $id . ']';
				$option['field_name'] 	= $field_name;
				$prent_option_value 	= get_post_meta( $post_id, $prent_option_name, true );
				$prent_option_value 	= is_serialized( $prent_option_value ) ? @unserialize( $prent_option_value, ['allowed_classes' => false] ): array();
				$option['value']    	= isset( $prent_option_value[ $id ] ) ? $prent_option_value[ $id ] : '';
			else:
				$option['field_name'] 	= $id;
				$option_value         	= get_post_meta( $post_id, $id, true );
				$option['value']      	= is_serialized( $option_value ) ? @unserialize( $option_value, ['allowed_classes' => false] ) : $option_value;

			endif;
			if (sizeof($option) > 0 && isset($option['type'])) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
				echo mep_field_generator($option['type'], $option);
				do_action("wp_theme_settings_field_$type", $option);
			}
            /*
			if ( ! empty( $details ) ) {
				echo "<p class='description'>$details</p>";
			}
            */
		}


		private function get_meta_box_id() {
			if ( isset( $this->data['meta_box_id'] ) ) {
				return $this->data['meta_box_id'];
			} else {
				return "";
			}
		}

		private function get_meta_box_title() {
			if ( isset( $this->data['meta_box_title'] ) ) {
				return $this->data['meta_box_title'];
			} else {
				return "";
			}
		}

		private function get_meta_box_screen() {
			if ( isset( $this->data['screen'] ) ) {
				return $this->data['screen'];
			} else {
				return array( 'post' );
			}
		}

		private function get_meta_box_context() {
			if ( isset( $this->data['context'] ) ) {
				return $this->data['context'];
			} else {
				return 'normal';
			}
		}

		private function get_meta_box_priority() {
			if ( isset( $this->data['priority'] ) ) {
				return $this->data['priority'];
			} else {
				return "high";
			}
		}

		private function get_callback_args() {
			if ( isset( $this->data['callback_args'] ) ) {
				return $this->data['callback_args'];
			} else {
				return array();
			}
		}

		private function get_panels() {
			if ( isset( $this->data['panels'] ) ) {
				return $this->data['panels'];
			} else {
				return array();
			}
		}

		private function get_item_name() {
			if ( isset( $this->data['item_name'] ) ) {
				return $this->data['item_name'];
			} else {
				return "PickPlugins";
			}
		}

		private function get_item_version() {
			if ( isset( $this->data['item_version'] ) ) {
				return $this->data['item_version'];
			} else {
				return "1.0.0";
			}
		}

		private function get_option_name() {
			if ( isset( $this->data['option_name'] ) ) {
				return $this->data['option_name'];
			} else {
				return false;
			}
		}

		private function get_nav_position() {
			if ( isset( $this->data['nav_position'] ) ) {
				return $this->data['nav_position'];
			} else {
				return 'left';
			}
		}

		private function get_post_id() {
			$post_id = get_the_ID();
			return $post_id;
		}
	}
}
