<?php

namespace fuel;

class main extends \TimberSite
{
	/**
	 * Theme setup
	 */
	public function setup()
	{
		add_theme_support('post-thumbnails');

		//Advanced custom fields integration to theme
		add_action('init', array(__NAMESPACE__ . '\\acf', 'init'));

		// Register plugins
		add_action('tgmpa_register', array($this, 'register_plugins'));

		// Scripts and styles
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

		// Menus
		add_action('init', array($this, 'register_menus'));

		//Allowed mime types
		add_filter('upload_mimes', array($this, 'cc_mime_types'));

		//Blog Excerpt Modifications
		add_filter('excerpt_more', array($this, 'new_excerpt_more'));
		add_filter('excerpt_length', array($this, 'new_excerpt_length'));

		//Timber template modifications
		add_filter('timber_context', array($this, 'add_to_context'));

		//ACF filter
		add_filter('acf/settings/load_json', array($this, 'acf_json_load_path'));

		//Move YoastSEO plugin to a lower priority
		add_filter('wpseo_metabox_prio', function() { return 'low';});
	}

	function add_to_context($context)
	{
		$context['menu'] = new \TimberMenu('primary');
		$context['site'] = $this;
		return $context;
	}

	public function acf_json_load_path($paths)
	{
		$paths[0] = get_template_directory() . '/assets/json/acf-fields';
		return $paths;
	}

	/**
	 * Register Plugs
	 */
	public function register_plugins()
	{
		$plugins = array(
			array(
				'name' => 'Developer',
				'slug' => 'developer',
				'required' => false
			),
			array(
				'name' => 'Timber',
				'slug' => 'timber',
				'required' => true
			)
		);

		$config = array();

		tgmpa($plugins, $config);
	}

	/**
	 * Allowed Mime Types
	 */
	public function cc_mime_types($mimes)
	{
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function enqueue_scripts()
	{
		if (is_singular()) wp_enqueue_script('comment-reply');

		wp_enqueue_style('app', get_template_directory_uri().'/assets/css/app.css', false, '1.0.0', 'all');
		wp_enqueue_script('main', get_template_directory_uri().'/assets/js/main.js', array('jquery'), '1.0.0', true);
	}

	public function new_excerpt_more($more)
	{
		return ' &hellip;';
	}

	public function new_excerpt_length($length)
	{
		return 20;
	}

	/**
	 * Register WP menus
	 */
	public function register_menus()
	{
		register_nav_menu('primary', 'Primary');
	}
}
