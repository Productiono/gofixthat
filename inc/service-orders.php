<?php
/**
 * Service Orders admin and webhook functionality.
 *
 * @package Apparel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Service Order custom post type.
 */
function apparel_register_service_order_cpt() {
	$labels = array(
		'name'          => __( 'Service Orders', 'apparel' ),
		'singular_name' => __( 'Service Order', 'apparel' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'exclude_from_search' => true,
		'show_in_nav_menus'  => false,
		'supports'           => array( 'title' ),
		'capability_type'    => 'post',
		'map_meta_cap'       => true,
	);

	register_post_type( 'service_order', $args );
}
add_action( 'init', 'apparel_register_service_order_cpt' );

/**
 * Add Service Orders submenu.
 */
function apparel_service_add_orders_submenu() {
	add_submenu_page(
		'edit.php?post_type=service',
		__( 'Orders', 'apparel' ),
		__( 'Orders', 'apparel' ),
		'manage_options',
		'apparel-service-orders',
		'apparel_service_render_orders_page'
	);
}
add_action( 'admin_menu', 'apparel_service_add_orders_submenu' );

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Service Orders list table.
 */
class Apparel_Service_Orders_Table extends WP_List_Table {
	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'service_order',
				'plural'   => 'service_orders',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Define columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'order_id'       => __( 'Order ID', 'apparel' ),
			'date'           => __( 'Date', 'apparel' ),
			'service'        => __( 'Service', 'apparel' ),
			'source_page'    => __( 'Source Page', 'apparel' ),
			'variation'      => __( 'Variation', 'apparel' ),
			'amount'         => __( 'Amount', 'apparel' ),
			'status'         => __( 'Status', 'apparel' ),
			'customer'       => __( 'Customer', 'apparel' ),
			'custom_fields'  => __( 'Custom Fields', 'apparel' ),
			'stripe_id'      => __( 'Stripe Session/Payment ID', 'apparel' ),
		);
	}

	/**
	 * Define sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'order_id' => array( 'ID', false ),
			'date'     => array( 'date', true ),
			'amount'   => array( 'amount_total', false ),
			'status'   => array( 'status', false ),
		);
	}

	/**
	 * Column defaults.
	 *
	 * @param array  $item        Item data.
	 * @param string $column_name Column name.
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		if ( isset( $item[ $column_name ] ) ) {
			return $item[ $column_name ];
		}

		return '';
	}

	/**
	 * Render column for service.
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_service( $item ) {
		if ( empty( $item['service_id'] ) ) {
			$service_name = $item['service_name'] ?? '';
			if ( ! $service_name ) {
				$service_name = __( 'Stripe Checkout Item', 'apparel' );
			}
			return esc_html( $service_name );
		}

		$service_id = (int) $item['service_id'];
		$title      = get_the_title( $service_id );
		$link       = get_edit_post_link( $service_id, 'display' );

		if ( $link ) {
			return sprintf( '<a href="%s">%s</a>', esc_url( $link ), esc_html( $title ) );
		}

		return esc_html( $title );
	}

	/**
	 * Render column for source page.
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_source_page( $item ) {
		$page_id = isset( $item['lead_gen_page_id'] ) ? (int) $item['lead_gen_page_id'] : 0;
		if ( ! $page_id ) {
			return '';
		}

		$title = get_the_title( $page_id );
		if ( ! $title ) {
			return '';
		}

		$link = get_edit_post_link( $page_id, 'display' );
		if ( $link ) {
			return sprintf( '<a href="%s">%s</a>', esc_url( $link ), esc_html( $title ) );
		}

		return esc_html( $title );
	}

	/**
	 * Render order ID column with actions.
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_order_id( $item ) {
		$order_id = isset( $item['order_id'] ) ? (int) $item['order_id'] : 0;
		if ( ! $order_id ) {
			return '';
		}

		$edit_link = get_edit_post_link( $order_id, 'display' );
		$label     = sprintf( '#%d', $order_id );

		$actions = array();
		if ( $edit_link ) {
			$actions['edit'] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $edit_link ),
				esc_html__( 'View/Edit', 'apparel' )
			);
		}

		$delete_link = get_delete_post_link( $order_id, '', true );
		if ( $delete_link ) {
			$actions['delete'] = sprintf(
				'<a href="%s" class="submitdelete">%s</a>',
				esc_url( $delete_link ),
				esc_html__( 'Delete', 'apparel' )
			);
		}

		$output = $edit_link ? sprintf( '<a href="%s">%s</a>', esc_url( $edit_link ), esc_html( $label ) ) : esc_html( $label );

		return $output . $this->row_actions( $actions );
	}

	/**
	 * Prepare table items.
	 */
	public function prepare_items() {
		$per_page     = 20;
		$current_page = $this->get_pagenum();

		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'date';
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'DESC';

		$args = array(
			'post_type'              => 'service_order',
			'posts_per_page'         => $per_page,
			'paged'                  => $current_page,
			'post_status'            => array( 'publish', 'private' ),
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => false,
			'update_post_meta_cache' => true,
		);

		$meta_query = array();
		if ( isset( $_GET['status'] ) && '' !== $_GET['status'] ) {
			$meta_query[] = array(
				'key'   => '_status',
				'value' => sanitize_text_field( wp_unslash( $_GET['status'] ) ),
			);
		}
		if ( ! empty( $meta_query ) ) {
			$args['meta_query'] = $meta_query;
		}

		$date_query = array();
		if ( ! empty( $_GET['date_from'] ) ) {
			$date_query['after'] = sanitize_text_field( wp_unslash( $_GET['date_from'] ) );
		}
		if ( ! empty( $_GET['date_to'] ) ) {
			$date_query['before'] = sanitize_text_field( wp_unslash( $_GET['date_to'] ) );
		}
		if ( $date_query ) {
			$date_query['inclusive'] = true;
			$args['date_query']      = array( $date_query );
		}

		if ( ! empty( $_GET['s'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
		}

		if ( 'amount_total' === $orderby ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_amount_total';
		} elseif ( 'status' === $orderby ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = '_status';
		} else {
			$args['orderby'] = $orderby;
		}
		$args['order'] = $order;

		$query = new WP_Query( $args );

		$items = array();
		foreach ( $query->posts as $post ) {
			$service_id    = get_post_meta( $post->ID, '_service_id', true );
			$service_name  = get_post_meta( $post->ID, '_service_name', true );
			$variation_id  = get_post_meta( $post->ID, '_variation_id', true );
			$variation_name = get_post_meta( $post->ID, '_variation_name', true );
			$amount_total  = get_post_meta( $post->ID, '_amount_total', true );
			$currency      = get_post_meta( $post->ID, '_currency', true );
			$status        = get_post_meta( $post->ID, '_status', true );
			$customer_email = get_post_meta( $post->ID, '_customer_email', true );
			$customer_name  = get_post_meta( $post->ID, '_customer_name', true );
			$customer_phone = get_post_meta( $post->ID, '_customer_phone', true );
			$session_id    = get_post_meta( $post->ID, '_stripe_session_id', true );
			$payment_intent = get_post_meta( $post->ID, '_stripe_payment_intent_id', true );
			$custom_fields = get_post_meta( $post->ID, '_stripe_custom_fields', true );
			$lead_gen_page_id = get_post_meta( $post->ID, '_lead_gen_page_id', true );

			$amount_display = '';
			if ( '' !== $amount_total ) {
				$amount_display = sprintf( '%s %s', number_format_i18n( (float) $amount_total, 2 ), strtoupper( $currency ) );
			}

			$customer_details = array_filter(
				array(
					$customer_name,
					$customer_email,
					$customer_phone,
				)
			);

			$custom_fields_summary = apparel_service_get_custom_fields_summary( $custom_fields );

			$items[] = array(
				'order_id'       => (int) $post->ID,
				'date'           => esc_html( get_the_date( '', $post ) ),
				'service_id'     => $service_id,
				'service_name'   => $service_name,
				'service'        => '',
				'lead_gen_page_id' => $lead_gen_page_id ? (int) $lead_gen_page_id : 0,
				'source_page'    => '',
				'variation'      => esc_html( $variation_name ? $variation_name : $variation_id ),
				'amount'         => esc_html( $amount_display ),
				'status'         => esc_html( ucfirst( (string) $status ) ),
				'customer'       => esc_html( implode( ' / ', $customer_details ) ),
				'custom_fields'  => esc_html( $custom_fields_summary ),
				'stripe_id'      => esc_html( $session_id ? $session_id : $payment_intent ),
			);
		}

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$this->items = $items;

		$this->set_pagination_args(
			array(
				'total_items' => (int) $query->found_posts,
				'per_page'    => $per_page,
				'total_pages' => (int) $query->max_num_pages,
			)
		);
	}
}

/**
 * Render orders page.
 */
function apparel_service_render_orders_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$table = new Apparel_Service_Orders_Table();
	$table->prepare_items();
	$current_status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
	$current_from   = isset( $_GET['date_from'] ) ? sanitize_text_field( wp_unslash( $_GET['date_from'] ) ) : '';
	$current_to     = isset( $_GET['date_to'] ) ? sanitize_text_field( wp_unslash( $_GET['date_to'] ) ) : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Service Orders', 'apparel' ); ?></h1>
		<form method="get">
			<input type="hidden" name="post_type" value="service" />
			<input type="hidden" name="page" value="apparel-service-orders" />
			<div class="tablenav top">
				<div class="alignleft actions">
					<label for="filter-status" class="screen-reader-text"><?php esc_html_e( 'Filter by status', 'apparel' ); ?></label>
					<select name="status" id="filter-status">
						<option value=""><?php esc_html_e( 'All statuses', 'apparel' ); ?></option>
						<?php foreach ( apparel_service_order_statuses() as $status ) : ?>
							<option value="<?php echo esc_attr( $status ); ?>" <?php selected( $current_status, $status ); ?>>
								<?php echo esc_html( ucfirst( $status ) ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<label for="filter-date-from" class="screen-reader-text"><?php esc_html_e( 'From date', 'apparel' ); ?></label>
					<input type="date" name="date_from" id="filter-date-from" value="<?php echo esc_attr( $current_from ); ?>" />
					<label for="filter-date-to" class="screen-reader-text"><?php esc_html_e( 'To date', 'apparel' ); ?></label>
					<input type="date" name="date_to" id="filter-date-to" value="<?php echo esc_attr( $current_to ); ?>" />
					<?php submit_button( __( 'Filter', 'apparel' ), 'secondary', false, false, array( 'id' => 'post-query-submit' ) ); ?>
				</div>
				<?php $table->search_box( __( 'Search Orders', 'apparel' ), 'service-orders' ); ?>
				<br class="clear" />
			</div>
			<?php $table->display(); ?>
		</form>
	</div>
	<?php
}

/**
 * Get available order statuses.
 *
 * @return array
 */
function apparel_service_order_statuses() {
	return array( 'paid', 'completed', 'cancelled', 'refunded', 'partially_refunded', 'failed' );
}

/**
 * Register Stripe webhook endpoint.
 */
function apparel_service_register_stripe_webhook() {
	register_rest_route(
		'apparel/v1',
		'/stripe/webhook',
		array(
			'methods'             => 'POST',
			'callback'            => 'apparel_service_handle_stripe_webhook',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'apparel_service_register_stripe_webhook' );

/**
 * Register admin meta boxes for service orders.
 */
function apparel_service_register_order_meta_boxes() {
	add_meta_box(
		'apparel-service-order-details',
		__( 'Service Order Details', 'apparel' ),
		'apparel_service_render_order_details_metabox',
		'service_order',
		'normal',
		'high'
	);

	add_meta_box(
		'apparel-service-order-notes',
		__( 'Admin Notes', 'apparel' ),
		'apparel_service_render_order_notes_metabox',
		'service_order',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes_service_order', 'apparel_service_register_order_meta_boxes' );

/**
 * Render order details meta box.
 *
 * @param WP_Post $post Post object.
 */
function apparel_service_render_order_details_metabox( $post ) {
	$service_id      = get_post_meta( $post->ID, '_service_id', true );
	$service_name    = get_post_meta( $post->ID, '_service_name', true );
	$variation_id    = get_post_meta( $post->ID, '_variation_id', true );
	$variation_name  = get_post_meta( $post->ID, '_variation_name', true );
	$amount_total    = get_post_meta( $post->ID, '_amount_total', true );
	$currency        = get_post_meta( $post->ID, '_currency', true );
	$status          = get_post_meta( $post->ID, '_status', true );
	$customer_email  = get_post_meta( $post->ID, '_customer_email', true );
	$customer_name   = get_post_meta( $post->ID, '_customer_name', true );
	$customer_phone  = get_post_meta( $post->ID, '_customer_phone', true );
	$customer_addr   = get_post_meta( $post->ID, '_customer_address', true );
	$custom_fields   = get_post_meta( $post->ID, '_stripe_custom_fields', true );
	$session_id      = get_post_meta( $post->ID, '_stripe_session_id', true );
	$payment_intent  = get_post_meta( $post->ID, '_stripe_payment_intent_id', true );
	$created_at      = get_post_meta( $post->ID, '_created_at', true );
	$checkout_url    = get_post_meta( $post->ID, '_checkout_url', true );
	$quantity        = get_post_meta( $post->ID, '_quantity', true );
	$lead_gen_page_id = get_post_meta( $post->ID, '_lead_gen_page_id', true );
	$lead_gen_payment_link = get_post_meta( $post->ID, '_lead_gen_payment_link', true );

	$service_title = $service_id ? get_the_title( $service_id ) : ( $service_name ? $service_name : __( 'Stripe Checkout Item', 'apparel' ) );
	$service_link  = $service_id ? get_edit_post_link( $service_id, 'display' ) : '';
	$lead_gen_page_title = $lead_gen_page_id ? get_the_title( $lead_gen_page_id ) : '';
	$lead_gen_page_link  = $lead_gen_page_id ? get_edit_post_link( $lead_gen_page_id, 'display' ) : '';

	$amount_display = '';
	if ( '' !== $amount_total ) {
		$amount_display = sprintf( '%s %s', number_format_i18n( (float) $amount_total, 2 ), strtoupper( (string) $currency ) );
	}

	$created_display = $created_at ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), (int) $created_at ) : '';
	$custom_fields_display = apparel_service_format_custom_fields_for_display( $custom_fields );

	wp_nonce_field( 'apparel_service_order_update', 'apparel_service_order_nonce' );
	?>
	<table class="widefat striped">
		<tbody>
			<tr>
				<th><?php esc_html_e( 'Service', 'apparel' ); ?></th>
				<td>
					<?php if ( $service_link ) : ?>
						<a href="<?php echo esc_url( $service_link ); ?>"><?php echo esc_html( $service_title ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $service_title ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php if ( $lead_gen_page_id && $lead_gen_page_title ) : ?>
				<tr>
					<th><?php esc_html_e( 'Source Page', 'apparel' ); ?></th>
					<td>
						<?php if ( $lead_gen_page_link ) : ?>
							<a href="<?php echo esc_url( $lead_gen_page_link ); ?>"><?php echo esc_html( $lead_gen_page_title ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $lead_gen_page_title ); ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<th><?php esc_html_e( 'Variation', 'apparel' ); ?></th>
				<td><?php echo esc_html( $variation_name ? $variation_name : $variation_id ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Quantity', 'apparel' ); ?></th>
				<td><?php echo esc_html( $quantity ? $quantity : 1 ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Amount', 'apparel' ); ?></th>
				<td><?php echo esc_html( $amount_display ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Payment Status', 'apparel' ); ?></th>
				<td>
					<select name="apparel_service_order_status">
						<?php foreach ( apparel_service_order_statuses() as $option ) : ?>
							<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $status, $option ); ?>>
								<?php echo esc_html( ucfirst( $option ) ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Customer', 'apparel' ); ?></th>
				<td>
					<?php echo esc_html( $customer_name ); ?><br />
					<?php if ( $customer_email ) : ?>
						<a href="mailto:<?php echo esc_attr( $customer_email ); ?>"><?php echo esc_html( $customer_email ); ?></a><br />
					<?php endif; ?>
					<?php echo esc_html( $customer_phone ); ?><br />
					<?php echo esc_html( $customer_addr ); ?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Custom Fields', 'apparel' ); ?></th>
				<td>
					<?php if ( empty( $custom_fields_display ) ) : ?>
						<?php esc_html_e( 'None', 'apparel' ); ?>
					<?php else : ?>
						<ul>
							<?php foreach ( $custom_fields_display as $custom_field ) : ?>
								<li><?php echo esc_html( $custom_field ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Stripe Session ID', 'apparel' ); ?></th>
				<td><?php echo esc_html( $session_id ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Stripe Payment Intent', 'apparel' ); ?></th>
				<td><?php echo esc_html( $payment_intent ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Checkout URL', 'apparel' ); ?></th>
				<td>
					<?php if ( $checkout_url ) : ?>
						<a href="<?php echo esc_url( $checkout_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $checkout_url ); ?></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php if ( $lead_gen_payment_link ) : ?>
				<tr>
					<th><?php esc_html_e( 'Lead Gen Payment Link', 'apparel' ); ?></th>
					<td>
						<a href="<?php echo esc_url( $lead_gen_payment_link ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $lead_gen_payment_link ); ?></a>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<th><?php esc_html_e( 'Created At', 'apparel' ); ?></th>
				<td><?php echo esc_html( $created_display ); ?></td>
			</tr>
		</tbody>
	</table>
	<?php
}

/**
 * Render admin notes meta box.
 *
 * @param WP_Post $post Post object.
 */
function apparel_service_render_order_notes_metabox( $post ) {
	$notes = get_post_meta( $post->ID, '_admin_notes', true );
	?>
	<p>
		<textarea name="apparel_service_order_notes" rows="6" style="width:100%;"><?php echo esc_textarea( $notes ); ?></textarea>
	</p>
	<?php
}

/**
 * Save order meta updates.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post Post object.
 */
function apparel_service_save_order_meta( $post_id, $post ) {
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['apparel_service_order_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apparel_service_order_nonce'] ) ), 'apparel_service_order_update' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'service_order' !== $post->post_type ) {
		return;
	}

	if ( isset( $_POST['apparel_service_order_status'] ) ) {
		$status = sanitize_text_field( wp_unslash( $_POST['apparel_service_order_status'] ) );
		$previous_status = get_post_meta( $post_id, '_status', true );
		update_post_meta( $post_id, '_status', $status );
		if ( $status && $status !== $previous_status ) {
			apparel_service_maybe_send_order_status_email( $post_id, $status );
		}
	}

	if ( isset( $_POST['apparel_service_order_notes'] ) ) {
		$notes = sanitize_textarea_field( wp_unslash( $_POST['apparel_service_order_notes'] ) );
		update_post_meta( $post_id, '_admin_notes', $notes );
	}
}
add_action( 'save_post_service_order', 'apparel_service_save_order_meta', 10, 2 );

/**
 * Register Stripe checkout session creation endpoint.
 */
function apparel_service_register_checkout_session_endpoint() {
	register_rest_route(
		'apparel/v1',
		'/stripe/checkout-session',
		array(
			'methods'             => 'POST',
			'callback'            => 'apparel_service_create_checkout_session',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'apparel_service_register_checkout_session_endpoint' );

/**
 * Handle Stripe webhook payload.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function apparel_service_handle_stripe_webhook( WP_REST_Request $request ) {
	$payload    = $request->get_body();
	$sig_header = $request->get_header( 'stripe-signature' );
	$secret     = apparel_service_get_stripe_webhook_secret();

	if ( ! $secret || ! $sig_header || ! apparel_service_verify_stripe_signature( $payload, $sig_header, $secret ) ) {
		return new WP_REST_Response( array( 'message' => 'Invalid signature.' ), 400 );
	}

	$event = json_decode( $payload, true );
	if ( ! is_array( $event ) || empty( $event['type'] ) ) {
		return new WP_REST_Response( array( 'message' => 'Invalid payload.' ), 400 );
	}

	if ( 'checkout.session.completed' === $event['type'] && ! empty( $event['data']['object'] ) ) {
		apparel_service_process_checkout_session( $event['data']['object'] );
	}

	if ( 'checkout.session.async_payment_succeeded' === $event['type'] && ! empty( $event['data']['object'] ) ) {
		apparel_service_process_checkout_session( $event['data']['object'] );
	}

	if ( 'checkout.session.async_payment_failed' === $event['type'] && ! empty( $event['data']['object'] ) ) {
		$session = $event['data']['object'];
		apparel_service_update_order_status(
			'failed',
			sanitize_text_field( $session['id'] ?? '' ),
			sanitize_text_field( $session['payment_intent'] ?? '' )
		);
	}

	if ( 'payment_intent.payment_failed' === $event['type'] && ! empty( $event['data']['object'] ) ) {
		$payment_intent = $event['data']['object'];
		apparel_service_update_order_status(
			'failed',
			'',
			sanitize_text_field( $payment_intent['id'] ?? '' )
		);
	}

	if ( 'charge.refunded' === $event['type'] && ! empty( $event['data']['object'] ) ) {
		$charge = $event['data']['object'];
		$amount_refunded = isset( $charge['amount_refunded'] ) ? ( (float) $charge['amount_refunded'] / 100 ) : 0;
		$amount_total    = isset( $charge['amount'] ) ? ( (float) $charge['amount'] / 100 ) : 0;
		$status          = ( $amount_refunded && $amount_total && $amount_refunded < $amount_total ) ? 'partially_refunded' : 'refunded';
		apparel_service_update_order_status(
			$status,
			'',
			sanitize_text_field( $charge['payment_intent'] ?? '' ),
			array(
				'amount_refunded' => $amount_refunded,
				'currency'        => strtolower( sanitize_text_field( $charge['currency'] ?? '' ) ),
			)
		);
	}

	return new WP_REST_Response( array( 'received' => true ), 200 );
}

/**
 * Create a Stripe Checkout Session for a service purchase.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function apparel_service_create_checkout_session( WP_REST_Request $request ) {
	$params = $request->get_json_params();
	if ( empty( $params ) ) {
		$params = $request->get_body_params();
	}

	$price_id     = isset( $params['price_id'] ) ? sanitize_text_field( wp_unslash( $params['price_id'] ) ) : '';
	$service_id   = isset( $params['service_id'] ) ? absint( $params['service_id'] ) : 0;
	$variation_id = isset( $params['variation_id'] ) ? sanitize_text_field( wp_unslash( $params['variation_id'] ) ) : '';
	$quantity     = isset( $params['quantity'] ) ? absint( $params['quantity'] ) : 1;

	if ( ! $price_id || 0 !== strpos( $price_id, 'price_' ) ) {
		return new WP_REST_Response( array( 'message' => 'Invalid price ID.' ), 400 );
	}

	if ( $quantity < 1 ) {
		$quantity = 1;
	} elseif ( $quantity > 10 ) {
		$quantity = 10;
	}

	$service_match = array();
	if ( $service_id ) {
		$service_match = apparel_service_find_service_by_price_id( $price_id );
		if ( empty( $service_match ) || (int) $service_match['service_id'] !== $service_id ) {
			return new WP_REST_Response( array( 'message' => 'Price ID does not match service.' ), 400 );
		}
	} else {
		$service_match = apparel_service_find_service_by_price_id( $price_id );
		if ( empty( $service_match['service_id'] ) ) {
			return new WP_REST_Response( array( 'message' => 'Price ID does not match a service.' ), 400 );
		}
		$service_id = $service_match['service_id'];
	}

	if ( ! $variation_id && ! empty( $service_match['variation_id'] ) ) {
		$variation_id = $service_match['variation_id'];
	}

	$secret_key = apparel_service_get_stripe_secret_key();
	if ( ! $secret_key ) {
		return new WP_REST_Response( array( 'message' => 'Stripe secret key is not configured.' ), 500 );
	}

	$success_url = apparel_service_get_checkout_success_url();
	$cancel_url  = apparel_service_get_checkout_cancel_url();

	$metadata = array();
	if ( $service_id ) {
		$metadata['service_id'] = (string) $service_id;
		$metadata['service_name'] = get_the_title( $service_id );
	}
	if ( $variation_id ) {
		$metadata['variation_id'] = $variation_id;
		$variation_name = apparel_service_get_variation_name( $service_id, $variation_id );
		if ( $variation_name ) {
			$metadata['variation_name'] = $variation_name;
		}
	}

	$body = array(
		'mode'                       => 'payment',
		'success_url'                => $success_url,
		'cancel_url'                 => $cancel_url,
		'line_items[0][price]'       => $price_id,
		'line_items[0][quantity]'    => $quantity,
	);

	foreach ( $metadata as $key => $value ) {
		if ( '' !== $value && null !== $value ) {
			$body[ sprintf( 'metadata[%s]', $key ) ] = $value;
		}
	}

	$response = apparel_service_stripe_request(
		$secret_key,
		'https://api.stripe.com/v1/checkout/sessions',
		$body
	);

	if ( ! $response || empty( $response['id'] ) || empty( $response['url'] ) ) {
		return new WP_REST_Response( array( 'message' => 'Unable to create checkout session.' ), 500 );
	}

	return new WP_REST_Response(
		array(
			'id'  => sanitize_text_field( $response['id'] ),
			'url' => esc_url_raw( $response['url'] ),
		),
		200
	);
}

/**
 * Verify Stripe webhook signature.
 *
 * @param string $payload    Payload body.
 * @param string $sig_header Stripe signature header.
 * @param string $secret     Webhook secret.
 * @return bool
 */
function apparel_service_verify_stripe_signature( $payload, $sig_header, $secret ) {
	$timestamp  = 0;
	$signatures = array();
	foreach ( explode( ',', $sig_header ) as $item ) {
		$pair = explode( '=', $item, 2 );
		if ( 2 !== count( $pair ) ) {
			continue;
		}
		$key   = trim( $pair[0] );
		$value = trim( $pair[1] );

		if ( 't' === $key ) {
			$timestamp = (int) $value;
		}
		if ( 'v1' === $key ) {
			$signatures[] = $value;
		}
	}

	if ( ! $timestamp || empty( $signatures ) ) {
		return false;
	}

	$expected = hash_hmac( 'sha256', $timestamp . '.' . $payload, $secret );
	$valid    = false;
	foreach ( $signatures as $signature ) {
		if ( hash_equals( $expected, $signature ) ) {
			$valid = true;
			break;
		}
	}

	if ( ! $valid ) {
		return false;
	}

	$tolerance = 300;
	if ( abs( time() - $timestamp ) > $tolerance ) {
		return false;
	}

	return true;
}

/**
 * Process checkout session.
 *
 * @param array $session Checkout session object.
 */
function apparel_service_process_checkout_session( $session ) {
	if ( empty( $session['id'] ) ) {
		return;
	}

	$payment_status = isset( $session['payment_status'] ) ? $session['payment_status'] : '';
	$status         = in_array( $payment_status, array( 'paid', 'no_payment_required' ), true ) ? 'paid' : 'failed';

	$secret_key = apparel_service_get_stripe_secret_key();

	$line_items   = array();
	$service_match = apparel_service_find_service_by_session( $session, $secret_key, $line_items );
	$service_id    = $service_match['service_id'] ?? 0;
	$variation_id  = $service_match['variation_id'] ?? '';
	$quantity      = $service_match['quantity'] ?? 1;
	$custom_fields = apparel_service_extract_custom_fields( $session, $secret_key );

	$line_item_summary = array();
	if ( ! empty( $line_items ) ) {
		foreach ( $line_items as $line_item ) {
			$price_id     = $line_item['price']['id'] ?? '';
			$product_id   = $line_item['price']['product'] ?? '';
			$description  = $line_item['description'] ?? '';
			$item_quantity = isset( $line_item['quantity'] ) ? absint( $line_item['quantity'] ) : 1;
			$amount_total = isset( $line_item['amount_total'] ) ? ( (float) $line_item['amount_total'] / 100 ) : '';
			$currency     = isset( $line_item['currency'] ) ? strtolower( $line_item['currency'] ) : '';

			$line_item_summary[] = array(
				'price_id'    => sanitize_text_field( $price_id ),
				'product_id'  => sanitize_text_field( $product_id ),
				'description' => sanitize_text_field( $description ),
				'quantity'    => $item_quantity,
				'amount_total' => $amount_total,
				'currency'    => $currency,
			);
		}
	}

	$service_name = '';
	if ( ! $service_id ) {
		$service_name = apparel_service_resolve_stripe_service_name(
			$secret_key,
			sanitize_text_field( $session['id'] ?? '' ),
			$line_items,
			sanitize_text_field( $session['payment_intent'] ?? '' )
		);
	}

	$amount_total = isset( $session['amount_total'] ) ? ( (float) $session['amount_total'] / 100 ) : '';
	$currency     = isset( $session['currency'] ) ? strtolower( $session['currency'] ) : '';

	$customer_details = isset( $session['customer_details'] ) && is_array( $session['customer_details'] ) ? $session['customer_details'] : array();
	$customer_email   = '';
	if ( ! empty( $customer_details['email'] ) ) {
		$customer_email = sanitize_email( $customer_details['email'] );
	} elseif ( ! empty( $session['customer_email'] ) ) {
		$customer_email = sanitize_email( $session['customer_email'] );
	}

	$customer_name    = ! empty( $customer_details['name'] ) ? sanitize_text_field( $customer_details['name'] ) : '';
	$customer_phone   = ! empty( $customer_details['phone'] ) ? sanitize_text_field( $customer_details['phone'] ) : '';
	$customer_address = '';
	if ( ! empty( $customer_details['address'] ) ) {
		$customer_address = apparel_service_format_customer_address( $customer_details['address'] );
	}

	$variation_name = '';
	if ( $service_id && $variation_id ) {
		$variation_name = apparel_service_get_variation_name( $service_id, $variation_id );
	}

	$payment_link_id = isset( $session['payment_link'] ) ? sanitize_text_field( $session['payment_link'] ) : '';
	$checkout_link   = $payment_link_id;
	$checkout_url    = '';
	if ( $payment_link_id && $secret_key ) {
		$checkout_url = apparel_service_get_payment_link_url( $secret_key, $payment_link_id );
	}
	$lead_gen_page_id    = 0;
	$lead_gen_page_title = '';
	if ( $checkout_url ) {
		$lead_gen_page_id = apparel_lead_gen_find_page_by_payment_link_url( $checkout_url );
		if ( $lead_gen_page_id ) {
			$lead_gen_page_title = get_the_title( $lead_gen_page_id );
		}
	}

	$order_data = array(
		'service_id'              => $service_id,
		'service_name'            => $service_name,
		'amount_total'            => $amount_total,
		'currency'                => $currency,
		'customer_email'          => $customer_email,
		'customer_name'           => $customer_name,
		'customer_phone'          => $customer_phone,
		'customer_address'        => $customer_address,
		'stripe_session_id'       => sanitize_text_field( $session['id'] ),
		'stripe_payment_intent_id' => isset( $session['payment_intent'] ) ? sanitize_text_field( $session['payment_intent'] ) : '',
		'status'                  => $status,
		'created_at'              => isset( $session['created'] ) ? (int) $session['created'] : time(),
		'checkout_link'           => $checkout_link,
		'checkout_url'            => $checkout_url,
		'lead_gen_page_id'        => $lead_gen_page_id,
		'lead_gen_page_title'     => $lead_gen_page_title,
		'lead_gen_payment_link'   => $checkout_url,
		'variation_id'            => $variation_id,
		'variation_name'          => $variation_name,
		'quantity'                => $quantity,
		'line_items'              => $line_item_summary,
		'custom_fields'           => $custom_fields,
	);

	apparel_service_upsert_order( $order_data );
}

/**
 * Update an order status by Stripe session or payment intent ID.
 *
 * @param string $status Status value.
 * @param string $session_id Checkout session ID.
 * @param string $payment_intent_id Payment intent ID.
 * @param array  $context Optional context data.
 * @return int
 */
function apparel_service_update_order_status( $status, $session_id = '', $payment_intent_id = '', $context = array() ) {
	if ( ! $status || ( ! $session_id && ! $payment_intent_id ) ) {
		return 0;
	}

	$existing = array();
	if ( $session_id ) {
		$existing = get_posts(
			array(
				'post_type'              => 'service_order',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'meta_key'               => '_stripe_session_id',
				'meta_value'             => $session_id,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
	}

	if ( empty( $existing ) && $payment_intent_id ) {
		$existing = get_posts(
			array(
				'post_type'              => 'service_order',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'meta_key'               => '_stripe_payment_intent_id',
				'meta_value'             => $payment_intent_id,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
	}

	if ( empty( $existing ) ) {
		return 0;
	}

	$order_id = (int) $existing[0];
	update_post_meta( $order_id, '_status', sanitize_text_field( $status ) );

	if ( $payment_intent_id ) {
		update_post_meta( $order_id, '_stripe_payment_intent_id', sanitize_text_field( $payment_intent_id ) );
	}

	if ( isset( $context['amount_refunded'] ) ) {
		update_post_meta( $order_id, '_amount_refunded', (float) $context['amount_refunded'] );
	}

	if ( ! empty( $context['currency'] ) ) {
		update_post_meta( $order_id, '_refund_currency', sanitize_text_field( $context['currency'] ) );
	}

	apparel_service_maybe_send_order_status_email( $order_id, $status );

	return $order_id;
}

/**
 * Get a service order ID by Stripe checkout session ID.
 *
 * @param string $session_id Checkout session ID.
 * @return int
 */
function apparel_service_get_order_id_by_session_id( $session_id ) {
	if ( ! $session_id ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'              => 'service_order',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'meta_key'               => '_stripe_session_id',
			'meta_value'             => $session_id,
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	return ! empty( $existing ) ? (int) $existing[0] : 0;
}

/**
 * Find service by session data.
 *
 * @param array  $session    Checkout session data.
 * @param string $secret_key Stripe secret key.
 * @param array  $line_items Checkout session line items.
 * @return array
 */
function apparel_service_find_service_by_session( $session, $secret_key, &$line_items = array() ) {
	$payment_link_id = isset( $session['payment_link'] ) ? sanitize_text_field( $session['payment_link'] ) : '';
	if ( $payment_link_id ) {
		$service_match = apparel_service_find_service_by_payment_link( $payment_link_id, $secret_key );
		if ( $service_match ) {
			$line_items = array();
			return $service_match;
		}
	}

	if ( $secret_key && ! empty( $session['id'] ) ) {
		$line_items = apparel_service_get_checkout_line_items( $secret_key, $session['id'] );
	}

	if ( ! empty( $line_items ) ) {
		$first_item = $line_items[0];
		$price_id   = $first_item['price']['id'] ?? '';
		$quantity   = isset( $first_item['quantity'] ) ? absint( $first_item['quantity'] ) : 1;
		if ( $price_id ) {
			$match = apparel_service_find_service_by_price_id( $price_id );
			if ( $match ) {
				$match['quantity'] = $quantity;
				return $match;
			}
		}
	}

	return array();
}

/**
 * Extract custom fields from a Stripe checkout session.
 *
 * @param array  $session    Checkout session data.
 * @param string $secret_key Stripe secret key.
 * @return array
 */
function apparel_service_extract_custom_fields( $session, $secret_key ) {
	$custom_fields = array();
	if ( ! empty( $session['custom_fields'] ) && is_array( $session['custom_fields'] ) ) {
		$custom_fields = $session['custom_fields'];
	}

	if ( empty( $custom_fields ) && $secret_key && ! empty( $session['id'] ) ) {
		$response = apparel_service_stripe_get_request(
			$secret_key,
			sprintf( 'https://api.stripe.com/v1/checkout/sessions/%s', rawurlencode( $session['id'] ) ),
			array(
				'expand' => array( 'custom_fields' ),
			)
		);
		if ( ! empty( $response['custom_fields'] ) && is_array( $response['custom_fields'] ) ) {
			$custom_fields = $response['custom_fields'];
		}
	}

	return apparel_service_normalize_custom_fields( $custom_fields );
}

/**
 * Normalize Stripe custom fields for storage.
 *
 * @param array $custom_fields Raw custom fields.
 * @return array
 */
function apparel_service_normalize_custom_fields( $custom_fields ) {
	if ( empty( $custom_fields ) || ! is_array( $custom_fields ) ) {
		return array();
	}

	$normalized = array();
	foreach ( $custom_fields as $field ) {
		if ( ! is_array( $field ) ) {
			continue;
		}

		$label = '';
		if ( ! empty( $field['label']['custom'] ) ) {
			$label = $field['label']['custom'];
		} elseif ( ! empty( $field['label']['type'] ) && ! empty( $field['label']['i18n'] ) ) {
			$label = $field['label']['i18n'];
		} elseif ( ! empty( $field['label'] ) && is_string( $field['label'] ) ) {
			$label = $field['label'];
		}

		$value = '';
		if ( ! empty( $field['text']['value'] ) ) {
			$value = $field['text']['value'];
		} elseif ( isset( $field['numeric']['value'] ) ) {
			$value = (string) $field['numeric']['value'];
		} elseif ( ! empty( $field['dropdown']['value'] ) ) {
			$value = $field['dropdown']['value'];
		} elseif ( isset( $field['value'] ) ) {
			$value = is_string( $field['value'] ) ? $field['value'] : wp_json_encode( $field['value'] );
		}

		$normalized[] = array(
			'key'   => ! empty( $field['key'] ) ? sanitize_text_field( $field['key'] ) : '',
			'label' => sanitize_text_field( $label ),
			'value' => sanitize_textarea_field( $value ),
		);
	}

	return $normalized;
}

/**
 * Format custom fields for display.
 *
 * @param array $custom_fields Stored custom fields.
 * @return array
 */
function apparel_service_format_custom_fields_for_display( $custom_fields ) {
	if ( empty( $custom_fields ) || ! is_array( $custom_fields ) ) {
		return array();
	}

	$display = array();
	foreach ( $custom_fields as $field ) {
		if ( ! is_array( $field ) ) {
			continue;
		}

		$label = $field['label'] ?? '';
		$key   = $field['key'] ?? '';
		$value = $field['value'] ?? '';
		if ( '' === $label ) {
			$label = $key;
		}

		if ( '' === $value ) {
			continue;
		}

		if ( '' !== $label ) {
			$display[] = sprintf( '%s: %s', $label, $value );
		} else {
			$display[] = $value;
		}
	}

	return $display;
}

/**
 * Get custom fields formatted for email rendering.
 *
 * @param int   $order_id Order ID.
 * @param array $order_data Optional order data.
 * @return array
 */
function apparel_service_get_custom_fields_for_email( $order_id, $order_data = array() ) {
	$custom_fields = $order_data['custom_fields'] ?? get_post_meta( $order_id, '_stripe_custom_fields', true );
	if ( empty( $custom_fields ) || ! is_array( $custom_fields ) ) {
		return array();
	}

	$fields = array();
	foreach ( $custom_fields as $field ) {
		if ( ! is_array( $field ) ) {
			continue;
		}

		$label = $field['label'] ?? '';
		$key   = $field['key'] ?? '';
		$value = $field['value'] ?? '';

		$label = '' !== $label ? $label : $key;
		$value = is_string( $value ) ? $value : '';

		if ( '' === trim( $value ) ) {
			continue;
		}

		$fields[] = array(
			'label' => $label,
			'value' => $value,
		);
	}

	return $fields;
}

/**
 * Get a summary string for custom fields.
 *
 * @param array $custom_fields Stored custom fields.
 * @return string
 */
function apparel_service_get_custom_fields_summary( $custom_fields ) {
	$display = apparel_service_format_custom_fields_for_display( $custom_fields );
	if ( empty( $display ) ) {
		return '';
	}

	return implode( ' | ', $display );
}

/**
 * Find service matching a Stripe payment link ID.
 *
 * @param string $payment_link_id Stripe payment link ID.
 * @param string $secret_key      Stripe secret key.
 * @return array
 */
function apparel_service_find_service_by_payment_link( $payment_link_id, $secret_key ) {
	if ( ! $payment_link_id || ! $secret_key ) {
		return array();
	}

	$payment_link_url = apparel_service_get_payment_link_url( $secret_key, $payment_link_id );
	if ( ! $payment_link_url ) {
		return array();
	}

	$service_ids = get_posts(
		array(
			'post_type'              => 'service',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'meta_key'               => '_service_checkout_url',
			'meta_value'             => $payment_link_url,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! empty( $service_ids ) ) {
		return array(
			'service_id'  => (int) $service_ids[0],
			'variation_id' => '',
			'quantity'    => 1,
		);
	}

	$all_services = get_posts(
		array(
			'post_type'              => 'service',
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $all_services as $service_id ) {
		$variations = get_post_meta( $service_id, '_service_variations', true );
		if ( ! is_array( $variations ) ) {
			continue;
		}
		foreach ( $variations as $variation ) {
			if ( ! empty( $variation['stripe_payment_link'] ) && $variation['stripe_payment_link'] === $payment_link_url ) {
				return array(
					'service_id'  => (int) $service_id,
					'variation_id' => isset( $variation['variation_id'] ) ? $variation['variation_id'] : '',
					'quantity'    => 1,
				);
			}
		}
	}

	return array();
}

/**
 * Find Lead Gen page by Stripe payment link URL.
 *
 * @param string $payment_link_url Stripe payment link URL.
 * @return int
 */
function apparel_lead_gen_find_page_by_payment_link_url( $payment_link_url ) {
	if ( ! $payment_link_url ) {
		return 0;
	}

	$payment_link_url = untrailingslashit( $payment_link_url );

	$page_ids = get_posts(
		array(
			'post_type'              => 'page',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'meta_key'               => 'lead_gen_stripe_payment_link',
			'meta_value'             => $payment_link_url,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! empty( $page_ids ) ) {
		return (int) $page_ids[0];
	}

	return 0;
}

/**
 * Find service by Stripe price ID.
 *
 * @param string $price_id Stripe price ID.
 * @return array
 */
function apparel_service_find_service_by_price_id( $price_id ) {
	$services = get_posts(
		array(
			'post_type'              => 'service',
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $services as $service_id ) {
		$variations = get_post_meta( $service_id, '_service_variations', true );
		if ( ! is_array( $variations ) ) {
			continue;
		}
		foreach ( $variations as $variation ) {
			if ( ! empty( $variation['stripe_price_id'] ) && $variation['stripe_price_id'] === $price_id ) {
				return array(
					'service_id'  => (int) $service_id,
					'variation_id' => isset( $variation['variation_id'] ) ? $variation['variation_id'] : '',
					'quantity'    => 1,
				);
			}
		}
	}

	return array();
}

/**
 * Get Stripe payment link URL from ID.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $payment_link_id Payment link ID.
 * @return string
 */
function apparel_service_get_payment_link_url( $secret_key, $payment_link_id ) {
	if ( ! $secret_key || ! $payment_link_id ) {
		return '';
	}

	$response = apparel_service_stripe_get_request(
		$secret_key,
		sprintf( 'https://api.stripe.com/v1/payment_links/%s', rawurlencode( $payment_link_id ) )
	);

	if ( empty( $response['url'] ) ) {
		return '';
	}

	return esc_url_raw( $response['url'] );
}

/**
 * Get checkout line items from Stripe.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $session_id Checkout session ID.
 * @param int    $limit      Maximum number of line items to return.
 * @param bool   $expand_products Whether to expand product data.
 * @return array
 */
function apparel_service_get_checkout_line_items( $secret_key, $session_id, $limit = 1, $expand_products = false ) {
	$limit = $limit ? absint( $limit ) : 1;
	if ( $limit < 1 ) {
		$limit = 1;
	}

	$query = array( 'limit' => $limit );
	if ( $expand_products ) {
		$query['expand'] = array( 'data.price.product' );
	}

	$response = apparel_service_stripe_get_request(
		$secret_key,
		sprintf( 'https://api.stripe.com/v1/checkout/sessions/%s/line_items', rawurlencode( $session_id ) ),
		$query
	);

	if ( empty( $response['data'] ) || ! is_array( $response['data'] ) ) {
		return array();
	}

	return $response['data'];
}

/**
 * Resolve a service name for a Stripe checkout session.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $session_id Checkout session ID.
 * @param array  $line_items Known line items.
 * @param string $payment_intent_id Payment intent ID.
 * @return string
 */
function apparel_service_resolve_stripe_service_name( $secret_key, $session_id, $line_items = array(), $payment_intent_id = '' ) {
	if ( $secret_key && $session_id ) {
		$line_items = apparel_service_get_checkout_line_items( $secret_key, $session_id, 100, true );
	}

	$names = array();
	if ( is_array( $line_items ) ) {
		foreach ( $line_items as $line_item ) {
			if ( ! is_array( $line_item ) ) {
				continue;
			}
			$name = apparel_service_get_line_item_name( $line_item, $secret_key );
			if ( $name ) {
				$names[] = $name;
			}
		}
	}

	if ( ! empty( $names ) ) {
		$names = array_values( array_unique( $names ) );
		return implode( ' + ', $names );
	}

	if ( $secret_key && $payment_intent_id ) {
		$description = apparel_service_get_payment_intent_description( $secret_key, $payment_intent_id );
		if ( $description ) {
			return $description;
		}
	}

	return '';
}

/**
 * Get a name for a Stripe line item.
 *
 * @param array  $line_item Line item data.
 * @param string $secret_key Stripe secret key.
 * @return string
 */
function apparel_service_get_line_item_name( $line_item, $secret_key ) {
	$description = $line_item['description'] ?? '';
	if ( $description ) {
		return sanitize_text_field( $description );
	}

	$price = $line_item['price'] ?? array();
	if ( is_array( $price ) ) {
		$product = $price['product'] ?? '';
		if ( is_array( $product ) && ! empty( $product['name'] ) ) {
			return sanitize_text_field( $product['name'] );
		}

		if ( is_string( $product ) && $product && $secret_key ) {
			$product_name = apparel_service_get_stripe_product_name( $secret_key, $product );
			if ( $product_name ) {
				return $product_name;
			}
		}
	}

	return '';
}

/**
 * Get a Stripe product name by ID.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $product_id Stripe product ID.
 * @return string
 */
function apparel_service_get_stripe_product_name( $secret_key, $product_id ) {
	if ( ! $secret_key || ! $product_id ) {
		return '';
	}

	$response = apparel_service_stripe_get_request(
		$secret_key,
		sprintf( 'https://api.stripe.com/v1/products/%s', rawurlencode( $product_id ) )
	);

	if ( empty( $response['name'] ) ) {
		return '';
	}

	return sanitize_text_field( $response['name'] );
}

/**
 * Get a payment intent description as a fallback.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $payment_intent_id Payment intent ID.
 * @return string
 */
function apparel_service_get_payment_intent_description( $secret_key, $payment_intent_id ) {
	if ( ! $secret_key || ! $payment_intent_id ) {
		return '';
	}

	$response = apparel_service_stripe_get_request(
		$secret_key,
		sprintf( 'https://api.stripe.com/v1/payment_intents/%s', rawurlencode( $payment_intent_id ) ),
		array( 'expand' => array( 'charges.data' ) )
	);

	if ( ! empty( $response['description'] ) ) {
		return sanitize_text_field( $response['description'] );
	}

	$charge_description = $response['charges']['data'][0]['description'] ?? '';
	if ( $charge_description ) {
		return sanitize_text_field( $charge_description );
	}

	return '';
}

/**
 * Get variation name by service and variation ID.
 *
 * @param int    $service_id   Service post ID.
 * @param string $variation_id Variation ID.
 * @return string
 */
function apparel_service_get_variation_name( $service_id, $variation_id ) {
	if ( ! $service_id || ! $variation_id ) {
		return '';
	}

	$variations = get_post_meta( $service_id, '_service_variations', true );
	if ( ! is_array( $variations ) ) {
		return '';
	}

	foreach ( $variations as $variation ) {
		if ( ! empty( $variation['variation_id'] ) && $variation['variation_id'] === $variation_id ) {
			return isset( $variation['name'] ) ? sanitize_text_field( $variation['name'] ) : '';
		}
	}

	return '';
}

/**
 * Format customer address for display.
 *
 * @param array $address Customer address data.
 * @return string
 */
function apparel_service_format_customer_address( $address ) {
	if ( ! is_array( $address ) ) {
		return '';
	}

	$parts = array_filter(
		array(
			$address['line1'] ?? '',
			$address['line2'] ?? '',
			$address['city'] ?? '',
			$address['state'] ?? '',
			$address['postal_code'] ?? '',
			$address['country'] ?? '',
		)
	);

	return sanitize_text_field( implode( ', ', $parts ) );
}

/**
 * Create or update a service order.
 *
 * @param array $order_data Order data.
 * @return int
 */
function apparel_service_upsert_order( $order_data ) {
	$session_id = $order_data['stripe_session_id'] ?? '';
	$payment_intent_id = $order_data['stripe_payment_intent_id'] ?? '';

	if ( ! $session_id && ! $payment_intent_id ) {
		return 0;
	}

	$existing = array();
	if ( $session_id ) {
		$existing = get_posts(
			array(
				'post_type'              => 'service_order',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'meta_key'               => '_stripe_session_id',
				'meta_value'             => $session_id,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
	}

	if ( empty( $existing ) && $payment_intent_id ) {
		$existing = get_posts(
			array(
				'post_type'              => 'service_order',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'meta_key'               => '_stripe_payment_intent_id',
				'meta_value'             => $payment_intent_id,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
	}

	$order_id = ! empty( $existing ) ? (int) $existing[0] : 0;
	$post_data = array(
		'post_type'   => 'service_order',
		'post_status' => 'private',
		'post_title'  => sprintf( __( 'Order %s', 'apparel' ), $session_id ? $session_id : $payment_intent_id ),
	);

	if ( ! $order_id ) {
		if ( ! empty( $order_data['created_at'] ) ) {
			$post_data['post_date_gmt'] = gmdate( 'Y-m-d H:i:s', $order_data['created_at'] );
			$post_data['post_date']     = get_date_from_gmt( $post_data['post_date_gmt'] );
		}
		$order_id = wp_insert_post( $post_data );
	} else {
		$post_data['ID'] = $order_id;
		wp_update_post( $post_data );
	}

	if ( is_wp_error( $order_id ) || ! $order_id ) {
		return 0;
	}

	update_post_meta( $order_id, '_service_id', absint( $order_data['service_id'] ?? 0 ) );
	update_post_meta( $order_id, '_service_name', sanitize_text_field( $order_data['service_name'] ?? '' ) );
	update_post_meta( $order_id, '_amount_total', $order_data['amount_total'] );
	update_post_meta( $order_id, '_currency', sanitize_text_field( $order_data['currency'] ?? '' ) );
	update_post_meta( $order_id, '_customer_email', sanitize_email( $order_data['customer_email'] ?? '' ) );
	update_post_meta( $order_id, '_customer_name', sanitize_text_field( $order_data['customer_name'] ?? '' ) );
	update_post_meta( $order_id, '_customer_phone', sanitize_text_field( $order_data['customer_phone'] ?? '' ) );
	update_post_meta( $order_id, '_customer_address', sanitize_text_field( $order_data['customer_address'] ?? '' ) );
	update_post_meta( $order_id, '_stripe_session_id', sanitize_text_field( $order_data['stripe_session_id'] ) );
	update_post_meta( $order_id, '_stripe_payment_intent_id', sanitize_text_field( $order_data['stripe_payment_intent_id'] ?? '' ) );
	update_post_meta( $order_id, '_status', sanitize_text_field( $order_data['status'] ?? '' ) );
	update_post_meta( $order_id, '_created_at', absint( $order_data['created_at'] ?? time() ) );
	update_post_meta( $order_id, '_checkout_link', sanitize_text_field( $order_data['checkout_link'] ?? '' ) );
	update_post_meta( $order_id, '_checkout_url', esc_url_raw( $order_data['checkout_url'] ?? '' ) );
	update_post_meta( $order_id, '_lead_gen_page_id', absint( $order_data['lead_gen_page_id'] ?? 0 ) );
	update_post_meta( $order_id, '_lead_gen_page_title', sanitize_text_field( $order_data['lead_gen_page_title'] ?? '' ) );
	update_post_meta( $order_id, '_lead_gen_payment_link', esc_url_raw( $order_data['lead_gen_payment_link'] ?? '' ) );
	update_post_meta( $order_id, '_variation_id', sanitize_text_field( $order_data['variation_id'] ?? '' ) );
	update_post_meta( $order_id, '_variation_name', sanitize_text_field( $order_data['variation_name'] ?? '' ) );
	update_post_meta( $order_id, '_quantity', absint( $order_data['quantity'] ?? 1 ) );
	update_post_meta( $order_id, '_stripe_line_items', $order_data['line_items'] ?? array() );
	update_post_meta( $order_id, '_stripe_custom_fields', $order_data['custom_fields'] ?? array() );

	$service_items = apparel_service_get_order_service_items( $order_id, $order_data );

	apparel_service_maybe_send_order_confirmation( $order_id, $order_data, $service_items );
	apparel_service_maybe_mark_order_completed( $order_id, $order_data, $service_items );
	apparel_service_maybe_send_order_status_email( $order_id, $order_data['status'] ?? '', $order_data, $service_items );

	return $order_id;
}

/**
 * Maybe send a service order confirmation email.
 *
 * @param int   $order_id Order ID.
 * @param array $order_data Optional order data from checkout session.
 * @param array $service_items Optional service items for the order.
 * @return bool
 */
function apparel_service_maybe_send_order_confirmation( $order_id, $order_data = array(), $service_items = array() ) {
	if ( ! $order_id ) {
		return false;
	}

	$status = $order_data['status'] ?? get_post_meta( $order_id, '_status', true );
	if ( 'paid' !== $status ) {
		return false;
	}

	$already_sent = get_post_meta( $order_id, '_confirmation_sent', true );
	if ( $already_sent ) {
		return false;
	}

	$customer_email = $order_data['customer_email'] ?? get_post_meta( $order_id, '_customer_email', true );
	if ( ! $customer_email || ! is_email( $customer_email ) ) {
		return false;
	}

	if ( empty( $service_items ) ) {
		$service_items = apparel_service_get_order_service_items( $order_id, $order_data );
		if ( empty( $service_items ) ) {
			return false;
		}
	}

	$subject = sprintf(
		/* translators: %s: site name */
		__( 'Your service order is confirmed - %s', 'apparel' ),
		wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES )
	);

	$message_html = apparel_service_build_order_confirmation_email( $order_id, $order_data, $service_items );
	$message_text = apparel_service_build_order_confirmation_email_text( $order_id, $order_data, $service_items );

	$sent = apparel_service_send_order_email( $customer_email, $subject, $message_html, $message_text );
	if ( $sent ) {
		update_post_meta( $order_id, '_confirmation_sent', current_time( 'mysql' ) );
	}

	return (bool) $sent;
}

/**
 * Build service order confirmation email HTML.
 *
 * @param int   $order_id Order ID.
 * @param array $order_data Order data.
 * @param array $service_items Service items for the order.
 * @return string
 */
function apparel_service_build_order_confirmation_email( $order_id, $order_data, $service_items ) {
	$context = apparel_service_get_order_email_context( $order_id, $order_data, $service_items );

	$args = array(
		'heading'             => __( 'Your service order is confirmed', 'apparel' ),
		'intro'               => __( 'Thank you for your purchase. We are preparing your service and will be in touch with any next steps.', 'apparel' ),
		'order_id'            => $order_id,
		'order_date_display'  => $context['order_date_display'],
		'order_total_display' => $context['order_total_display'],
		'service_items'       => $service_items,
		'custom_fields'       => $context['custom_fields'],
		'download_items'      => $context['download_items'],
		'customer_name'       => $context['customer_name'],
		'admin_email'         => $context['admin_email'],
		'site_name'           => $context['site_name'],
		'service_links'       => $context['service_links'],
		'action_url'          => $context['primary_service_url'],
		'action_label'        => $context['primary_service_url'] ? __( 'View Service', 'apparel' ) : '',
		'show_downloads'      => true,
	);

	return apparel_service_render_service_order_email_html( $args );
}

/**
 * Build service order confirmation plain-text email.
 *
 * @param int   $order_id Order ID.
 * @param array $order_data Order data.
 * @param array $service_items Service items for the order.
 * @return string
 */
function apparel_service_build_order_confirmation_email_text( $order_id, $order_data, $service_items ) {
	$context = apparel_service_get_order_email_context( $order_id, $order_data, $service_items );

	$args = array(
		'heading'             => __( 'Your service order is confirmed', 'apparel' ),
		'intro'               => __( 'Thank you for your purchase. We are preparing your service and will be in touch with any next steps.', 'apparel' ),
		'order_id'            => $order_id,
		'order_date_display'  => $context['order_date_display'],
		'order_total_display' => $context['order_total_display'],
		'service_items'       => $service_items,
		'custom_fields'       => $context['custom_fields'],
		'download_items'      => $context['download_items'],
		'customer_name'       => $context['customer_name'],
		'admin_email'         => $context['admin_email'],
		'site_name'           => $context['site_name'],
		'service_links'       => $context['service_links'],
		'action_url'          => $context['primary_service_url'],
		'action_label'        => $context['primary_service_url'] ? __( 'View Service', 'apparel' ) : '',
		'show_downloads'      => true,
	);

	return apparel_service_render_service_order_email_text( $args );
}

/**
 * Maybe mark an order as completed if downloads are available.
 *
 * @param int   $order_id Order ID.
 * @param array $order_data Optional order data.
 * @param array $service_items Service items.
 * @return bool
 */
function apparel_service_maybe_mark_order_completed( $order_id, $order_data = array(), $service_items = array() ) {
	if ( ! $order_id ) {
		return false;
	}

	$current_status = get_post_meta( $order_id, '_status', true );
	if ( 'completed' === $current_status ) {
		return false;
	}

	$status = $order_data['status'] ?? $current_status;
	if ( 'paid' !== $status ) {
		return false;
	}

	if ( empty( $service_items ) ) {
		$service_items = apparel_service_get_order_service_items( $order_id, $order_data );
	}

	if ( empty( apparel_service_get_order_download_items( $service_items ) ) ) {
		return false;
	}

	update_post_meta( $order_id, '_status', 'completed' );
	apparel_service_maybe_send_order_status_email( $order_id, 'completed', $order_data, $service_items );

	return true;
}

/**
 * Build status-specific order email content.
 *
 * @param int    $order_id Order ID.
 * @param array  $order_data Order data.
 * @param array  $service_items Service items.
 * @param string $status Status.
 * @return array
 */
function apparel_service_build_order_status_email( $order_id, $order_data, $service_items, $status ) {
	$context = apparel_service_get_order_email_context( $order_id, $order_data, $service_items );
	$site_name = $context['site_name'];

	$heading       = '';
	$intro         = '';
	$status_note   = '';
	$show_download = false;

	$refund_amount_display = '';
	if ( $context['refund_amount'] ) {
		$refund_currency = $context['refund_currency'] ? $context['refund_currency'] : ( $order_data['currency'] ?? get_post_meta( $order_id, '_currency', true ) );
		$refund_amount_display = apparel_service_format_currency_amount( $context['refund_amount'], $refund_currency );
	}

	switch ( $status ) {
		case 'completed':
			$heading       = __( 'Your service order is complete', 'apparel' );
			$intro         = __( 'We have completed your service. Thank you for choosing us.', 'apparel' );
			$status_note   = __( 'If your service includes a download, you can access it below.', 'apparel' );
			$show_download = true;
			break;
		case 'cancelled':
			$heading     = __( 'Your service order was cancelled', 'apparel' );
			$intro       = __( 'Your order has been cancelled as requested.', 'apparel' );
			$status_note = __( 'If this was a mistake or you still need this service, please place a new order or contact support.', 'apparel' );
			break;
		case 'refunded':
			$heading = __( 'Your service order was refunded', 'apparel' );
			$intro   = __( 'We have processed your refund.', 'apparel' );
			if ( $refund_amount_display ) {
				$status_note = sprintf(
					/* translators: %s: refund amount */
					__( 'Refund amount: %s. It may take a few business days to appear on your statement.', 'apparel' ),
					$refund_amount_display
				);
			} else {
				$status_note = __( 'Your refund may take a few business days to appear on your statement.', 'apparel' );
			}
			break;
		case 'partially_refunded':
			$heading = __( 'Your service order was partially refunded', 'apparel' );
			$intro   = __( 'We have processed a partial refund for your order.', 'apparel' );
			if ( $refund_amount_display ) {
				$status_note = sprintf(
					/* translators: %s: refund amount */
					__( 'Partial refund amount: %s. It may take a few business days to appear on your statement.', 'apparel' ),
					$refund_amount_display
				);
			} else {
				$status_note = __( 'Your partial refund may take a few business days to appear on your statement.', 'apparel' );
			}
			break;
		case 'failed':
			$heading     = __( 'Payment failed for your service order', 'apparel' );
			$intro       = __( 'Unfortunately we could not process your payment.', 'apparel' );
			$status_note = __( 'Please try again or contact support if you need help.', 'apparel' );
			break;
		default:
			return array();
	}

	$subject = sprintf(
		/* translators: %s: site name */
		__( '%1$s - %2$s', 'apparel' ),
		$heading,
		$site_name
	);

	$args = array(
		'heading'             => $heading,
		'intro'               => $intro,
		'status_note'         => $status_note,
		'order_id'            => $order_id,
		'order_date_display'  => $context['order_date_display'],
		'order_total_display' => $context['order_total_display'],
		'service_items'       => $service_items,
		'custom_fields'       => $context['custom_fields'],
		'download_items'      => $context['download_items'],
		'customer_name'       => $context['customer_name'],
		'admin_email'         => $context['admin_email'],
		'site_name'           => $site_name,
		'service_links'       => $context['service_links'],
		'action_url'          => $context['primary_service_url'],
		'action_label'        => $context['primary_service_url'] ? __( 'View Service', 'apparel' ) : '',
		'show_downloads'      => $show_download,
	);

	return array(
		'subject' => $subject,
		'html'    => apparel_service_render_service_order_email_html( $args ),
		'text'    => apparel_service_render_service_order_email_text( $args ),
	);
}

/**
 * Maybe send a status update email.
 *
 * @param int    $order_id Order ID.
 * @param string $status Status.
 * @param array  $order_data Optional order data.
 * @param array  $service_items Optional service items.
 * @return bool
 */
function apparel_service_maybe_send_order_status_email( $order_id, $status, $order_data = array(), $service_items = array() ) {
	if ( ! $order_id || ! $status ) {
		return false;
	}

	$allowed_statuses = array( 'completed', 'cancelled', 'refunded', 'partially_refunded', 'failed' );
	if ( ! in_array( $status, $allowed_statuses, true ) ) {
		return false;
	}

	$sent_meta_key = '_status_email_sent_' . sanitize_key( $status );
	if ( get_post_meta( $order_id, $sent_meta_key, true ) ) {
		return false;
	}

	$customer_email = $order_data['customer_email'] ?? get_post_meta( $order_id, '_customer_email', true );
	if ( ! $customer_email || ! is_email( $customer_email ) ) {
		return false;
	}

	if ( empty( $service_items ) ) {
		$service_items = apparel_service_get_order_service_items( $order_id, $order_data );
	}

	if ( empty( $service_items ) ) {
		return false;
	}

	$email = apparel_service_build_order_status_email( $order_id, $order_data, $service_items, $status );
	if ( empty( $email ) || empty( $email['subject'] ) ) {
		return false;
	}

	$sent = apparel_service_send_order_email( $customer_email, $email['subject'], $email['html'], $email['text'] );
	if ( $sent ) {
		update_post_meta( $order_id, $sent_meta_key, current_time( 'mysql' ) );
	}

	return (bool) $sent;
}

/**
 * Get service items associated with an order.
 *
 * @param int   $order_id Order ID.
 * @param array $order_data Order data.
 * @return array
 */
function apparel_service_get_order_service_items( $order_id, $order_data = array() ) {
	$items = array();

	$line_items = $order_data['line_items'] ?? get_post_meta( $order_id, '_stripe_line_items', true );
	if ( is_array( $line_items ) ) {
		foreach ( $line_items as $line_item ) {
			if ( ! is_array( $line_item ) ) {
				continue;
			}
			$price_id = $line_item['price_id'] ?? '';
			if ( ! $price_id ) {
				$price_id = $line_item['price']['id'] ?? '';
			}
			if ( ! $price_id ) {
				continue;
			}
			$service_match = apparel_service_find_service_by_price_id( $price_id );
			if ( empty( $service_match['service_id'] ) ) {
				continue;
			}

			$service_id     = (int) $service_match['service_id'];
			$variation_id   = $service_match['variation_id'] ?? '';
			$variation_name = $variation_id ? apparel_service_get_variation_name( $service_id, $variation_id ) : '';
			$download_link  = apparel_service_get_service_download_link( $service_id, $variation_id );
			$quantity       = isset( $line_item['quantity'] ) ? absint( $line_item['quantity'] ) : 1;
			$amount_total   = $line_item['amount_total'] ?? '';
			$currency       = $line_item['currency'] ?? ( $order_data['currency'] ?? get_post_meta( $order_id, '_currency', true ) );
			$amount_display = apparel_service_format_currency_amount( $amount_total, $currency );

			$items[] = array(
				'service_id'     => $service_id,
				'title'          => get_the_title( $service_id ),
				'variation_name' => $variation_name,
				'download_link'  => $download_link,
				'quantity'       => $quantity,
				'amount_display' => $amount_display,
			);
		}
	}

	if ( empty( $items ) ) {
		$service_id    = $order_data['service_id'] ?? get_post_meta( $order_id, '_service_id', true );
		$variation_name = $order_data['variation_name'] ?? get_post_meta( $order_id, '_variation_name', true );
		$variation_id   = $order_data['variation_id'] ?? get_post_meta( $order_id, '_variation_id', true );
		$download_link  = apparel_service_get_service_download_link( $service_id, $variation_id );
		$quantity       = $order_data['quantity'] ?? get_post_meta( $order_id, '_quantity', true );
		if ( $service_id ) {
			$items[] = array(
				'service_id'     => (int) $service_id,
				'title'          => get_the_title( $service_id ),
				'variation_name' => $variation_name,
				'download_link'  => $download_link,
				'quantity'       => $quantity ? absint( $quantity ) : 1,
				'amount_display' => apparel_service_format_currency_amount(
					$order_data['amount_total'] ?? get_post_meta( $order_id, '_amount_total', true ),
					$order_data['currency'] ?? get_post_meta( $order_id, '_currency', true )
				),
			);
		}
	}

	return $items;
}

/**
 * Format a currency amount for display.
 *
 * @param float|string $amount Amount.
 * @param string       $currency Currency code.
 * @return string
 */
function apparel_service_format_currency_amount( $amount, $currency ) {
	if ( '' === $amount || null === $amount ) {
		return '';
	}

	return sprintf(
		'%s %s',
		number_format_i18n( (float) $amount, 2 ),
		strtoupper( (string) $currency )
	);
}

/**
 * Get a download link for a service or variation.
 *
 * @param int    $service_id Service ID.
 * @param string $variation_id Variation ID.
 * @return string
 */
function apparel_service_get_service_download_link( $service_id, $variation_id = '' ) {
	if ( ! $service_id ) {
		return '';
	}

	if ( $variation_id ) {
		$variations = get_post_meta( $service_id, '_service_variations', true );
		if ( is_array( $variations ) ) {
			foreach ( $variations as $variation ) {
				if ( empty( $variation['variation_id'] ) || $variation['variation_id'] !== $variation_id ) {
					continue;
				}
				if ( ! empty( $variation['download_link'] ) ) {
					return $variation['download_link'];
				}
			}
		}
	}

	$download_link = get_post_meta( $service_id, '_service_download_link', true );
	return $download_link ? $download_link : '';
}

/**
 * Extract downloadable items from service items.
 *
 * @param array $service_items Service items.
 * @return array
 */
function apparel_service_get_order_download_items( $service_items ) {
	$downloads = array();
	foreach ( $service_items as $item ) {
		if ( empty( $item['download_link'] ) ) {
			continue;
		}

		$label = $item['title'];
		if ( ! empty( $item['variation_name'] ) ) {
			$label .= ' - ' . $item['variation_name'];
		}

		$downloads[] = array(
			'label' => $label,
			'url'   => $item['download_link'],
		);
	}

	return $downloads;
}

/**
 * Build order email context data.
 *
 * @param int   $order_id Order ID.
 * @param array $order_data Order data.
 * @param array $service_items Service items.
 * @return array
 */
function apparel_service_get_order_email_context( $order_id, $order_data, $service_items ) {
	$customer_name = $order_data['customer_name'] ?? get_post_meta( $order_id, '_customer_name', true );
	$order_total   = $order_data['amount_total'] ?? get_post_meta( $order_id, '_amount_total', true );
	$currency      = $order_data['currency'] ?? get_post_meta( $order_id, '_currency', true );
	$created_at    = $order_data['created_at'] ?? get_post_meta( $order_id, '_created_at', true );
	$created_at    = $created_at ? (int) $created_at : time();
	$service_links = array();

	foreach ( $service_items as $item ) {
		$service_id = isset( $item['service_id'] ) ? (int) $item['service_id'] : 0;
		if ( ! $service_id ) {
			continue;
		}

		$permalink = get_permalink( $service_id );
		if ( ! $permalink ) {
			continue;
		}

		$service_links[ $service_id ] = array(
			'title' => $item['title'] ?? get_the_title( $service_id ),
			'url'   => $permalink,
		);
	}

	$service_links       = array_values( $service_links );
	$primary_service_url = $service_links[0]['url'] ?? '';

	$context = array(
		'customer_name'        => $customer_name,
		'order_total_display'  => apparel_service_format_currency_amount( $order_total, $currency ),
		'order_date_display'   => date_i18n( get_option( 'date_format' ), $created_at ),
		'admin_email'          => get_option( 'admin_email' ),
		'site_name'            => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		'checkout_url'         => $order_data['checkout_url'] ?? get_post_meta( $order_id, '_checkout_url', true ),
		'custom_fields'        => apparel_service_get_custom_fields_for_email( $order_id, $order_data ),
		'download_items'       => apparel_service_get_order_download_items( $service_items ),
		'refund_amount'        => get_post_meta( $order_id, '_amount_refunded', true ),
		'refund_currency'      => get_post_meta( $order_id, '_refund_currency', true ),
		'service_links'        => $service_links,
		'primary_service_url'  => $primary_service_url,
	);

	return $context;
}

/**
 * Render HTML service order email.
 *
 * @param array $args Email arguments.
 * @return string
 */
function apparel_service_render_service_order_email_html( $args ) {
	$heading             = $args['heading'] ?? '';
	$intro               = $args['intro'] ?? '';
	$status_note         = $args['status_note'] ?? '';
	$order_id            = $args['order_id'] ?? 0;
	$order_date_display  = $args['order_date_display'] ?? '';
	$order_total_display = $args['order_total_display'] ?? '';
	$service_items       = $args['service_items'] ?? array();
	$custom_fields       = $args['custom_fields'] ?? array();
	$download_items      = $args['download_items'] ?? array();
	$service_links       = $args['service_links'] ?? array();
	$customer_name       = $args['customer_name'] ?? '';
	$admin_email         = $args['admin_email'] ?? '';
	$site_name           = $args['site_name'] ?? '';
	$action_url          = $args['action_url'] ?? '';
	$action_label        = $args['action_label'] ?? '';
	$show_downloads      = ! empty( $args['show_downloads'] );

	ob_start();
	?>
	<!doctype html>
	<html>
		<head>
			<meta charset="utf-8">
			<title><?php echo esc_html( $site_name ); ?></title>
		</head>
		<body style="margin:0;padding:0;background-color:#f4f5f7;font-family:Arial, sans-serif;color:#1a1a1a;">
			<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7;padding:32px 0;">
				<tr>
					<td align="center">
						<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;">
							<tr>
								<td style="padding:32px 32px 16px;">
									<h1 style="margin:0 0 12px;font-size:24px;line-height:1.4;color:#111;"><?php echo esc_html( $heading ); ?></h1>
									<?php if ( $intro ) : ?>
										<p style="margin:0 0 16px;font-size:14px;color:#4a4a4a;"><?php echo esc_html( $intro ); ?></p>
									<?php endif; ?>
									<p style="margin:0;font-size:14px;color:#4a4a4a;">
										<?php
										if ( $customer_name ) {
											printf(
												/* translators: %s: customer name */
												esc_html__( 'Hello %s,', 'apparel' ),
												esc_html( $customer_name )
											);
										} else {
											esc_html_e( 'Hello,', 'apparel' );
										}
										?>
									</p>
								</td>
							</tr>
							<tr>
								<td style="padding:0 32px 24px;">
									<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
										<tr>
											<td style="padding:12px 0;border-bottom:1px solid #e8e8e8;font-size:14px;color:#4a4a4a;">
												<strong><?php esc_html_e( 'Order Number', 'apparel' ); ?></strong>
											</td>
											<td align="right" style="padding:12px 0;border-bottom:1px solid #e8e8e8;font-size:14px;color:#4a4a4a;">
												<?php echo esc_html( '#' . $order_id ); ?>
											</td>
										</tr>
										<tr>
											<td style="padding:12px 0;border-bottom:1px solid #e8e8e8;font-size:14px;color:#4a4a4a;">
												<strong><?php esc_html_e( 'Order Date', 'apparel' ); ?></strong>
											</td>
											<td align="right" style="padding:12px 0;border-bottom:1px solid #e8e8e8;font-size:14px;color:#4a4a4a;">
												<?php echo esc_html( $order_date_display ); ?>
											</td>
										</tr>
										<?php if ( $order_total_display ) : ?>
											<tr>
												<td style="padding:12px 0;font-size:14px;color:#4a4a4a;">
													<strong><?php esc_html_e( 'Order Total', 'apparel' ); ?></strong>
												</td>
												<td align="right" style="padding:12px 0;font-size:14px;color:#4a4a4a;">
													<?php echo esc_html( $order_total_display ); ?>
												</td>
											</tr>
										<?php endif; ?>
									</table>
								</td>
							</tr>
							<tr>
								<td style="padding:0 32px 24px;">
									<h2 style="margin:0 0 12px;font-size:18px;color:#111;"><?php esc_html_e( 'Service Details', 'apparel' ); ?></h2>
									<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
										<tr>
											<th align="left" style="padding:10px 0;border-bottom:1px solid #e8e8e8;font-size:13px;text-transform:uppercase;letter-spacing:.04em;color:#6a6a6a;">
												<?php esc_html_e( 'Service', 'apparel' ); ?>
											</th>
											<th align="center" style="padding:10px 0;border-bottom:1px solid #e8e8e8;font-size:13px;text-transform:uppercase;letter-spacing:.04em;color:#6a6a6a;">
												<?php esc_html_e( 'Qty', 'apparel' ); ?>
											</th>
											<th align="right" style="padding:10px 0;border-bottom:1px solid #e8e8e8;font-size:13px;text-transform:uppercase;letter-spacing:.04em;color:#6a6a6a;">
												<?php esc_html_e( 'Amount', 'apparel' ); ?>
											</th>
										</tr>
										<?php foreach ( $service_items as $item ) : ?>
											<tr>
												<td style="padding:12px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:#1a1a1a;">
													<?php echo esc_html( $item['title'] ); ?>
													<?php if ( ! empty( $item['variation_name'] ) ) : ?>
														<br><span style="font-size:12px;color:#6a6a6a;"><?php echo esc_html( $item['variation_name'] ); ?></span>
													<?php endif; ?>
												</td>
												<td align="center" style="padding:12px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:#1a1a1a;">
													<?php echo esc_html( (string) $item['quantity'] ); ?>
												</td>
												<td align="right" style="padding:12px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:#1a1a1a;">
													<?php echo esc_html( $item['amount_display'] ); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</table>
								</td>
							</tr>
							<?php if ( ! empty( $custom_fields ) ) : ?>
								<tr>
									<td style="padding:0 32px 24px;">
										<h2 style="margin:0 0 12px;font-size:18px;color:#111;"><?php esc_html_e( 'You have provided the following:', 'apparel' ); ?></h2>
										<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
											<?php foreach ( $custom_fields as $field ) : ?>
												<tr>
													<td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:#1a1a1a;vertical-align:top;width:40%;">
														<strong><?php echo esc_html( $field['label'] ); ?></strong>
													</td>
													<td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:#4a4a4a;">
														<?php echo wp_kses_post( nl2br( esc_html( $field['value'] ) ) ); ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</table>
									</td>
								</tr>
							<?php endif; ?>
							<?php if ( $show_downloads && ! empty( $download_items ) ) : ?>
								<tr>
									<td style="padding:0 32px 24px;">
										<h2 style="margin:0 0 12px;font-size:18px;color:#111;"><?php esc_html_e( 'Downloads', 'apparel' ); ?></h2>
										<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
											<?php foreach ( $download_items as $download_item ) : ?>
												<tr>
													<td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:#1a1a1a;">
														<?php echo esc_html( $download_item['label'] ); ?>
													</td>
													<td align="right" style="padding:8px 0;border-bottom:1px solid #f0f0f0;">
														<a href="<?php echo esc_url( $download_item['url'] ); ?>" style="background-color:#111;color:#ffffff;text-decoration:none;padding:8px 16px;border-radius:4px;font-size:14px;display:inline-block;"><?php esc_html_e( 'Download', 'apparel' ); ?></a>
													</td>
												</tr>
											<?php endforeach; ?>
										</table>
									</td>
								</tr>
							<?php endif; ?>
							<?php if ( $action_url && $action_label ) : ?>
								<tr>
									<td style="padding:0 32px 24px;text-align:center;">
										<a href="<?php echo esc_url( $action_url ); ?>" style="background-color:#111;color:#ffffff;text-decoration:none;padding:12px 24px;border-radius:4px;font-size:14px;display:inline-block;"><?php echo esc_html( $action_label ); ?></a>
									</td>
								</tr>
							<?php endif; ?>
							<?php if ( count( $service_links ) > 1 ) : ?>
								<tr>
									<td style="padding:0 32px 24px;">
										<h2 style="margin:0 0 12px;font-size:18px;color:#111;"><?php esc_html_e( 'Other Services', 'apparel' ); ?></h2>
										<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
											<?php foreach ( $service_links as $index => $service_link ) : ?>
												<?php if ( 0 === $index ) : ?>
													<?php continue; ?>
												<?php endif; ?>
												<tr>
													<td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:#1a1a1a;">
														<a href="<?php echo esc_url( $service_link['url'] ); ?>" style="color:#111;text-decoration:underline;"><?php echo esc_html( $service_link['title'] ); ?></a>
													</td>
												</tr>
											<?php endforeach; ?>
										</table>
									</td>
								</tr>
							<?php endif; ?>
							<?php if ( $status_note ) : ?>
								<tr>
									<td style="padding:0 32px 24px;">
										<p style="margin:0;font-size:14px;color:#4a4a4a;"><?php echo esc_html( $status_note ); ?></p>
									</td>
								</tr>
							<?php endif; ?>
							<tr>
								<td style="padding:0 32px 32px;">
									<p style="margin:0 0 8px;font-size:14px;color:#4a4a4a;"><?php esc_html_e( 'Need help or want to adjust your service? Reply to this email and we will take care of you.', 'apparel' ); ?></p>
									<?php if ( $admin_email ) : ?>
										<p style="margin:0;font-size:14px;color:#4a4a4a;">
											<?php
											printf(
												/* translators: %s: support email */
												esc_html__( 'Support: %s', 'apparel' ),
												esc_html( $admin_email )
											);
											?>
										</p>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td style="padding:16px 32px;background-color:#f7f7f7;text-align:center;font-size:12px;color:#7a7a7a;">
									<?php
									printf(
										/* translators: %s: site name */
										esc_html__( 'Thank you for choosing %s.', 'apparel' ),
										esc_html( $site_name )
									);
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</body>
	</html>
	<?php
	return (string) ob_get_clean();
}

/**
 * Render plain-text service order email.
 *
 * @param array $args Email arguments.
 * @return string
 */
function apparel_service_render_service_order_email_text( $args ) {
	$heading             = $args['heading'] ?? '';
	$intro               = $args['intro'] ?? '';
	$status_note         = $args['status_note'] ?? '';
	$order_id            = $args['order_id'] ?? 0;
	$order_date_display  = $args['order_date_display'] ?? '';
	$order_total_display = $args['order_total_display'] ?? '';
	$service_items       = $args['service_items'] ?? array();
	$custom_fields       = $args['custom_fields'] ?? array();
	$download_items      = $args['download_items'] ?? array();
	$service_links       = $args['service_links'] ?? array();
	$customer_name       = $args['customer_name'] ?? '';
	$admin_email         = $args['admin_email'] ?? '';
	$site_name           = $args['site_name'] ?? '';
	$action_url          = $args['action_url'] ?? '';
	$action_label        = $args['action_label'] ?? '';
	$show_downloads      = ! empty( $args['show_downloads'] );

	$lines   = array();
	$lines[] = $heading;
	if ( $intro ) {
		$lines[] = $intro;
	}
	$lines[] = $customer_name ? sprintf( __( 'Hello %s,', 'apparel' ), $customer_name ) : __( 'Hello,', 'apparel' );
	$lines[] = '';
	$lines[] = sprintf( __( 'Order Number: #%s', 'apparel' ), $order_id );
	$lines[] = sprintf( __( 'Order Date: %s', 'apparel' ), $order_date_display );
	if ( $order_total_display ) {
		$lines[] = sprintf( __( 'Order Total: %s', 'apparel' ), $order_total_display );
	}
	$lines[] = '';
	$lines[] = __( 'Services:', 'apparel' );
	foreach ( $service_items as $item ) {
		$lines[] = sprintf( '- %s (x%s)', $item['title'], $item['quantity'] );
		if ( ! empty( $item['variation_name'] ) ) {
			$lines[] = sprintf( '  %s', $item['variation_name'] );
		}
		if ( $item['amount_display'] ) {
			$lines[] = sprintf( '  %s', $item['amount_display'] );
		}
	}
	if ( ! empty( $custom_fields ) ) {
		$lines[] = '';
		$lines[] = __( 'You have provided the following:', 'apparel' );
		foreach ( $custom_fields as $field ) {
			$value_lines = preg_split( '/\r\n|\r|\n/', (string) $field['value'] );
			$first_line  = array_shift( $value_lines );
			$lines[]     = sprintf( '- %s: %s', $field['label'], $first_line );
			foreach ( $value_lines as $line ) {
				$lines[] = '  ' . $line;
			}
		}
	}
	if ( $show_downloads && ! empty( $download_items ) ) {
		$lines[] = '';
		$lines[] = __( 'Downloads:', 'apparel' );
		foreach ( $download_items as $download_item ) {
			$lines[] = sprintf( '- %s: %s', $download_item['label'], $download_item['url'] );
		}
	}
	if ( count( $service_links ) > 1 ) {
		$lines[] = '';
		$lines[] = __( 'Other Services:', 'apparel' );
		foreach ( $service_links as $index => $service_link ) {
			if ( 0 === $index ) {
				continue;
			}
			$lines[] = sprintf( '- %s: %s', $service_link['title'], $service_link['url'] );
		}
	}
	if ( $action_url && $action_label ) {
		$lines[] = '';
		$lines[] = sprintf( '%s: %s', $action_label, $action_url );
	}
	if ( $status_note ) {
		$lines[] = '';
		$lines[] = $status_note;
	}
	$lines[] = '';
	$lines[] = __( 'Need help or want to adjust your service? Reply to this email and we will take care of you.', 'apparel' );
	if ( $admin_email ) {
		$lines[] = sprintf( __( 'Support: %s', 'apparel' ), $admin_email );
	}
	$lines[] = '';
	if ( $site_name ) {
		$lines[] = sprintf( __( 'Thank you for choosing %s.', 'apparel' ), $site_name );
	}

	return implode( "\n", $lines );
}

/**
 * Send a multipart email with plain-text fallback.
 *
 * @param string $to Recipient email.
 * @param string $subject Subject.
 * @param string $html HTML message.
 * @param string $text Plain text message.
 * @return bool
 */
function apparel_service_send_order_email( $to, $subject, $html, $text ) {
	$content_type_filter = static function() {
		return 'text/html; charset=UTF-8';
	};
	$phpmailer_init = static function( $phpmailer ) use ( $text ) {
		$phpmailer->AltBody = $text;
	};

	add_filter( 'wp_mail_content_type', $content_type_filter );
	add_action( 'phpmailer_init', $phpmailer_init );

	$sent = wp_mail( $to, $subject, $html );

	remove_action( 'phpmailer_init', $phpmailer_init );
	remove_filter( 'wp_mail_content_type', $content_type_filter );

	return $sent;
}
