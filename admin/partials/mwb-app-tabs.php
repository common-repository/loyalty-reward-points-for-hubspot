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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<?php if ( ! empty( $tabs ) && is_array( $tabs ) ) : ?>
<div class="mwb-tab__header mwb-content-wrap">
	<ul class="mwb-tab__header-list">
		<?php foreach ( $tabs as $key => $label ) : ?>
			<li class="mwb-tab__header-list-item">
				<?php $is_active = 'mwb-dashboard' === $key ? 'active' : ''; ?>
				<a href="" class="mwb-tab-link <?php echo esc_html( $key ); ?>-tab-link <?php echo esc_html( $is_active ); ?>" ><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
