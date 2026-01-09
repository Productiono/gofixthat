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
			return esc_html__( 'Unknown', 'apparel' );
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
				'service'        => '',
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
	return array( 'paid', 'failed', 'refunded' );
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

	$service_title = $service_id ? get_the_title( $service_id ) : __( 'Unknown', 'apparel' );
	$service_link  = $service_id ? get_edit_post_link( $service_id, 'display' ) : '';

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
		update_post_meta( $post_id, '_status', $status );
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
		apparel_service_update_order_status(
			'refunded',
			'',
			sanitize_text_field( $charge['payment_intent'] ?? '' )
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

	$order_data = array(
		'service_id'              => $service_id,
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
 * @return int
 */
function apparel_service_update_order_status( $status, $session_id = '', $payment_intent_id = '' ) {
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
			'value' => sanitize_text_field( $value ),
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

		if ( '' === $value && '' === $label ) {
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
 * @return array
 */
function apparel_service_get_checkout_line_items( $secret_key, $session_id ) {
	$response = apparel_service_stripe_get_request(
		$secret_key,
		sprintf( 'https://api.stripe.com/v1/checkout/sessions/%s/line_items', rawurlencode( $session_id ) ),
		array( 'limit' => 1 )
	);

	if ( empty( $response['data'] ) || ! is_array( $response['data'] ) ) {
		return array();
	}

	return $response['data'];
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
	update_post_meta( $order_id, '_variation_id', sanitize_text_field( $order_data['variation_id'] ?? '' ) );
	update_post_meta( $order_id, '_variation_name', sanitize_text_field( $order_data['variation_name'] ?? '' ) );
	update_post_meta( $order_id, '_quantity', absint( $order_data['quantity'] ?? 1 ) );
	update_post_meta( $order_id, '_stripe_line_items', $order_data['line_items'] ?? array() );
	update_post_meta( $order_id, '_stripe_custom_fields', $order_data['custom_fields'] ?? array() );

	return $order_id;
}
