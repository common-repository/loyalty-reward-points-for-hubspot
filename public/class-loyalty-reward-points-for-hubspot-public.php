<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/public
 */
class Loyalty_Reward_Points_For_Hubspot_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loyalty_Reward_Points_For_Hubspot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loyalty_Reward_Points_For_Hubspot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loyalty_Reward_Points_For_Hubspot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loyalty_Reward_Points_For_Hubspot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/loyalty-reward-points-for-hubspot-public.js', array( 'jquery' ), $this->version, false );

		$current_user = wp_get_current_user();
		if ( 0 == $current_user->ID ) {
			$customer_email = '';
			$username       = '';
		} else {
			$customer_email = $current_user->user_email;
			$username       = $current_user->user_login;
		}

		$ajax_data = array(
			'mwb_app_customer_id'        => $current_user->ID,
			'mwb_app_customer_email'     => $customer_email,
			'mwb_app_customer_username'  => $username,
			'mwb_app_store_url'          => home_url(),
			'mwb_app_api_key'            => get_option( 'mwb_app_pointsandreward_api_key', '' ),
			'mwb_app_store_login_url'    => get_option( 'mwb_app_pointsandreward_store_login_url', '' ),
			'mwb_app_store_register_url' => get_option( 'mwb_app_pointsandreward_store_register_url', '' ),
			'mwb_app_widget_language'    => get_option( 'mwb_app_pointsandreward_widget_language', 'en' ),
		);

		wp_localize_script( $this->plugin_name, 'mwb_app_details', $ajax_data );

	}

	/**
	 * Set the cookie for referral code after visiting the referral link.
	 */
	public function eccom_par_set_cookie_for_referral() {
		if ( ! is_user_logged_in() ) {
			if ( isset( $_GET['mwbpar_referral_code'] ) && ! empty( $_GET['mwbpar_referral_code'] ) ) {
				$referral_key = sanitize_text_field( wp_unslash( $_GET['mwbpar_referral_code'] ) );
				setcookie( 'mwbpar_referral_code', $referral_key, time() + ( 86400 * 30 ), '/' );
			}
		}
	}

	/**
	 * Add referral code in user meta.
	 *
	 * @since 1.0.0
	 * @param int $customer_id User Id.
	 * @return void
	 */
	public function eccom_par_get_registered_customer( $customer_id ) {
		if ( get_user_by( 'ID', $customer_id ) ) {
			if ( isset( $_COOKIE['mwbpar_referral_code'] ) && ! empty( $_COOKIE['mwbpar_referral_code'] ) ) {
				update_user_meta( $customer_id, 'ecomm_par_referral_code', sanitize_text_field( wp_unslash( $_COOKIE['mwbpar_referral_code'] ) ) );
				setcookie( 'mwbpar_referral_code', '', time() - 3600 );
			}
		}
	}

	/**
	 * Add widget to site.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_widget() {
		$current_user = wp_get_current_user();
		if ( 0 == $current_user->ID ) {
			$customer_email = '';
			$username       = '';
		} else {
			$customer_email = $current_user->user_email;
			$username       = $current_user->user_login;
		}

		$ajax_data = array(
			'mwb_app_customer_id'        => $current_user->ID,
			'mwb_app_customer_email'     => $customer_email,
			'mwb_app_customer_username'  => $username,
			'mwb_app_store_url'          => home_url(),
			'mwb_app_api_key'            => get_option( 'mwb_app_pointsandreward_api_key', '' ),
			'mwb_app_store_login_url'    => get_option( 'mwb_app_pointsandreward_store_login_url', '' ),
			'mwb_app_store_register_url' => get_option( 'mwb_app_pointsandreward_store_register_url', '' ),
			'mwb_app_widget_language'    => get_option( 'mwb_app_pointsandreward_widget_language', 'en' ),
		);
		?>
		
		<div class="bot-iframe-wrapper" 
			style="position: fixed;
			bottom: 0px;
			height: 90px;
			max-height: 100%;
			max-width: 100%;
			overflow: hidden;
			width: 90px;
			z-index: 9999999;">

		<iframe onload="onLoadHandler();" id='bot-iframe' src="https://apps.martechapps.com/points-and-rewards-widget/" allow="clipboard-read; clipboard-write" frameborder=0 name="bot-iframe" style="width: 100%; height: 100%"></iframe>
		</div>
		<script>
			function onLoadHandler() {
				const event = new Event('build');
					var frame = document.getElementById('bot-iframe');
				frame.contentWindow.postMessage({'mwb_app_details': <?php echo json_encode( $ajax_data ); ?>}, '*');
			}
		</script>
		<?php
	}

}
