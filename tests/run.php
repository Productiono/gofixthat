<?php
require_once __DIR__ . '/stubs.php';
require_once __DIR__ . '/../inc/docs.php';

class DocsTestHarness {
	private $tests = array();
	private $failures = array();

	public function add( $name, callable $callback ) {
		$this->tests[ $name ] = $callback;
	}

	public function assertSame( $expected, $actual, $message = '' ) {
		if ( $expected !== $actual ) {
			throw new Exception( $message ?: 'Failed asserting that values are identical.' );
		}
	}

	public function assertTrue( $condition, $message = '' ) {
		if ( true !== $condition ) {
			throw new Exception( $message ?: 'Failed asserting that condition is true.' );
		}
	}

	public function run() {
		foreach ( $this->tests as $name => $callback ) {
			reset_mbf_test_state();

			try {
				$callback( $this );
				echo ".";
			} catch ( Exception $exception ) {
				$this->failures[] = $name . ': ' . $exception->getMessage();
				echo "F";
			}
		}

		echo "\n";

		if ( $this->failures ) {
			foreach ( $this->failures as $failure ) {
				echo " - {$failure}\n";
			}

			return 1;
		}

		return 0;
	}
}

$harness = new DocsTestHarness();

$harness->add(
	'admin_permissions_required_for_sanitize',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['current_user_can'] = array( 'manage_options' => false );
		$result = mbf_docs_sanitize_options( array( 'permalink_base' => 'custom', 'show_admin_columns' => true ) );
		$t->assertSame( mbf_docs_default_options(), $result, 'Non-admin should receive defaults.' );
	}
);

$harness->add(
	'sanitize_options_sanitizes_slug',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['current_user_can'] = array( 'manage_options' => true );
		$result = mbf_docs_sanitize_options( array( 'permalink_base' => 'Docs Base', 'show_admin_columns' => '1', 'show_admin_filters' => '' ) );
		$t->assertSame( 'docs-base', $result['permalink_base'], 'Slug should be sanitized.' );
		$t->assertTrue( ! empty( $GLOBALS['mbf_test_state']['settings_errors'] ), 'Settings error should be recorded for sanitized slug.' );
	}
);

$harness->add(
	'sanitize_options_handles_empty_slug',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['current_user_can'] = array( 'manage_options' => true );
		$result = mbf_docs_sanitize_options( array( 'permalink_base' => '', 'show_admin_columns' => false ) );
		$t->assertSame( 'docs', $result['permalink_base'], 'Fallback to default slug expected.' );
	}
);

$harness->add(
	'flush_scheduled_on_option_change',
	function( DocsTestHarness $t ) {
		mbf_docs_schedule_rewrite_flush( array( 'permalink_base' => 'docs' ), array( 'permalink_base' => 'kb' ) );
		$t->assertSame( '1', $GLOBALS['mbf_test_state']['options']['mbf_docs_flush_rewrite_rules'], 'Flush flag should be set when options change.' );
		$t->assertSame( array( 'mbf_doc_sidebar_items' ), $GLOBALS['mbf_test_state']['sidebar_cache'], 'Sidebar cache should be cleared when permalink changes.' );
	}
);

$harness->add(
	'rewrite_rules_respect_permalink_base',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['options']['mbf_docs_system_options'] = array( 'permalink_base' => 'knowledge' );
		mbf_register_doc_article_rewrite_rules();
		$t->assertTrue( ! empty( $GLOBALS['mbf_test_state']['rewrite_rules'] ), 'Rewrite rule should be registered.' );
		$rule = $GLOBALS['mbf_test_state']['rewrite_rules'][0][0];
		$t->assertSame( '^knowledge/([^/]+)/([^/]+)/([^/]+)/?$', $rule, 'Permalink base should appear in rewrite regex.' );
	}
);

$harness->add(
	'sidebar_cache_invalidation_on_save',
	function( DocsTestHarness $t ) {
		$post = (object) array( 'post_type' => 'doc_article' );
		mbf_maybe_clear_doc_sidebar_on_save_post( 1, $post, true );
		$t->assertSame( array( 'mbf_doc_sidebar_items' ), $GLOBALS['mbf_test_state']['sidebar_cache'] );
	}
);

$harness->add(
	'sidebar_cache_invalidation_on_term_change',
	function( DocsTestHarness $t ) {
		mbf_maybe_clear_doc_sidebar_on_term_change( 1, 1, 'doc_subcategory' );
		$t->assertSame( array( 'mbf_doc_sidebar_items' ), $GLOBALS['mbf_test_state']['sidebar_cache'] );
		reset_mbf_test_state();
		mbf_maybe_clear_doc_sidebar_on_term_change( 1, 1, 'post_tag' );
		$t->assertSame( array(), $GLOBALS['mbf_test_state']['sidebar_cache'], 'Non-doc taxonomy should not clear cache.' );
	}
);

$harness->add(
	'meta_cap_maps_for_author_and_others',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['posts'][1] = (object) array( 'post_type' => 'doc_article', 'post_author' => 7, 'post_status' => 'publish' );
		$t->assertSame( array( 'edit_doc_articles' ), mbf_map_doc_meta_cap( array(), 'edit_doc_article', 7, array( 1 ) ) );
		$t->assertSame( array( 'edit_others_doc_articles' ), mbf_map_doc_meta_cap( array(), 'edit_doc_article', 2, array( 1 ) ) );
	}
);

$harness->add(
	'meta_cap_handles_private_reading',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['posts'][2] = (object) array( 'post_type' => 'doc_article', 'post_author' => 3, 'post_status' => 'private' );
		$GLOBALS['mbf_test_state']['user_caps'][9]['read_private_doc_articles'] = true;
		$t->assertSame( array( 'read' ), mbf_map_doc_meta_cap( array(), 'read_doc_article', 9, array( 2 ) ) );
	}
);

$harness->add(
	'doc_sidebar_uses_cached_items',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['transients']['mbf_doc_sidebar_items'] = array(
			array( 'id' => 1, 'type' => 'category', 'children' => array(), 'is_active' => false ),
		);
		$GLOBALS['mbf_test_state']['flags']['is_singular'] = false;
		$result = mbf_get_doc_sidebar_items();
		$t->assertSame( 1, count( $result ), 'Cached sidebar items should be returned.' );
		$t->assertTrue( empty( $GLOBALS['mbf_test_state']['transients']['built'] ), 'No builder transient expected when cache exists.' );
	}
);

$harness->add(
	'doc_sidebar_builds_and_caches_when_missing',
	function( DocsTestHarness $t ) {
		$GLOBALS['mbf_test_state']['terms']['doc_category'] = array( (object) array( 'term_id' => 11, 'name' => 'Getting Started' ) );
		$GLOBALS['mbf_test_state']['category_posts'][11]    = array( 101 );
		$GLOBALS['mbf_test_state']['titles'][101]           = 'Welcome';
		$GLOBALS['mbf_test_state']['permalinks'][101]       = '/docs/welcome/';
		$GLOBALS['mbf_test_state']['post_meta'][101]        = array( 'doc_display_order' => 2 );
		$GLOBALS['mbf_test_state']['post_terms'][101]       = array(
			'doc_subcategory' => array(),
		);
		$GLOBALS['mbf_test_state']['flags']['is_singular']  = false;
		$GLOBALS['mbf_test_state']['transients']            = array();

		$result = mbf_get_doc_sidebar_items();
		$t->assertTrue( isset( $GLOBALS['mbf_test_state']['transients']['mbf_doc_sidebar_items'] ), 'Sidebar items should be cached after build.' );
		$t->assertTrue( ! empty( $result ), 'Built sidebar items should not be empty.' );
	}
);

$status = $harness->run();
exit( $status );
