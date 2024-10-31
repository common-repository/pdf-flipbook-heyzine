<?php
/**
 * Render admin page for valid token
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

$expires_at       = get_option( 'cl_heyzine_oauth_token_expires_at' );
$date_time_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
$heyzines         = $this->get_flipbook_list();
$n_heyzines       = count( $heyzines );

if ( ! empty( $_POST ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['heyzine_new_nonce'] ) ), 'cl_heyzine_upload_file' ) ) {
	$res_new_flipbook = $this->set_new_flipbook();

	if ( is_wp_error( $res_new_flipbook ) ) {
		$success_new_flipbook = false;
	} else {
		$success_new_flipbook = true;
	}
}
?>

<div class="wrap cl-heyzine-options">
	<h1><?php esc_html_e( 'Heyzine', 'pdf-flipbook-heyzine' ); ?></h1>

	<?php
	if ( $notice_deleted_transient ) {
		?>
		<div class="notice notice-success notice-alt is-dismissible">
			<h2><?php esc_html_e( 'Heyzine cache deleted.', 'pdf-flipbook-heyzine' ); ?></h2>
		</div>
		<?php
	}

	if ( isset( $success_new_flipbook ) && $success_new_flipbook ) {
		?>
		<div class="notice notice-success notice-alt is-dismissible">
			<h2><?php esc_html_e( 'Heyzine submited for creation.', 'pdf-flipbook-heyzine' ); ?></h2>
			<p><?php esc_html_e( 'Now your Heyzine is working on the conversion process and will be available shortly.', 'pdf-flipbook-heyzine' ); ?></p>
			<p><?php esc_html_e( 'Update this page in a few minutes and delete Heyzine cache to view the new Flipbook.', 'pdf-flipbook-heyzine' ); ?></p>
		</div>
		<?php
	} elseif ( isset( $success_new_flipbook ) && false === $success_new_flipbook ) {
		?>
		<div class="notice notice-error notice-alt is-dismissible">
			<h2><?php esc_html_e( 'Error creating Heyzine.', 'pdf-flipbook-heyzine' ); ?></h2>
			<p><?php echo esc_html( $res_new_flipbook->get_error_message() ); ?></p>
		</div>
		<?php
	}
	?>

	<div class="notice notice-success notice-alt">
		<p>
		<?php
		// translators: %s: Heyzine API token expiration date and time..
		printf( esc_html__( 'Your token is valid until %s', 'pdf-flipbook-heyzine' ), '<strong>' . esc_html( wp_date( $date_time_format, $expires_at ) ) . '</strong>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		</p>
	</div>

	<div class="notice notice-info notice-alt">
		<p>
		<?php
		// translators: %s: Number of Heyzines.
		printf( esc_html__( 'In your account, you have %s Heyzines.', 'pdf-flipbook-heyzine' ), '<strong>' . absint( $n_heyzines ) . '</strong>' );
		?>
		</p>
	</div>

	<div class="postbox-container">
		<div class="postbox">
			<div class="inside">
				<p><?php esc_html_e( 'You can now create a new Heyzine from your posts, using a shortcode or with a Gutenberg block.', 'pdf-flipbook-heyzine' ); ?></p>

				<p><?php esc_html_e( 'Once you insert a new Heyzine into your page or post, it is pure HTML, so it will work even if the API key is invalid or expired.', 'pdf-flipbook-heyzine' ); ?></p>


			</div>
		</div>

		<div class="postbox">
			<div class="inside">
				<div class="postbox-header">
					<h2><?php esc_html_e( 'Heyzine shortcode', 'pdf-flipbook-heyzine' ); ?></h2>
				</div>

				<p><?php esc_html_e( 'Select your Heyzine title to view the shortcode to embed it.', 'pdf-flipbook-heyzine' ); ?></p>

				<select name="cl_heyzine_select" id="cl_heyzine_select">
					<option value="none"><?php esc_html_e( 'Select a Heyzine', 'pdf-flipbook-heyzine' ); ?></option>
					<?php
					foreach ( $heyzines as $heyzine ) {
						if ( empty( $heyzine['links']['custom'] ) ) {
							$heyzine_link = $heyzine['links']['base'];
						} else {
							$heyzine_link = $heyzine['links']['custom'];
						}
						$heyzine_id          = $heyzine['id'];
						$heyzine_title       = $heyzine['title'];
						$heyzine_sub_title   = $heyzine['subtitle'];
						$heyzine_description = $heyzine['description'];
						$heyzine_pages       = $heyzine['pages'];

						echo '<option value="' . esc_url( $heyzine_link ) . '" data-id="' . esc_html( $heyzine_id ) . '" data-title="' . esc_html( $heyzine_title ) . '" data-sub-title="' . esc_html( $heyzine_sub_title ) . '" data-description="' . esc_html( $heyzine_description ) . '" data-pages="' . absint( $heyzine_pages ) . '">' . esc_html( $heyzine_title ) . '</option>';
					}
					?>
				</select>

				<button data-link="" class="button button-primary cl-btn-customize" id="cl_heyzine_customize" disabled><?php esc_html_e( 'Customize', 'pdf-flipbook-heyzine' ); ?></button>

				<label class="toggle">
					<input class="toggle-checkbox" type="checkbox" name="responsive_width" id="responsive_width" checked>
					<div class="toggle-switch"></div>
					<span class="toggle-label"><?php esc_html_e( 'Responsive width', 'pdf-flipbook-heyzine' ); ?></span>
				</label>

				<label class="range">
					<input type="range" value="800" max="1200" min="150" step="10" name="flibook_width" id="flibook_width" disabled />
					<?php esc_html_e( 'Flipbook width', 'pdf-flipbook-heyzine' ); ?> <span class="range-value" id="flipbook_width_value">(800px)</span>
				</label>

				<label class="range">
					<input type="range" value="500" max="900" min="100" step="10" name="flibook_height" id="flibook_height" />
					<?php esc_html_e( 'Flipbook height', 'pdf-flipbook-heyzine' ); ?> <span class="range-value" id="flipbook_height_value">(500px)</span>
				</label>

				<select name="cl_heyzine_select_page" id="cl_heyzine_select_page" disabled>
					<option value="0"><?php esc_html_e( 'Select page', 'pdf-flipbook-heyzine' ); ?></option>
				</select>

				<label class="toggle">
					<input class="toggle-checkbox" type="checkbox" name="show_title" id="show_title">
					<div class="toggle-switch"></div>
					<span class="toggle-label"><?php esc_html_e( 'Show Heyzine title', 'pdf-flipbook-heyzine' ); ?></span>
				</label>

				<label class="toggle">
					<input class="toggle-checkbox" type="checkbox" name="show_sub_title" id="show_sub_title">
					<div class="toggle-switch"></div>
					<span class="toggle-label"><?php esc_html_e( 'Show Heyzine subtitle', 'pdf-flipbook-heyzine' ); ?></span>
				</label>

				<label class="toggle">
					<input class="toggle-checkbox" type="checkbox" name="show_description" id="show_description">
					<div class="toggle-switch"></div>
					<span class="toggle-label"><?php esc_html_e( 'Show Heyzine description', 'pdf-flipbook-heyzine' ); ?></span>
				</label>

				<label class="toggle">
					<input class="toggle-checkbox" type="checkbox" name="show_link" id="show_link">
					<div class="toggle-switch"></div>
					<span class="toggle-label"><?php esc_html_e( 'Show Heyzine link to heyzine page', 'pdf-flipbook-heyzine' ); ?></span>
				</label>

				<code class="cl-heyzine-shortcode" id="cl-heyzine-shortcode" title="<?php esc_html_e( 'Click to copy the shortcode to clipboard', 'pdf-flipbook-heyzine' ); ?>">
				[cl_heyzine]
				</code>

			</div>
		</div>

		<div class="postbox">
			<div class="inside">
				<div class="postbox-header">
					<h2><?php esc_html_e( 'Create a new Heyzine', 'pdf-flipbook-heyzine' ); ?></h2>
				</div>

				<p><?php esc_html_e( 'If you want to upload a new file to create a Heyzine, you can do it from the below form.', 'pdf-flipbook-heyzine' ); ?></p>

				<form name="cl-new-heyzine" method="post">
					<?php wp_nonce_field( 'cl_heyzine_upload_file', 'heyzine_new_nonce' ); ?>

					<input type="hidden" name="cl-heyzine-url" value="" id="cl-upload-heyzine-url" />

					<button class="button button-secondary" id="cl-upload-heyzine-btn"><?php esc_html_e( 'Upload file to create a new Heyzine', 'pdf-flipbook-heyzine' ); ?></button>
					<button class="button button-primary button-hero cl-btn-new-heyzine" id="cl-create-heyzine-btn" type="submit" disabled><?php esc_html_e( 'Create a new Heyzine', 'pdf-flipbook-heyzine' ); ?></button>
				</form>
			</div>
		</div>

		<div class="postbox">
			<div class="inside">
				<div class="postbox-header">
					<h2><?php esc_html_e( 'Delete cache & API key', 'pdf-flipbook-heyzine' ); ?></h2>
				</div>

				<details>
					<summary><span class="dashicons dashicons-database-remove"></span> <?php esc_html_e( 'Delete Heyzine cache', 'pdf-flipbook-heyzine' ); ?></summary>

					<p><a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=heyzine&cl_action=cl_heyzine_delete_transient' ), 'cl_heyzine_delete_transient', 'heyzine_nonce' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Delete cache', 'pdf-flipbook-heyzine' ); ?></a></p>
				</details>

				<details>
					<summary><span class="dashicons dashicons-database-remove"></span> <?php esc_html_e( 'Delete Heyzine API Token', 'pdf-flipbook-heyzine' ); ?></summary>

					<p><a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=heyzine&cl_action=cl_heyzine_delete_token' ), 'cl_heyzine_delete_token', 'heyzine_nonce' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Delete token', 'pdf-flipbook-heyzine' ); ?></a></p>
				</details>
			</div>
		</div>
	</div>
</div>
