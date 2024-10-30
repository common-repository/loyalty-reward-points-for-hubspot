<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
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
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
 */
class Loyalty_Reward_Points_For_Hubspot {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Loyalty_Reward_Points_For_Hubspot_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'LOYALTY_REWARD_POINTS_FOR_HUBSPOT_VERSION' ) ) {
			$this->version = LOYALTY_REWARD_POINTS_FOR_HUBSPOT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'loyalty-reward-points-for-hubspot';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ajax_callback();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loyalty_Reward_Points_For_Hubspot_Loader. Orchestrates the hooks of the plugin.
	 * - Loyalty_Reward_Points_For_Hubspot_I18n. Defines internationalization functionality.
	 * - Loyalty_Reward_Points_For_Hubspot_Admin. Defines all hooks for the admin area.
	 * - Loyalty_Reward_Points_For_Hubspot_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loyalty-reward-points-for-hubspot-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loyalty-reward-points-for-hubspot-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-loyalty-reward-points-for-hubspot-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loyalty-reward-points-for-hubspot-ajax-callbacks.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-loyalty-reward-points-for-hubspot-public.php';

		$this->loader = new Loyalty_Reward_Points_For_Hubspot_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Loyalty_Reward_Points_For_Hubspot_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {

		$plugin_i18n = new Loyalty_Reward_Points_For_Hubspot_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Loyalty_Reward_Points_For_Hubspot_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_for_connector', 99 );
		$this->loader->add_filter( 'woocommerce_valid_webhook_resources', $plugin_admin, 'ecomm_par_add_new_resource' );
		$this->loader->add_filter( 'woocommerce_valid_webhook_events', $plugin_admin, 'ecomm_par_add_new_topic_events' );

		if ( get_option( 'mwb_enable_pointsandreward_app', 'no' ) === 'yes' ) {
			$this->loader->add_filter( 'woocommerce_webhook_payload', $plugin_admin, 'ecomm_par_get_product_review_data', 10, 3 );
		}

		// Specify components.
		$this->loader->add_filter( 'mwb_app_tabs', $plugin_admin, 'return_tabs' );
		// Includes all the templates.
		$this->loader->add_action( 'mwb_app_connect_render_tab', $plugin_admin, 'render_tabs' );
		$this->loader->add_action( 'mwb_app_connect_render_tabcontent', $plugin_admin, 'render_tab_contents' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_public_hooks() {

		$plugin_public = new Loyalty_Reward_Points_For_Hubspot_Public( $this->get_plugin_name(), $this->get_version() );

		if ( get_option( 'mwb_enable_pointsandreward_app', 'no' ) === 'yes' ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'wp_loaded', $plugin_public, 'eccom_par_set_cookie_for_referral' );
			$this->loader->add_action( 'user_register', $plugin_public, 'eccom_par_get_registered_customer', 10, 1 );
			$this->loader->add_action( 'wp_footer', $plugin_public, 'add_widget' );

		}

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
	 * @return    Loyalty_Reward_Points_For_Hubspot_Loader    Orchestrates the hooks of the plugin.
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

	/**
	 * Register all of the hooks related to the ajax requests.
	 *
	 * @since  1.0.0
	 */
	private function define_ajax_callback() {

		$ajax_module = new Loyalty_Reward_Points_For_Hubspot_Ajax_Callbacks();

		$this->loader->add_action(
			'wp_ajax_mwb_app_connector_ajax',
			$ajax_module,
			'mwb_app_connector_ajax'
		);
	}
}
