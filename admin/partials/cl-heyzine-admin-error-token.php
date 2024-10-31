<?php
/**
 * Render admin page for error obtaining token.
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

<div class="notice notice-error">
	<p><strong><?php esc_html_e( 'Error obtaining token', 'pdf-flipbook-heyzine' ); ?>.</strong></p>
	<p><strong><?php echo esc_html( $token_res ); ?></strong></p>
</div>
