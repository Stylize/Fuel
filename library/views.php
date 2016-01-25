<?php

namespace fuel;

class views {

	private static $initiated = false;

	public static function init()
	{
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function init_hooks()
	{
		self::$initiated = true;

		add_action( __NAMESPACE__ . "_header", array( __CLASS__, 'get_header_nav' ) );
		add_action( __NAMESPACE__ . "_head_meta", array( __CLASS__, 'get_meta' ) );
		add_action( __NAMESPACE__ . "_footer", array( __CLASS__, 'get_footer_bottom' ) );
		add_action( __NAMESPACE__ . "_loop_before", array( __CLASS__, 'get_archive_header' ) );
		add_action( __NAMESPACE__ . "_html", array( __CLASS__, 'get_html_tag' ) );
		add_filter('template_include', array( __CLASS__, 'custom_archive_include' ));
		add_filter('template_include', array( __CLASS__, 'custom_single_include' ));
	}

	public static function get_header_nav()
	{
		get_template_part( 'views/partials/header', 'nav' );
	}

	public static function get_navbar_menu() {
		$args = array(
			'theme_location' => 'primary',    // where it's located in the theme
			'container' => false,             // remove menu container
			'container_class' => '',          // class of container
			'menu' => '',                     // menu name
			'menu_class' => '',               // adding custom nav class
			'before' => '',                   // before each link <a>
			'after' => '',                    // after each link </a>
			'link_before' => '',              // before each link text
			'link_after' => '',               // after each link text
			'depth' => 3,                     // limit the depth of the nav
			'fallback_cb' => false,           // fallback function (see below)
			'walker' => new navbar_walker()   // walker to customize menu
		);

		wp_nav_menu($args);
	}
	public static function get_meta()
	{
		get_template_part('views/partials/head', 'meta');
	}

	public static function get_html_tag() {
		get_template_part('views/partials/html');
	}

	public static function get_archive_header()
	{
		if ( is_archive() ) {
			get_template_part('partials/archive', 'header');
		}
	}

	public static function get_footer_bottom()
	{
		get_template_part( 'views/partials/footer', 'bottom' );
	}

	public static function get_archive_item()
	{
		$post_type = get_post_type();

		if ( $post_type ) {
			get_template_part( 'views/item/' .  $post_type );
		}
	}

	public static function custom_archive_include( $template )
	{
		$post_type = get_post_type();

		if ( $post_type ) {
			if ( is_archive() ) {
				if ( file_exists(get_template_directory() . '/views/archive/' . $post_type . '.php') ) {
					return get_template_directory() . '/views/archive/' . $post_type . '.php';
				}
			}
		}

		return $template;
	}

	public static function custom_single_include( $template )
	{
		$post_type = get_post_type();

		if ( $post_type ) {
			if ( is_single() ) {
				if ( file_exists( get_template_directory() . '/views/single/' . $post_type . '.php' ) ) {
					return get_template_directory() . '/views/single/' . $post_type . '.php';
				}
			}
		}

		return $template;
	}
}
