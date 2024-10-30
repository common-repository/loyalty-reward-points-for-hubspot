<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/includes
 */
class Loyalty_Reward_Points_For_Hubspot_I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'loyalty-reward-points-for-hubspot',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
