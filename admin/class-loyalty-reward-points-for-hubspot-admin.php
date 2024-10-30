<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/admin
 */
class Loyalty_Reward_Points_For_Hubspot_Admin {

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
	 * Register the stylesheets for the admin area.
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
		$screen = get_current_screen();

		if ( ! empty( $screen ) && in_array( $screen->id, $this->get_plugin_admin_screens() ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/loyalty-reward-points-for-hubspot-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();
		if ( ! empty( $screen ) && in_array( $screen->id, $this->get_plugin_admin_screens() ) ) {
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

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/loyalty-reward-points-for-hubspot-admin.js', array( 'jquery' ), $this->version, false );

			$ajax_data = array(
				'ajax_url'                 => admin_url( 'admin-ajax.php' ),
				'ajax_action'              => 'mwb_app_connector_ajax',
				'ajax_nonce'               => wp_create_nonce( 'mwb_app_connector_ajax_nonce' ),
				'ajax_error'               => __( 'An error occured!', 'loyalty-reward-points-for-hubspot' ),
				'trigger_error_message'    => __( 'Something Went Wrong', 'loyalty-reward-points-for-hubspot' ),
				'process_complete_message' => __( 'Process Completed', 'loyalty-reward-points-for-hubspot' ),
				'sweetalert_button_text'   => __( 'Ok', 'loyalty-reward-points-for-hubspot' ),
			);

			wp_localize_script( $this->plugin_name, 'ajax_data', $ajax_data );

			wp_enqueue_script( 'app-connect-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweet-alert.min.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Get plugin admin screens.
	 *
	 * @return array Array of admin screens.
	 */
	public function get_plugin_admin_screens() {
		return array( 'toplevel_page_loyalty-reward-points-for-hubspot' );
	}

	/**
	 * Adding settings menu for Loyalty Reward Points for HubSpot.
	 *
	 * @since    1.0.0
	 */
	public function add_menu_for_connector() {
		add_menu_page( 'Loyalty Reward Points for HubSpot', 'Loyalty Reward Points for HubSpot', 'manage_options', 'loyalty-reward-points-for-hubspot', array( $this, 'mwb_plugin_page' ), 'dashicons-awards', 15 );
	}

	/**
	 * Load template for plugin.
	 *
	 * @since    1.0.0
	 */
	public function mwb_plugin_page() {
		$file_path = '/partials/loyalty-reward-points-for-hubspot-admin-display.php';
		self::load_template( $file_path );
	}

	/**
	 * Get general settings.
	 *
	 * @return array Array of settings.
	 */
	public static function get_pointsandreward_app_settings() {

		$settings[] = array(
			'type' => 'title',
		);

		$settings[] = array(
			'title' => esc_html__( 'Enable App', 'loyalty-reward-points-for-hubspot' ),
			'id'    => 'mwb_enable_pointsandreward_app',
			'type'  => 'checkbox',
			'value' => get_option( 'mwb_enable_pointsandreward_app', 'no' ),
			'desc'  => __( 'Enable App', 'loyalty-reward-points-for-hubspot' ),
		);

		$api_key    = get_option( 'mwb_app_pointsandreward_api_key' );
		$settings[] = array(
			'title'             => esc_html__( 'Api Key', 'loyalty-reward-points-for-hubspot' ),
			'id'                => 'mwb_app_pointsandreward_api_key',
			'type'              => empty( $api_key ) ? 'text' : 'password',
			'class'             => 'toggle-password',
			'custom_attributes' => array( 'readonly' => 'readonly' ),
			'value'             => $api_key,
			'desc'              => __( 'App Api Key', 'loyalty-reward-points-for-hubspot' ),
		);

		$settings[] = array(
			'title' => esc_html__( 'Widget Login Url', 'loyalty-reward-points-for-hubspot' ),
			'id'    => 'mwb_app_pointsandreward_store_login_url',
			'type'  => 'text',
			'value' => get_option( 'mwb_app_pointsandreward_store_login_url' ),
			'desc'  => __( 'Store\'s login page url', 'loyalty-reward-points-for-hubspot' ),
		);

		$settings[] = array(
			'title' => esc_html__( 'Widget Sign Up Url', 'loyalty-reward-points-for-hubspot' ),
			'id'    => 'mwb_app_pointsandreward_store_register_url',
			'type'  => 'text',
			'value' => get_option( 'mwb_app_pointsandreward_store_register_url' ),
			'desc'  => __( 'Store\'s registration page url', 'loyalty-reward-points-for-hubspot' ),
		);

		$settings[] = array(
			'title'  => esc_html__( 'Widget Language', 'loyalty-reward-points-for-hubspot' ),
			'id'      => 'mwb_app_pointsandreward_widget_language',
			'type'    => 'select',
			'options' => array(
				'en' => 'English',
				'fr' => 'French',
			),
			'desc'    => __( 'Select language for widget', 'loyalty-reward-points-for-hubspot' ),
		);

		$settings[] = array(
			'type' => 'sectionend',
		);
		return $settings;
	}

	/**
	 * Get settings for authorization.
	 *
	 * @return array Array of settings for authorization.
	 */
	public static function get_pointsandreward_authorize_fields() {

		$settings[] = array(
			'type' => 'title',
		);

		$settings[] = array(
			'title' => esc_html__( 'Api Key', 'loyalty-reward-points-for-hubspot' ),
			'id'    => 'mwb_app_pointsandreward_api_key',
			'type'  => 'text',
			'value' => get_option( 'mwb_app_pointsandreward_api_key' ),
			'desc'  => __( 'Enter App Api Key for authorization', 'loyalty-reward-points-for-hubspot' ),
		);

		$settings[] = array(
			'type' => 'sectionend',
		);
		return $settings;

	}

	/**
	 * It will add new resource.
	 *
	 * @param array $resources Existing resources.
	 * @return array
	 */
	public function ecomm_par_add_new_resource( $resources ) {

		$resource = array(
			'action',
		);

		return array_merge( $resources, $resource );
	}

	/**
	 * It will add new events for topic resources.
	 *
	 * @param array $topic_events Existing valid events for resources.
	 * @return array
	 */
	public function ecomm_par_add_new_topic_events( $topic_events ) {

		// New events to be used for resources.
		$new_events = array( 'wp_insert_comment' );

		return array_merge( $topic_events, $new_events );
	}

	/**
	 * It will get the product's review details.
	 *
	 * @param array $payload  Payload.
	 * @param array $resource Resource.
	 * @param array $resource_id Resource ID.
	 * @return array
	 */
	public function ecomm_par_get_product_review_data( $payload, $resource, $resource_id ) {

		global $wpdb;
		if ( 'action' === $resource && 'wp_insert_comment' === $payload['action'] ) {

			$payload_data = array();
			$comment = get_comment( $resource_id, ARRAY_A );

			if ( $comment ) {
				$payload_data['id']               = (int) $comment['comment_ID'];
				$payload_data['date_created']     = $comment['comment_date'];
				$payload_data['date_created_gmt'] = $comment['comment_date_gmt'];

				$payload_data['is_product_review'] = ( 'review' === $comment['comment_type'] ) ? true : false;

				if ( 'review' === $comment['comment_type'] ) {
					$payload_data['rating']   = (int) get_comment_meta( $comment['comment_ID'], 'rating', true );
					$payload_data['verified'] = wc_review_is_from_verified_owner( $comment['comment_id'] );
				}
				$payload_data['post_id'] = (int) $comment['comment_post_ID'];
				$payload_data['status']  = $this->get_comment_status( (string) $comment['comment_approved'] );

				$payload_data['reviewer']       = $comment['comment_author'];
				$payload_data['reviewer_email'] = $comment['comment_author_email'];
				$payload_data['review']         = wpautop( $comment['comment_content'] );

				if ( ! empty( $payload_data['reviewer_email'] ) ) {
					$user = get_user_by( 'email', $payload_data['reviewer_email'] );
					if ( $user ) {

						$count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(comment_ID) FROM ' . $wpdb->prefix . 'comments  WHERE `comment_post_ID` = %s AND `user_id` = %s )', $payload_data['post_id'], $user->ID ) );
						$payload_data['user_id']       = $user->ID;
						$payload_data['no_of_reviews'] = $count;

					}
				}
			}

			return $payload_data;
		}

		return $payload;
	}

	/**
	 * Checks comment_approved to set comment status for single comment output.
	 *
	 * @since 1.0.0
	 * @param string|int $comment_approved comment status.
	 * @return string Comment status.
	 */
	public function get_comment_status( $comment_approved ) {
		switch ( $comment_approved ) {
			case 'hold':
			case '0':
				$status = 'hold';
				break;
			case 'approve':
			case '1':
				$status = 'approved';
				break;
			case 'spam':
			case 'trash':
			default:
				$status = $comment_approved;
				break;
		}
		return $status;
	}

	/**
	 * Check and include admin view file
	 *
	 * @param string $file_path Relative path of file.
	 * @param array  $params Array of extra params.
	 * @param bool   $base   If is base.
	 */
	public static function load_template( $file_path, $params = array(), $base = false ) {

		try {

			$result = wc_get_template(
				$file_path,
				$params,
				'',
				$base ? $base : plugin_dir_path( __FILE__ )
			);

		} catch ( \Throwable $th ) {
			echo esc_html( $th->getMessage() );
			wp_die();
		}
	}

	/**
	 * Get Tabs labels.
	 */
	public function render_tabs() {
		$tabs      = apply_filters( 'mwb_app_tabs', array() );
		$file_path = 'admin/partials/mwb-app-tabs.php';
		self::load_template( $file_path, array( 'tabs' => $tabs ), LOYALTY_REWARD_POINTS_FOR_HUBSPOT_DIR_PATH );
	}

	/**
	 * Get Tabs Contents.
	 */
	public function render_tab_contents() {
		$tabs = apply_filters( 'mwb_app_tabs', array() );
		if ( ! empty( $tabs ) && is_array( $tabs ) ) {
			foreach ( $tabs as $file_name => $value ) {
				$params = array();
				$file_path = 'admin/partials/' . $file_name . '.php';
				self::load_template( $file_path, $params, LOYALTY_REWARD_POINTS_FOR_HUBSPOT_DIR_PATH );
			}
		}
	}

	/**
	 * Specify Tabs Lables/Contents.
	 *
	 * @param  array $tabs Tabs array.
	 * @return array Tabs array.
	 */
	public function return_tabs( $tabs = array() ) {

		return array(
			'mwb-pointsandreward' => __( 'Settings', 'loyalty-reward-points-for-hubspot' ),
		);
	}

	// End of class.
}
