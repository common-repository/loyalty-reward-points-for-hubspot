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

?>

<div class="mwb-body-container">
	<div class="mwb-crm-name">
		<h1 class="mwb-crm-name__title">
			<?php
			esc_html_e( 'Loyalty Reward Points for HubSpot', 'loyalty-reward-points-for-hubspot' );
			?>
		</h1>
		<div class="mwb-crm-name__version">
		<?php echo sprintf( 'V %s', esc_html( LOYALTY_REWARD_POINTS_FOR_HUBSPOT_VERSION ) ); ?>
		</div>
	</div>
	<section id="mwb-tab__wrapper" class="mwb-tab">
		<?php do_action( 'mwb_app_connect_render_tab' ); ?>
		<?php do_action( 'mwb_app_connect_render_tabcontent' ); ?>
	</section>		
</div>
