<?php
/**
 * The ajax-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
 */

/**
 * The ajax-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the ajax-specific stylesheet and JavaScript.
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
 */
class Loyalty_Reward_Points_For_Hubspot_Ajax_Callbacks {

	/**
	 * Ajax Call back
	 */
	public function mwb_app_connector_ajax() {

		$nonce_key = 'mwb_app_connector_ajax_nonce';

		check_ajax_referer( $nonce_key, 'nonce' );

		$event = ! empty( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : '';

		if ( method_exists( $this, $event ) ) {
			$data = $this->$event( $_POST );
		} else {
			$data = esc_html__( 'method not found', 'loyalty-reward-points-for-hubspot' );
		}
		echo wp_json_encode( $data );
		wp_die();
	}

	/**
	 * Save Loyalty Reward Points app settings
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array Response array.
	 */
	public function save_pointsandreward_app_setting( $posted_data = array() ) {

		$settings = $posted_data['settings'];

		update_option( 'mwb_app_pointsandreward_store_login_url', sanitize_text_field( wp_unslash( $settings['login_url'] ) ) );
		update_option( 'mwb_app_pointsandreward_store_register_url', sanitize_text_field( wp_unslash( $settings['register_url'] ) ) );
		update_option( 'mwb_app_pointsandreward_widget_language', sanitize_text_field( wp_unslash( $settings['widget_language'] ) ) );

		$api_key = $settings['api_key'];
		$url     = 'https://apps-home.martechapps.com/pointsandrewardhome/panel/isStorePluginEnabled/';
		$headers = array(
			'MWB-APP-API-KEY' => $api_key,
			'Content-Type'    => 'application/json',
		);
		$post_body = json_encode(
			array(
				'mwb_app_store_url'              => home_url(),
				'mwb_enable_pointsandreward_app' => $settings['enable_app'],
			)
		);
		$request  = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
			'body'        => $post_body,
			'cookies'     => array(),
		);

		$response = wp_remote_post( $url, $request );
		if ( is_wp_error( $response ) ) {
			$result = array(
				'success' => false,
				'message' => esc_html__( 'Unexpected Error Occured', 'loyalty-reward-points-for-hubspot' ),
			);
			return $result;
		} else {
			$response = wp_remote_retrieve_body( $response );
			$result   = json_decode( $response, true );
			if ( $result['success'] ) {
				update_option( 'mwb_enable_pointsandreward_app', sanitize_text_field( wp_unslash( $settings['enable_app'] ) ) );
				update_option( 'mwb_app_pointsandreward_api_key', sanitize_text_field( wp_unslash( $settings['api_key'] ) ) );

				$result = array(
					'success' => true,
					'message' => __( 'Settings Saved', 'loyalty-reward-points-for-hubspot' ),
				);
			} else {
				delete_option( 'mwb_app_pointsandreward_authorized' );
			}

			return $result;
		}

	}

	/**
	 * Authorize store api key.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array Response array.
	 */
	public function authorize_pointsandreward_api_key( $posted_data = array() ) {

		$api_key = $posted_data['api_key'];
		$url     = 'https://apps-home.martechapps.com/pointsandrewardhome/panel/isStorePluginEnabled/';
		$headers = array(
			'MWB-APP-API-KEY' => $api_key,
			'Content-Type'    => 'application/json',
		);
		$post_body = json_encode(
			array(
				'mwb_app_store_url'     => home_url(),
				'isAppConnected'        => true,
				'createWebhookForStore' => true,
			)
		);
		$request  = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
			'body'        => $post_body,
			'cookies'     => array(),
		);

		$response = wp_remote_post( $url, $request );
		if ( is_wp_error( $response ) ) {
			$result = array(
				'success' => false,
				'message' => esc_html__( 'Unexpected Error Occured', 'loyalty-reward-points-for-hubspot' ),
			);
			return $result;
		} else {
			$response = wp_remote_retrieve_body( $response );
			$result   = json_decode( $response, true );
			if ( $result['success'] ) {
				update_option( 'mwb_app_pointsandreward_authorized', 'yes' );
				update_option( 'mwb_app_pointsandreward_api_key', $api_key );
				$result['message'] = esc_html__( 'Api key is authorized successfully.', 'loyalty-reward-points-for-hubspot' );
			}

			return $result;
		}

	}

	// End of class.
}
