<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Loyalty_Reward_Points_for_HubSpot
 *
 * @wordpress-plugin
 * Plugin Name:       Loyalty Reward Points for HubSpot
 * Plugin URI:        https://wordpress.org/plugins/loyalty-reward-points-for-hubspot/
 * Description:       Loyalty Reward Points for HubSpot is a connector plugin for the Loyalty Reward Points application for bridging your WooCommerce and Hubspot data.
 * Version:           1.0.1
 * Requires at least:   5.8.0
 * Tested up to:        6.1.1
 * WC requires at least:    6.0.0
 * WC tested up to:         7.2.2
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       loyalty-reward-points-for-hubspot
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin Active Detection.
 *
 * @since 1.0.0
 * @param string $plugin_slug index file of plugin.
 * @return boolean
 */
function mwb_app_connector_is_plugin_active( $plugin_slug = '' ) {

	if ( empty( $plugin_slug ) ) {
		return false;
	}
	$active_plugins = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() ) {

		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

	}
	return in_array( $plugin_slug, $active_plugins ) || array_key_exists( $plugin_slug, $active_plugins );
}

/**
 * The code that runs during plugin validation.
 * This action is checks for WooCommerce Dependency.
 *
 * @since    1.0.0
 * @return array
 */
function mwb_app_connector_activation() {

	$activation['status']  = true;
	$activation['message'] = '';

	// Dependant plugin.
	if ( ! mwb_app_connector_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

		$activation['status']  = false;
		$activation['message'] = 'woo_inactive';

	}

	return $activation;
}

$mwb_app_connector_activation = mwb_app_connector_activation();

if ( true === $mwb_app_connector_activation['status'] ) {

	/**
	 * Currently plugin version.
	 * Start at version 1.0.0 and use SemVer - https://semver.org
	 * Rename this for your plugin and update it as you release new versions.
	 */
	define( 'LOYALTY_REWARD_POINTS_FOR_HUBSPOT_VERSION', '1.0.1' );

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-loyalty-reward-points-for-hubspot-activator.php
	 */
	function activate_mwb_app_connector() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-loyalty-reward-points-for-hubspot-activator.php';
		Loyalty_Reward_Points_For_Hubspot_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-loyalty-reward-points-for-hubspot-deactivator.php
	 */
	function deactivate_mwb_app_connector() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-loyalty-reward-points-for-hubspot-deactivator.php';
		Loyalty_Reward_Points_For_Hubspot_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_mwb_app_connector' );
	register_deactivation_hook( __FILE__, 'deactivate_mwb_app_connector' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-loyalty-reward-points-for-hubspot.php';

	/**
	 * Define constants.
	 *
	 * @since    1.0.0
	 */
	function define_app_connector_constants() {
		define( 'LOYALTY_REWARD_POINTS_FOR_HUBSPOT_DIR_PATH', plugin_dir_path( __FILE__ ) );

	}

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_mwb_app_connector() {

		define_app_connector_constants();

		$plugin = new Loyalty_Reward_Points_For_Hubspot();
		$plugin->run();

		$GLOBALS['mwb_app_connector_name'] = $plugin;

	}
	run_mwb_app_connector();

	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'plugin_settings_link' );

	/**
	 * Settings link.
	 *
	 * @since    1.0.0
	 * @param   Array $links    Settings link array.
	 */
	function plugin_settings_link( $links ) {

		$my_link = array(
			'<a href="' . admin_url( 'admin.php?page=loyalty-reward-points-for-hubspot' ) . '">' . __( 'Settings', 'loyalty-reward-points-for-hubspot' ) . '</a>',
		);
		return array_merge( $my_link, $links );
	}

	/**
	* Add docs and support link.
	*/
	add_filter( 'plugin_row_meta', 'mwb_loyalty_plugin_row_links', 10, 2 );

	/**
	 * Add docs and support link.
	 *
	 * @since    1.0.0
	 * @param array  $links Other links.
	 * @param string $file plugin file path.
	 */
	function mwb_loyalty_plugin_row_links( $links, $file ) {

		if ( 'loyalty-reward-points-for-hubspot/loyalty-reward-points-for-hubspot.php' === $file ) {
			$row_meta = array(
				'docs'    => '<a href="' . esc_url( 'https://docs.makewebbetter.com/loyalty-reward-points-for-hubspot/?utm_source=pluginbackend&utm_medium=orgplugin&utm_campaign=loyalty-reward-points-for-hubspot' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'loyalty-reward-points-for-hubspot' ) . '" style="color:green;">' . esc_html__( 'Docs', 'loyalty-reward-points-for-hubspot' ) . '</a>',
				'support' => '<a href="' . esc_url( 'https://makewebbetter.com/contact-us/?utm_source=pluginbackend&utm_medium=orgplugin&utm_campaign=loyalty-reward-points-for-hubspot' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'loyalty-reward-points-for-hubspot' ) . '" style="color:green;">' . esc_html__( 'Support', 'loyalty-reward-points-for-hubspot' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
} else {

	add_action( 'admin_init', 'mwb_app_connector_activation_failure' );

	/**
	 * Deactivate this plugin.
	 *
	 * @since    1.0.0
	 */
	function mwb_app_connector_activation_failure() {

		// To hide Plugin activated notice.
		if ( ! empty( $_GET['activate'] ) ) {

			unset( $_GET['activate'] ); // phpcs:ignore
		}

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// Add admin error notice.
	add_action( 'admin_notices', 'mwb_app_connector_activation_admin_notice' );

	/**
	 * This function is used to display plugin activation error notice.
	 *
	 * @since    1.0.0
	 */
	function mwb_app_connector_activation_admin_notice() {

		global $mwb_app_connector_activation;

		?>

		<?php if ( 'woo_inactive' == $mwb_app_connector_activation['message'] ) : ?>

			<div class="notice notice-error is-dismissible mwb-notice">
				<p><strong><?php esc_html_e( 'WooCommerce', 'loyalty-reward-points-for-hubspot' ); ?></strong><?php esc_html_e( ' is not activated, Please activate WooCommerce first to activate ', 'loyalty-reward-points-for-hubspot' ); ?><strong><?php esc_html_e( 'Loyalty Reward Points for HubSpot', 'loyalty-reward-points-for-hubspot' ); ?></strong><?php esc_html_e( '.', 'loyalty-reward-points-for-hubspot' ); ?></p>
			</div>

			<?php
		endif;
	}
}

