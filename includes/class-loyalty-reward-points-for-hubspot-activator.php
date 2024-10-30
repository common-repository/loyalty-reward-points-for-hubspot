<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
 */
class Loyalty_Reward_Points_For_Hubspot_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$api_key = get_option( 'mwb_app_pointsandreward_api_key', '' );
		if ( ! empty( $api_key ) ) {
			$url     = 'https://apps-home.martechapps.com/pointsandrewardhome/panel/isStorePluginEnabled/';
			$headers = array(
				'MWB-APP-API-KEY' => $api_key,
				'Content-Type'    => 'application/json',
			);
			$post_body = json_encode(
				array(
					'mwb_app_store_url' => home_url(),
					'isAppConnected'    => true,
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

			wp_remote_post( $url, $request );
		}

	}

}
