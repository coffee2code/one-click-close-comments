<?php

defined( 'ABSPATH' ) or die();

class One_Click_Close_Comments_Test extends WP_UnitTestCase {

	public function tearDown() {
		parent::tearDown();

		c2c_OneClickCloseComments::reset();

		wp_deregister_style( 'c2c_OneClickCloseComments' );
		wp_dequeue_style( 'c2c_OneClickCloseComments' );
		wp_deregister_script( 'c2c_OneClickCloseComments' );
		wp_dequeue_script( 'c2c_OneClickCloseComments' );
	}


	//
	//
	// DATA PROVIDERS
	//
	//


	public static function get_default_hooks() {
		return array(
			array( 'action', 'load-edit.php',          'do_init' ),
			array( 'action', 'load-edit.php',          'enqueue_scripts_and_styles' ),
			array( 'action', 'wp_ajax_close_comments', 'toggle_comment_status' ),
		);
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	public static function change_click_char ( $char ) {
		return '*';
	}


	//
	//
	// TESTS
	//
	//


	public function test_class_name() {
		$this->assertTrue( class_exists( 'c2c_OneClickCloseComments' ) );
	}

	public function test_version() {
		$this->assertEquals( '2.7.1', c2c_OneClickCloseComments::version() );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_OneClickCloseComments', 'init' ) ) );
	}

	/**
	 * @dataProvider get_default_hooks
	 */
	public function test_default_hooks_when_not_in_admin( $hook_type, $hook, $function, $priority = 10, $class_method = true ) {
		$callback = $class_method ? array( 'c2c_OneClickCloseComments', $function ) : $function;

		$prio = $hook_type === 'action' ?
			has_action( $hook, $callback ) :
			has_filter( $hook, $callback );

		$this->assertFalse( $prio );
	}

	public function test_enable_admin() {
		if ( ! defined( 'WP_ADMIN' ) ) {
			define( 'WP_ADMIN', true );
		}

		c2c_OneClickCloseComments::init();

		$this->assertTrue( is_admin() );
	}

	/**
	 * @dataProvider get_default_hooks
	 */
	public function test_default_hooks_when_in_admin( $hook_type, $hook, $function, $priority = 10, $class_method = true ) {
		$this->test_enable_admin();
		$callback = $class_method ? array( 'c2c_OneClickCloseComments', $function ) : $function;

		$prio = $hook_type === 'action' ?
			has_action( $hook, $callback ) :
			has_filter( $hook, $callback );

		$this->assertNotFalse( $prio );
		if ( $priority ) {
			$this->assertEquals( $priority, $prio );
		}
	}

	/*
	 * reset()
	 */

	public function test_reset() {
		$this->test_filter_c2c_one_click_close_comments_click_char();

		c2c_OneClickCloseComments::reset();

		$this->test_get_click_char();
	}

	/*
	 * toggle_comment_status()
	 */

	public function test_toggle_comment_status_when_comments_open() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'open' ) );
		$_POST['post_id'] = $post_id;

		$this->expectOutputRegex( '~^0$~', c2c_OneClickCloseComments::toggle_comment_status( false ) );
	}

	public function test_toggle_comment_status_when_comments_closed() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'closed' ) );
		$_POST['post_id'] = $post_id;

		$this->expectOutputRegex( '~^1$~', c2c_OneClickCloseComments::toggle_comment_status( false ) );
	}

	public function test_toggle_comment_status_when_user_does_not_have_caps() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'open' ) );
		$_POST['post_id'] = $post_id;

		$this->expectOutputRegex( '~^-1$~', c2c_OneClickCloseComments::toggle_comment_status( false ) );
	}

	public function test_toggle_comment_status_when_POST_post_id_value_not_set() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'open' ) );
		unset( $_POST['post_id'] );

		$this->expectOutputRegex( '~^-1$~', c2c_OneClickCloseComments::toggle_comment_status( false ) );
	}

	public function test_toggle_comment_status_when_POST_post_id_invalid() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'open' ) );
		$_POST['post_id'] = 999999;

		$this->expectOutputRegex( '~^-1$~', c2c_OneClickCloseComments::toggle_comment_status( false ) );
	}

	/*
	 * get_click_char()
	 */

	public function test_get_click_char() {
		$this->assertEquals( '<span class="dashicons dashicons-admin-comments"></span>', c2c_OneClickCloseComments::get_click_char() );
	}

	/*
	 * filter: c2c_one_click_close_comments_click_char
	 */

	public function test_filter_c2c_one_click_close_comments_click_char() {
		add_filter( 'c2c_one_click_close_comments_click_char', array( __CLASS__, 'change_click_char' ) );

		$this->assertEquals( '*', c2c_OneClickCloseComments::get_click_char() );

		remove_filter( 'c2c_one_click_close_comments_click_char', array( __CLASS__, 'change_click_char' ) );
	}

	/*
	 * deprecated filter: one-click-close-comments-click-char
	 */

	/**
	 * @expectedDeprecated one-click-close-comments-click-char
	 */
	public function test_deprecated_filter_one_click_close_comments_click_char() {
		add_filter( 'one-click-close-comments-click-char', function ( $char ) { return 'O'; } );

		$this->assertEquals( 'O', c2c_OneClickCloseComments::get_click_char() );
	}

	/*
	 * add_post_column()
	 */

	public function test_add_post_column_when_comments_column_present() {
		$expected = array( 'a' => 'A', 'close_comments' => '', 'comments' => 'Comments', 'b' => 'B' );
		$actual = c2c_OneClickCloseComments::add_post_column( array( 'a' => 'A', 'comments' => 'Comments', 'b' => 'B' ) );

		$this->assertEquals( $expected, $actual );
		$this->assertEquals( array_keys( $expected ), array_keys( $actual ) );
	}

	public function test_add_post_column_when_comments_column_present_as_last_column() {
		$expected = array( 'a' => 'A', 'b' => 'B', 'close_comments' => '', 'comments' => 'Comments' );
		$actual = c2c_OneClickCloseComments::add_post_column( array( 'a' => 'A', 'b' => 'B', 'comments' => 'Comments' ) );

		$this->assertEquals( $expected, $actual );
		$this->assertEquals( array_keys( $expected ), array_keys( $actual ) );
	}

	public function test_add_post_column_when_comments_column_not_present() {
		$expected = array( 'a' => 'A', 'b' => 'B', 'close_comments' => '' );
		$actual = c2c_OneClickCloseComments::add_post_column( array( 'a' => 'A', 'b' => 'B' ) );

		$this->assertEquals( $expected, $actual );
		$this->assertEquals( array_keys( $expected ), array_keys( $actual ) );
	}

	/*
	 * handle_column_data()
	 */

	public function test_handle_column_data_with_some_other_column() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'open' ) );

		$this->expectOutputRegex( '~^$~', c2c_OneClickCloseComments::handle_column_data( 'comments', $post_id ) );
	}

	public function test_handle_column_data_with_comments_open_when_user_does_not_have_caps() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'open' ) );

		$expected = '<span id="%s" class="comment_state-1" title="Comments are open." aria-hidden="true"><span class="dashicons dashicons-admin-comments"></span></span>';
		$expected .= '<span class="screen-reader-text">Comments are open.</span>';

		$this->expectOutputRegex(
			'~^' . sprintf( preg_quote( $expected ), '[^"]+' ) . '$~',
			c2c_OneClickCloseComments::handle_column_data( 'close_comments', $post_id )
		);
	}

	public function test_handle_column_data_with_comments_closed_when_user_does_not_have_caps() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'closed' ) );

		$expected = '<span id="%s" class="comment_state-0" title="Comments are closed." aria-hidden="true"><span class="dashicons dashicons-admin-comments"></span></span>';
		$expected .= '<span class="screen-reader-text">Comments are closed.</span>';

		$this->expectOutputRegex(
			'~^' . sprintf( preg_quote( $expected ), '[^"]+' ) . '$~',
			c2c_OneClickCloseComments::handle_column_data( 'close_comments', $post_id )
		);
	}

	public function test_handle_column_data_for_post_with_comments_open() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'open' ) );

		$expected = '<span id="%s" class="comment_state-1" title="Comments are open. Click to close." aria-hidden="true"><span class="dashicons dashicons-admin-comments"></span></span>';
		$expected .= '<span class="screen-reader-text">Comments are open. Click to close.</span>';

		$this->expectOutputRegex(
			'~^' . sprintf( preg_quote( $expected ), '[^"]+' ) . '$~',
			c2c_OneClickCloseComments::handle_column_data( 'close_comments', $post_id )
		);
	}

	public function test_handle_column_data_for_post_with_comments_closed() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		c2c_OneClickCloseComments::do_init();
		$post_id = $this->factory->post->create( array( 'comment_status' => 'closed' ) );

		$expected = '<span id="%s" class="comment_state-0" title="Comments are closed. Click to open." aria-hidden="true"><span class="dashicons dashicons-admin-comments"></span></span>';
		$expected .= '<span class="screen-reader-text">Comments are closed. Click to open.</span>';

		$this->expectOutputRegex(
			'~^' . sprintf( preg_quote( $expected ), '[^"]+' ) . '$~',
			c2c_OneClickCloseComments::handle_column_data( 'close_comments', $post_id )
		);
	}

	/*
	 * register_styles()
	 */

	public function test_register_styles() {
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'enqueued' ) );

		c2c_OneClickCloseComments::register_styles();

		$this->assertTrue( wp_style_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'enqueued' ) );
	}

	/*
	 * enqueue_scripts_and_styles()
	 */

	public function test_enqueue_scripts_and_styles() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_OneClickCloseComments', 'enqueue_admin_js' ) ) );
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_OneClickCloseComments', 'enqueue_admin_css' ) ) );
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'enqueued' ) );

		c2c_OneClickCloseComments::enqueue_scripts_and_styles();

		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( 'c2c_OneClickCloseComments', 'enqueue_admin_js' ) ) );
		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( 'c2c_OneClickCloseComments', 'enqueue_admin_css' ) ) );
		$this->assertTrue( wp_style_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'enqueued' ) );
	}

	/*
	 * enqueue_admin_css()
	 */

	public function test_enqueue_admin_css() {
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertFalse( wp_style_is( 'c2c_OneClickCloseComments', 'enqueued' ) );

		$this->test_enqueue_scripts_and_styles();
		c2c_OneClickCloseComments::enqueue_admin_css();

		$this->assertTrue( wp_style_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertTrue( wp_style_is( 'c2c_OneClickCloseComments', 'enqueued' ) );
	}

	/*
	 * enqueue_admin_js()
	 */

	public function test_enqueue_admin_js() {
		$this->assertFalse( wp_script_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertFalse( wp_script_is( 'c2c_OneClickCloseComments', 'enqueued' ) );

		c2c_OneClickCloseComments::do_init();
		c2c_OneClickCloseComments::enqueue_admin_js();

		$this->assertTrue( wp_script_is( 'c2c_OneClickCloseComments', 'registered' ) );
		$this->assertTrue( wp_script_is( 'c2c_OneClickCloseComments', 'enqueued' ) );
	}

}
