<?php
/**
 * Lightweight WordPress stubs for theme tests.
 */

if ( ! defined( 'DAY_IN_SECONDS' ) ) {
	define( 'DAY_IN_SECONDS', 86400 );
}

$walker_stub = new class() {};
unset( $walker_stub );

if ( ! class_exists( 'Walker' ) ) {
	class Walker {
	}
}

if ( ! class_exists( 'WP_Term' ) ) {
	class WP_Term {
		public $term_id;
		public $taxonomy;
		public $name;

		public function __construct( $term_id = 0, $taxonomy = '', $name = '' ) {
			$this->term_id = $term_id;
			$this->taxonomy = $taxonomy;
			$this->name     = $name;
		}
	}
}

$GLOBALS['mbf_test_state'] = array();

function reset_mbf_test_state() {
	$GLOBALS['mbf_test_state'] = array(
		'current_user_can'   => array(),
		'options'            => array(),
		'settings_errors'    => array(),
		'rewrite_rules'      => array(),
		'transients'         => array(),
		'sidebar_cache'      => array(),
		'posts'              => array(),
		'user_caps'          => array(),
		'terms'              => array(
			'doc_category'    => array(),
			'doc_subcategory' => array(),
		),
		'post_terms'         => array(),
		'post_meta'          => array(),
		'permalinks'         => array(),
		'titles'             => array(),
		'category_posts'     => array(),
		'terms_meta'         => array(),
		'flags'              => array(),
	);
}
reset_mbf_test_state();

function add_action( ...$args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return true;
}

function add_filter( ...$args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return true;
}

function current_user_can( $cap ) {
	return ! empty( $GLOBALS['mbf_test_state']['current_user_can'][ $cap ] );
}

function sanitize_title_with_dashes( $string ) {
	$slug = strtolower( trim( preg_replace( '/[^a-z0-9]+/i', '-', $string ), '-' ) );
	return $slug;
}

function add_settings_error( $setting, $code, $message, $type = 'error' ) {
	$GLOBALS['mbf_test_state']['settings_errors'][] = compact( 'setting', 'code', 'message', 'type' );
}

function esc_html__( $text ) {
	return $text;
}

function wp_parse_args( $args, $defaults = array() ) {
	return array_merge( $defaults, $args );
}

function get_option( $key, $default = false ) {
	return array_key_exists( $key, $GLOBALS['mbf_test_state']['options'] ) ? $GLOBALS['mbf_test_state']['options'][ $key ] : $default;
}

function update_option( $key, $value, $autoload = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	$GLOBALS['mbf_test_state']['options'][ $key ] = $value;
	return true;
}

function trailingslashit( $value ) {
	return rtrim( $value, "\\/" ) . '/';
}

function add_rewrite_rule( ...$args ) {
	$GLOBALS['mbf_test_state']['rewrite_rules'][] = $args;
}

function delete_transient( $key ) {
	$GLOBALS['mbf_test_state']['sidebar_cache'][] = $key;
	unset( $GLOBALS['mbf_test_state']['transients'][ $key ] );
	return true;
}

function get_transient( $key ) {
	return $GLOBALS['mbf_test_state']['transients'][ $key ] ?? false;
}

function set_transient( $key, $value, $expiration ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	$GLOBALS['mbf_test_state']['transients'][ $key ] = $value;
	return true;
}

function wp_is_post_revision( $post_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return ! empty( $GLOBALS['mbf_test_state']['flags']['is_revision'] );
}

function wp_is_post_autosave( $post_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return ! empty( $GLOBALS['mbf_test_state']['flags']['is_autosave'] );
}

function get_post( $post_id ) {
	return $GLOBALS['mbf_test_state']['posts'][ $post_id ] ?? null;
}

function user_can( $user_id, $cap ) {
	return ! empty( $GLOBALS['mbf_test_state']['user_caps'][ $user_id ][ $cap ] );
}

function is_singular( $post_type = '' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return ! empty( $GLOBALS['mbf_test_state']['flags']['is_singular'] );
}

function get_the_ID() {
	return $GLOBALS['mbf_test_state']['flags']['current_post'] ?? 0;
}

function get_the_terms( $post_id, $taxonomy ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return $GLOBALS['mbf_test_state']['post_terms'][ $post_id ][ $taxonomy ] ?? array();
}

function is_tax( $taxonomy = array() ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return ! empty( $GLOBALS['mbf_test_state']['flags']['is_tax'] );
}

function get_queried_object() {
	return $GLOBALS['mbf_test_state']['flags']['queried_object'] ?? null;
}

function get_terms( $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	$taxonomy = is_array( $args ) && isset( $args['taxonomy'] ) ? $args['taxonomy'] : '';
	return $GLOBALS['mbf_test_state']['terms'][ $taxonomy ] ?? array();
}

function is_wp_error( $thing ) {
	return $thing instanceof WP_Error;
}

class WP_Error {}

function get_term_link( $term ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return '/docs/term/' . ( is_object( $term ) ? $term->term_id : $term );
}

function get_posts( $args ) {
	$taxonomy = $args['tax_query'][0]['terms'][0] ?? 0;
	return $GLOBALS['mbf_test_state']['category_posts'][ $taxonomy ] ?? array();
}

function get_term_meta( $term_id, $key, $single = true ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return $GLOBALS['mbf_test_state']['terms_meta'][ $term_id ][ $key ] ?? 0;
}

function get_post_meta( $post_id, $key, $single = true ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return $GLOBALS['mbf_test_state']['post_meta'][ $post_id ][ $key ] ?? 0;
}

function get_the_title( $post_id ) {
	return $GLOBALS['mbf_test_state']['titles'][ $post_id ] ?? '';
}

function get_permalink( $post_id ) {
	return $GLOBALS['mbf_test_state']['permalinks'][ $post_id ] ?? '';
}

function wp_list_pluck( $list, $field ) {
	return array_map(
		function( $item ) use ( $field ) {
			return is_object( $item ) ? $item->{$field} : ( $item[ $field ] ?? null );
		},
		$list
	);
}

function wp_json_encode( $data, $options = 0 ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return json_encode( $data );
}

function esc_attr( $text ) {
	return $text;
}

function esc_html_e( $text ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	echo $text;
}

function esc_attr_e( $text ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	echo $text;
}

function esc_html( $text ) {
	return $text;
}

function __( $text ) {
	return $text;
}

function wp_unslash( $text ) {
	return $text;
}

function sanitize_text_field( $text ) {
	return trim( $text );
}

function wp_strip_all_tags( $text ) {
	return strip_tags( $text );
}

function home_url( $path = '/' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return 'https://example.com' . $path;
}

function post_type_archive_title( $prefix = '', $display = true ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return 'Docs';
}

function get_post_type_archive_link( $post_type ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return '/docs/';
}

function absint( $value ) {
	return abs( intval( $value ) );
}

function checked( $checked, $current = true ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return $checked == $current ? 'checked' : '';
}

function wp_die( $message ) { // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	die( $message );
}

function mbf_theme_breadcrumbs() {
	echo '<span>Breadcrumb</span>';
}

function wpautop( $text ) {
	return '<p>' . $text . '</p>';
}

function term_description( $term ) {
	return is_object( $term ) && isset( $term->description ) ? $term->description : '';
}

function __return_null() {
	return null;
}
