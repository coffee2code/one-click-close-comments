<?php

defined( 'ABSPATH' ) or die();

class One_Click_Close_Comments_Test extends WP_UnitTestCase {

	public function tearDown() {
		parent::tearDown();

		wp_deregister_style( 'c2c_OneClickCloseComments' );
		wp_dequeue_style( 'c2c_OneClickCloseComments' );
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


	//
	//
	// TESTS
	//
	//


	public function test_class_name() {
		$this->assertTrue( class_exists( 'c2c_OneClickCloseComments' ) );
	}

	public function test_version() {
		$this->assertEquals( '2.6.1', c2c_OneClickCloseComments::version() );
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

}
