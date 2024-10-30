<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Loyalty_Reward_Points_for_HubSpot
 * @subpackage Loyalty_Reward_Points_for_HubSpot/admin/partials
 */

$pointandreward_settings = Loyalty_Reward_Points_For_Hubspot_Admin::get_pointsandreward_app_settings();
$authorize_settings      = Loyalty_Reward_Points_For_Hubspot_Admin::get_pointsandreward_authorize_fields();
$is_app_authorized       = get_option( 'mwb_app_pointsandreward_authorized', 'no' );

?>
<div id="mwb-pointsandreward" class="mwb-tabcontent">
	<div class="mwb-content-wrap">
		<?php if ( 'yes' == $is_app_authorized ) { ?>
		<form action="#" method="post">
			<?php woocommerce_admin_fields( $pointandreward_settings ); ?>
		</form>
		<div class="mwb-intro__button">
			<a href="" class="mwb-btn mwb-btn--filled" id="mwb-pointsandreward-setting-button" >
				<?php esc_html_e( 'Save', 'loyalty-reward-points-for-hubspot' ); ?>
			</a>
		</div>	
		<?php } else { ?>
		<form action="#" method="post">
			<?php woocommerce_admin_fields( $authorize_settings ); ?>
		</form>
		<div class="mwb-intro__button">
			<a href="" class="mwb-btn mwb-btn--filled" id="mwb-pointsandreward-authorize-button" >
				<?php esc_html_e( 'Authorize', 'loyalty-reward-points-for-hubspot' ); ?>
			</a>
		</div>
		<?php } ?>
		
	</div>
</div>
