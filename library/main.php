<?php

namespace fuel;

//require_once( $dir_path . 'class-navbar-walker.php' ); // Navbar_Walker

class main {

	/**
	 * Theme setup
	 */
	public function setup() {
		// Add Theme support
		add_theme_support( 'html5' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );

		//Advanced custom fields integration to theme
		add_action( 'init', array( __NAMESPACE__ . '\\acf', 'init' ) );
		add_action( 'init', array( __NAMESPACE__ . '\\views', 'init' ) );

		// Register plugins
		add_action( 'tgmpa_register', array( $this, 'register_plugins' ) );

		// Scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Menus
		add_action( 'init', array( $this, 'register_menus' ) );

		//Allowed mime types
		add_filter('upload_mimes', array( $this, 'cc_mime_types' ));

		// Widget Areas
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_action( 'wp', array( $this, 'sidebars_template_home' ) );

		// Head Actions
		add_filter( 'body_class', array( $this, 'add_body_class_sidebar' ) );

		// Content Actions
		add_action( __NAMESPACE__ . "_loop_after", array( $this, 'get_pagination' ) );
		add_action( __NAMESPACE__ . "_content_after", array( $this, 'get_sidebar_primary' ) );

		// Footer Actions
		add_action( __NAMESPACE__ . "_footer", array( $this, 'get_sidebar_subsidiary' ) );
	}

	/**
	 * Is sidebar registered and active
	 * @param  string  $id ID name of sidebar
	 * @return boolean     True = registered and active
	 */
	private function is_active_sidebar( $id )
	{
		global $wp_registered_sidebars;

		if ( array_key_exists( $id, $wp_registered_sidebars ) && is_active_sidebar( $id ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Tegister plugins with TGMPA
	 */
	public function register_plugins()
	{
		$plugins = array(
			//array(
				//'name'               => 'Advanced Custom Fields Pro', // The plugin name.
				//'slug'               => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
				//'source'             => get_stylesheet_directory() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source.
				//'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				//'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				//'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				//'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				//'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				//'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			//),
			array(
				'name' => 'Developer',
				'slug' => 'developer',
				'required' => false
			)
		);

		$config = array();

		tgmpa( $plugins, $config );
	}

	/**
	 * Allowed Mime Types
	 */
	public function cc_mime_types( $mimes )
	{
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function enqueue_scripts()
	{
		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

		wp_enqueue_style( 'app', get_template_directory_uri().'/assets/css/app.css', false, '1.0.0', 'all' );
		wp_enqueue_script( 'main', get_template_directory_uri().'/assets/js/main.js', array('jquery'), '1.0.0', true );
	}

	/**
	 * Register WP menus
	 */
	public function register_menus()
	{
		register_nav_menu( 'primary', 'Primary' );
	}

	/**
	 * Register WP widget sidebars
	 */
	public function register_sidebars()
	{
		// Primary
		register_sidebar(array(
			'name'          => 'Primary',
			'id'            => 'primary',
			'description'   => 'Main widget-area aside content.',
			'class'         => 'aside widgets',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>'
		));

		// Subsidiary
		register_sidebar(array(
			'name'          => 'Subsidiary',
			'id'            => 'subsidiary',
			'description'   => 'Footer widget-area content.',
			'class'         => 'subsidiary widgets',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>'
		));
	}

	public function sidebars_template_home()
	{
		if ( is_page_template( 'views/templates/home.php' ) ) {
			unregister_sidebar( 'primary' );
		}
	}

	public function add_body_class_sidebar( $classes )
	{
		if ( $this->is_active_sidebar( 'primary' ) ) {
			$classes[] = 'aside-primary';
		}

		if ( $this->is_active_sidebar( 'subsidiary' ) ) {
			$classes[] = 'aside-subsidiary';
		}

		return $classes;
	}

	public function get_pagination() {
		if ( get_previous_posts_link() or get_next_posts_link() ) {
			get_template_part( 'partials/loop', 'pagination' );
		}
	}

	public function get_sidebar_primary() {
		if ( $this->is_active_sidebar( 'primary' ) ) {
			get_sidebar( 'primary' );
		}
	}

	public function get_sidebar_subsidiary() {
		if ( $this->is_active_sidebar( 'subsidiary' ) ) {
			get_sidebar( 'subsidiary' );
		}
	}

	/**
	 * Get content template for archive based on post-type.
	 * @global $post
	 */
	public function get_archive_content() {
		$post_type = get_post_type();
		get_template_part( 'content', $post_type );
	}
}
