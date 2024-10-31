<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://heyzine.com/
 * @since      1.0.0
 *
 * @package    Cl_Heyzine
 * @subpackage Cl_Heyzine/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cl_Heyzine
 * @subpackage Cl_Heyzine/includes
 */
class Cl_Heyzine {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Cl_Heyzine_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CL_HEYZINE_VERSION' ) ) {
			$this->version = CL_HEYZINE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pdf-flipbook-heyzine';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cl_Heyzine_Loader. Orchestrates the hooks of the plugin.
	 * - Cl_Heyzine_I18n. Defines internationalization functionality.
	 * - Cl_Heyzine_Admin. Defines all hooks for the admin area.
	 * - Cl_Heyzine_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once CL_HEYZINE_PLUGIN_PATH . 'includes/class-cl-heyzine-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once CL_HEYZINE_PLUGIN_PATH . 'includes/class-cl-heyzine-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once CL_HEYZINE_PLUGIN_PATH . 'admin/class-cl-heyzine-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once CL_HEYZINE_PLUGIN_PATH . 'public/class-cl-heyzine-public.php';

		$this->loader = new Cl_Heyzine_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cl_Heyzine_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {
		$plugin_i18n = new Cl_Heyzine_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Cl_Heyzine_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'inline_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'inline_css' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );

		$this->loader->add_action( 'init', $plugin_admin, 'heyzine_block' );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'register_heyzine_routes' );

		$this->loader->add_shortcode( 'cl_heyzine', $plugin_admin, 'heyzine_shortcode' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cl_Heyzine_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
