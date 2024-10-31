<?php
/**
 * Render admin page for invalid token.
 *
 * @link       https://heyzine.com/
 * @since      1.0.0
 *
 * @package    Cl_Heyzine
 * @subpackage Cl_Heyzine/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Heyzine', 'pdf-flipbook-heyzine' ); ?></h1>

	<div class="notice notice-warning"><p><?php esc_html_e( 'You don\'t have a HeyZine valid token configured. Please click this button to autenthicate with your Heyzine account or create a new one.', 'pdf-flipbook-heyzine' ); ?></p></div>

	<?php
	if ( isset( $error_description ) ) {
		?>
		<div class="notice notice-error">
			<p><strong><?php echo esc_html( $error_description ); ?>.</strong></p>
			<p><strong><?php esc_html_e( 'Try to authenticate again.', 'pdf-flipbook-heyzine' ); ?></strong></p>
		</div>
		<?php
	}
	?>

	<a href="<?php echo esc_url( $oauth_url ); ?>" class="button button-primary"><?php esc_html_e( 'Authenticate with Heyzine', 'pdf-flipbook-heyzine' ); ?></a>
</div>
