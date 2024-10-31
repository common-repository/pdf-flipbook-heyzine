<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://heyzine.com/
 * @since      1.0.0
 *
 * @package    Cl_Heyzine
 * @subpackage Cl_Heyzine/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cl_Heyzine
 * @subpackage Cl_Heyzine/admin
 */
class Cl_Heyzine_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Heyzine API base URL.
	 *
	 * @var string $base_url Heyzine API base URL.
	 */
	private $base_url = 'https://heyzine.com';

	/**
	 * Heyzine API authorize endpoint.
	 *
	 * @var string $api_auth Heyzine API authorize endpoint.
	 */
	private $api_auth = '/api2/authorize';

	/**
	 * Heyzine API token endpoint.
	 *
	 * @var string $api_token Heyzine API token endpoint.
	 */
	private $api_token = '/api2/token';

	/**
	 * Heyzine API flipbook list endpoint.
	 *
	 * @var string $api_flipbook_list Heyzine API flipbook list endpoint.
	 */
	private $api_flipbook_list = '/api2/list';

	/**
	 * Heyzine API flipbook endpoint.
	 *
	 * @var string $api_flipbook Heyzine API flipbook endpoint.
	 */
	private $api_flipbook = '/api2/flipbook';

	/**
	 * Heyzine API new flipbook endpoint.
	 *
	 * @var string $api_new_flipbook Heyzine API new flipbook endpoint.
	 */
	private $api_new_flipbook = '/api2/create';

	/**
	 * Heyzine API client ID.
	 *
	 * @var string $client_id Heyzine API client ID.
	 */
	private $client_id = 'heyzine_wordpress';

	/**
	 * Heyzine API client secret.
	 *
	 * @var string $client_secret Heyzine API client secret.
	 */
	private $client_secret = 'dvXmtX3eLCyKU3z1VyH3';

	/**
	 * Heyzine API redirect URI.
	 *
	 * @var string $redirect_uri Heyzine API redirect URI.
	 */
	private $redirect_uri = '';

	/**
	 * Heyzine nonce action.
	 *
	 * @var string $nonce_action Heyzine nonce action.
	 */
	private $nonce_action = 'heyzine_nonce_action_state';

	/**
	 * Heyzine namespace.
	 *
	 * @var string $heyzine_namespace Heyzine namespace.
	 */
	private $heyzine_namespace = 'heyzine/v1';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name  The name of this plugin.
	 * @param    string $version      The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->redirect_uri = admin_url( 'admin.php?page=heyzine' );
	}

	/**
	 * Register the inline JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function inline_scripts() {
		global $pagenow;
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'heyzine' === $_GET['page'] ) { // phpcs:ignore
			$params        = array(
				'title'      => __( 'Select file to create new heyzine', 'pdf-flipbook-heyzine' ),
				'btn'        => __( 'Select file', 'pdf-flipbook-heyzine' ),
				'copied'     => __( 'Shortcode copied to clipboard!', 'pdf-flipbook-heyzine' ),
				'copy_error' => __( 'ERROR copying shortcode to clipboard!', 'pdf-flipbook-heyzine' ),
				'page'       => __( 'Page', 'pdf-flipbook-heyzine' ),
			);
			$localize_vars = 'const CL_HEYZINE = ' . wp_json_encode( $params ) . ';';

			$script = $localize_vars . file_get_contents( CL_HEYZINE_PLUGIN_PATH . 'admin/js/cl-heyzine-admin-inline.js' ); // phpcs:ignore

			wp_enqueue_media();
			wp_register_script( 'cl-heyzine-upload-new', '', array(), CL_HEYZINE_VERSION, true );
			wp_enqueue_script( 'cl-heyzine-upload-new' );
			wp_add_inline_script( 'cl-heyzine-upload-new', $script );
		}
	}

	/**
	 * Register the inline CSS for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function inline_css() {
		global $pagenow;
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'heyzine' === $_GET['page'] ) { // phpcs:ignore
			$css = file_get_contents( CL_HEYZINE_PLUGIN_PATH . 'admin/css/cl-heyzine-admin-inline.css' ); // phpcs:ignore

			wp_register_style( 'cl-heyzine', '', array(), CL_HEYZINE_VERSION );
			wp_enqueue_style( 'cl-heyzine' );
			wp_add_inline_style( 'cl-heyzine', $css );
		}
	}

	/**
	 * Register the menu for this plugin into the WordPress Dashboard menu.
	 */
	public function admin_menu() {
		$svg_heyzine = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 49 49"><path fill="#fff" d="M18.6217 15.5008a1.6511 1.6511 0 1 0-1.611-1.651 1.6609 1.6609 0 0 0 1.611 1.651Z"/><path fill="#fff" fill-rule="evenodd" d="M28.0676 13.4412a2.0012 2.0012 0 0 0-1.3809-1.591l-.7604-.2202-.3402-.1a3.8634 3.8634 0 0 1-.5904-.2001 1.4011 1.4011 0 0 1-.5904-.5904 11.807 11.807 0 0 0-3.1419-3.232 6.5641 6.5641 0 0 0-6.8743-.3903 9.2955 9.2955 0 0 0-5.2033 8.1451v23.965a8.206 8.206 0 0 0 0 1.2007 7.8748 7.8748 0 0 0 4.6729 6.6642c.8687.388 1.7833.6638 2.7217.8205.2914.0504.5854.0839.8806.1001h1.7811l-.3302-15.5898a17.4206 17.4206 0 0 0 6.354-13.3183v-2.4615l1.9412-1.2808a2.0014 2.0014 0 0 0 .8605-1.9212ZM15.0095 44.6307a5.1426 5.1426 0 0 1-3.122-4.4728 4.2423 4.2423 0 0 1 .6104-2.8818 3.4114 3.4114 0 0 1 .3702-.4803l.8405-.7005.9206-.5503a9.7466 9.7466 0 0 1 1.581-.5904l.2502 10.1664a7.294 7.294 0 0 1-1.4509-.4903Zm-3.102-10.5966V15.3023a6.554 6.554 0 0 1 1.0006-3.3821 6.6844 6.6844 0 0 1 2.6517-2.3815 3.8423 3.8423 0 0 1 4.1426.2302 9.0063 9.0063 0 0 1 2.3815 2.4815 3.9121 3.9121 0 0 0 1.711 1.531l.5104.2101-1.8212 1.2008v3.8924a15.01 15.01 0 0 1-1.651 6.6542 15.0981 15.0981 0 0 1-4.4628 5.2332l-3.0619 2.2214-1.4009.8406Z" clip-rule="evenodd"/><path fill="#fff" fill-rule="evenodd" d="M26.2664.0117H7.2545A7.2745 7.2745 0 0 0 0 7.2663V26.158a7.265 7.265 0 0 0 4.5076 6.7111 7.2652 7.2652 0 0 0 2.787.5434h1.8911v-2.7017H7.2946a4.563 4.563 0 0 1-4.5729-4.5528V7.2662a4.5526 4.5526 0 0 1 4.5729-4.5428h19.0118a4.5629 4.5629 0 0 1 4.5729 4.5428v18.8919a4.5634 4.5634 0 0 1-2.826 4.2093 4.563 4.563 0 0 1-1.7469.3435h-5.6235a19.0134 19.0134 0 0 1-1.7711 1.7111v1.0006h7.3246a7.2653 7.2653 0 0 0 5.1462-2.1171 7.2634 7.2634 0 0 0 2.1383-5.1374V7.2663A7.2745 7.2745 0 0 0 26.2664.0117Z" clip-rule="evenodd"/></svg>';

		// Encode SVG to show the icon with the menu.
		$svg_icon = 'data:image/svg+xml;base64,' . base64_encode( $svg_heyzine ); // phpcs:ignore

		add_menu_page(
			'Heyzine',
			'Heyzine',
			'manage_options',
			'heyzine',
			array( $this, 'render_settings_page' ),
			$svg_icon,
			81 // Position after Settings.
		);
	}

	/**
	 * Get heyzine flipbooks from Heyzine API.
	 */
	public function get_api_heyzines() {
		$flipbook_list = $this->get_flipbook_list();

		if ( is_array( $flipbook_list ) ) {
			$flipbook_list = array_map(
				function ( $flipbook ) {
					return array(
						'id'             => $flipbook['id'],
						'date'           => $flipbook['date'],
						'title'          => $flipbook['title'],
						'subtitle'       => $flipbook['subtitle'],
						'description'    => $flipbook['description'],
						'private'        => $flipbook['private'],
						'pages'          => $flipbook['pages'],
						'link_custom'    => $flipbook['links']['custom'],
						'link_base'      => $flipbook['links']['base'],
						'link_thumbnail' => $flipbook['links']['thumbnail'],
						'link_pdf'       => $flipbook['links']['pdf'],
					);
				},
				$flipbook_list
			);
		}

		return rest_ensure_response( $flipbook_list );
	}

	/**
	 * Check if the current user has the correct permissions to view the private data from API.
	 */
	public function get_private_data_permissions_check() {
		// Restrict endpoint to only users who have the edit_posts capability.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'You can not view this private data.', 'pdf-flipbook-heyzine' ),
				array( 'status' => 401 )
			);
		}

		// This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
		return true;
	}

	/**
	 * Get heyzine flipbook from Heyzine API.
	 *
	 * @param WP_REST_Request $request The request object.
	 */
	public function get_api_heyzine( $request ) {
		$flipbook_id = (string) $request['id'];
		$flipbook    = $this->get_flipbook( $flipbook_id );

		if ( is_array( $flipbook ) ) {
			return rest_ensure_response(
				array(
					'id'             => $flipbook['id'],
					'date'           => $flipbook['date'],
					'title'          => $flipbook['title'],
					'subtitle'       => $flipbook['subtitle'],
					'description'    => $flipbook['description'],
					'private'        => $flipbook['private'],
					'tags'           => $flipbook['tags'],
					'oembed'         => $flipbook['oembed'],
					'link_custom'    => $flipbook['links']['custom'],
					'link_base'      => $flipbook['links']['base'],
					'link_thumbnail' => $flipbook['links']['thumbnail'],
					'link_pdf'       => $flipbook['links']['pdf'],
				)
			);
		} else {
			return new WP_Error(
				'rest_heyzine_invalid',
				esc_html__( 'The flipbook does not exist.', 'pdf-flipbook-heyzine' ),
				array( 'status' => 404 )
			);
		}
	}

	/**
	 * Register the routes for the API.
	 */
	public function register_heyzine_routes() {
		// Registering route for flipbooks.
		register_rest_route(
			$this->heyzine_namespace,
			'/flipbooks',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_api_heyzines' ),
				'permission_callback' => array( $this, 'get_private_data_permissions_check' ),
			)
		);

		// Registering route for single flipbook. The (?P<id>[\w-.]+) is our path variable for the ID, which, in this example, can only be some form of positive number.
		register_rest_route(
			$this->heyzine_namespace,
			'/flipbooks/(?P<id>[\w\-\.]+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_api_heyzine' ),
				'permission_callback' => array( $this, 'get_private_data_permissions_check' ),
			)
		);
	}

	/**
	 * Register the block for this plugin into the WordPress Gutenberg editor.
	 */
	public function heyzine_block() {
		register_block_type(
			CL_HEYZINE_PLUGIN_PATH . 'heyzine-block/build'
		);
	}

	/**
	 * Return the OAuth token stored in the database or false if it doesn't exist.
	 */
	private function get_db_oauth_token() {
		$token = get_option( 'cl_heyzine_oauth_token' );

		if ( $token ) {
			return $token;
		} else {
			return false;
		}
	}

	/**
	 * Get the OAuth token from Heyzine API.
	 *
	 * @param string $code The code necesary to obtain Heyzine API token.
	 */
	private function get_api_token( string $code ) {
		$res = wp_remote_post(
			$this->base_url . $this->api_token,
			array(
				'body' => array(
					'grant_type'    => 'authorization_code',
					'client_id'     => $this->client_id,
					'client_secret' => $this->client_secret,
					'code'          => $code,
					'redirect_uri'  => $this->base_url,
				),
			)
		);

		if ( is_wp_error( $res ) ) {
			return $res->get_error_message();
		} else {
			return json_decode( wp_remote_retrieve_body( $res ), true );
		}
	}

	/**
	 * Create new Heyzine from Heyzine API.
	 */
	private function set_new_flipbook() {
		if (
			isset( $_POST['heyzine_new_nonce'] )
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['heyzine_new_nonce'] ) ), 'cl_heyzine_upload_file' )
		) {
			if ( esc_url_raw( $_POST['cl-heyzine-url'] ) ) {
				$res = wp_remote_post(
					$this->base_url . $this->api_new_flipbook,
					array(
						'body'    => array(
							'pdf' => esc_url_raw( $_POST['cl-heyzine-url'] ),
						),
						'headers' => array(
							'Authorization' => 'Bearer ' . $this->get_db_oauth_token(),
						),
					)
				);

				if ( is_wp_error( $res ) ) {
					$response = $res;
				} else {
					$response = json_decode( wp_remote_retrieve_body( $res ), true );
				}
			} else {
				$response = new WP_Error(
					'url_file_not_valid',
					esc_html__( 'Error: File URL not valid', 'pdf-flipbook-heyzine' ),
				);
			}
		} else {
			$response = new WP_Error(
				'nonce_error',
				esc_html__( 'Error validating nonce', 'pdf-flipbook-heyzine' )
			);
		}

		return $response;
	}

	/**
	 * Get the list of flipbooks from Heyzine API.
	 */
	private function get_flipbook_list() {
		$data = get_transient( 'cl_heyzine_flipbook_list' );

		if ( false === $data ) {
			$res = wp_remote_get(
				$this->base_url . $this->api_flipbook_list,
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $this->get_db_oauth_token(),
					),
				)
			);

			if ( is_wp_error( $res ) ) {
				$data = $res->get_error_message();
			} else {
				$data                  = json_decode( wp_remote_retrieve_body( $res ), true );
				$days_to_save_api_data = 1;
				set_transient( 'cl_heyzine_flipbook_list', $data, $days_to_save_api_data * DAY_IN_SECONDS );
			}
		}

		return $data;
	}

	/**
	 * Return cl-heyzine shortcode.
	 *
	 * @param array $atts The attributes of the shortcode.
	 */
	public function heyzine_shortcode( $atts ) {
		$res   = '';
		$style = '';
		$atts  = shortcode_atts(
			array(
				'title'            => '',
				'sub_title'        => '',
				'description'      => '',
				'show_link'        => false,
				'responsive_width' => true,
				'flipbook_width'   => '800',
				'flipbook_height'  => '500',
				'heyzine_link'     => '',
				'heyzine_page'     => '0',

			),
			$atts,
			'cl_heyzine'
		);

		if ( empty( $atts['heyzine_link'] ) || ! wp_http_validate_url( $atts['heyzine_link'] ) ) {
			return null;
		}

		$style .= 'border: 0px;';
		if ( isset( $atts['flipbook_width'] ) ) {
			$style .= ' width: ' . absint( $atts['flipbook_width'] ) . 'px;';
		} else {
			$style .= ' width: 100%;';
		}
		$style .= ' height: ' . absint( $atts['flipbook_height'] ) . 'px;';

		$res .= '<div class="cl-heyzine-embed">';

		if ( ! empty( $atts['title'] ) ) {
			$res .= '<p class="cl-heyzine-title">' . esc_html( $atts['title'] ) . '</p>';
		}

		if ( ! empty( $atts['sub_title'] ) ) {
			$res .= '<p class="cl-heyzine-subtitle">' . esc_html( $atts['sub_title'] ) . '</p>';
		}

		if ( ! empty( $atts['description'] ) ) {
			$res .= '<p class="cl-heyzine-description">' . wp_kses( $atts['description'], array() ) . '</p>';
		}

		if ( ! empty( $atts['show_link'] ) ) {
			$res .= '<p class="cl-heyzine-link"><a href="' . esc_url( $atts['heyzine_link'] ) . '" target="_blank" rel="noreferrer">' . esc_url( $atts['heyzine_link'] ) . '</a></p>';
		}

		if ( empty( $atts['heyzine_page'] ) ) {
			$page = null;
		} else {
			$page = '#page/' . absint( $atts['heyzine_page'] );
		}

		$res .= '<iframe class="cl-heyzine-iframe fp-iframe" allow="fullscreen" style="' . $style . '" src="' . esc_url( $atts['heyzine_link'] . $page ) . '"></iframe>';
		$res .= '</div>';

		return $res;
	}

	/**
	 * Get a flipbook from Heyzine API.
	 *
	 * @param string $heyzine_id The ID of the flipbook.
	 */
	private function get_flipbook( $heyzine_id ) {
		$res = wp_remote_get(
			$this->base_url . $this->api_flipbook . '?id=' . $heyzine_id,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $this->get_db_oauth_token(),
				),
			)
		);

		if ( is_wp_error( $res ) ) {
			return $res->get_error_message();
		} else {
			return json_decode( wp_remote_retrieve_body( $res ), true );
		}
	}

	/**
	 * Save the OAuth token in the database.
	 *
	 * @param array $token_res The response from Heyzine API.
	 */
	public function save_token( $token_res ) {
		if ( is_array( $token_res ) ) {
			$access_token  = $token_res['access_token'];
			$refresh_token = $token_res['refresh_token'];
			$expires_in    = $token_res['expires_in'];
			$expires_at    = time() + $expires_in;

			update_option( 'cl_heyzine_oauth_token', $access_token, false );
			update_option( 'cl_heyzine_oauth_refresh_token', $refresh_token, false );
			update_option( 'cl_heyzine_oauth_token_expires_at', $expires_at, false );

			// Redirect to the settings page with javascript.
			echo '<script>window.location.href = "' . esc_url( $this->redirect_uri ) . '";</script>';

			// Show button to redirect setting page in case that js is disabled.
			echo '<br><a href="' . esc_url( $this->redirect_uri ) . '" class="button button-primary">' . esc_html__( 'Heyzine settings page', 'pdf-flipbook-heyzine' ) . '</a>';

			// Redirect to the settings page with PHP.
			wp_safe_redirect( $this->redirect_uri );
			exit;
		} else {
			require_once CL_HEYZINE_PLUGIN_PATH . 'admin/partials/cl-heyzine-admin-error-token.php';
		}
	}

	/**
	 * Render the settings page with the button to authenticate with Heyzine.
	 */
	private function render_settings_page_invalid_token() {
		$nonce = wp_create_nonce( $this->nonce_action );

		$oauth_url = $this->base_url . $this->api_auth . '?response_type=code&client_id=' . $this->client_id . '&state=' . $nonce . '&redirect_uri=' . $this->redirect_uri;

		if ( isset( $_GET['state'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['state'] ) ), $this->nonce_action ) ) {
				if ( empty( $_GET['error'] ) && ! empty( $_GET['code'] ) ) {
					$token_res = $this->get_api_token( filter_var( $_GET['code'], FILTER_SANITIZE_SPECIAL_CHARS ) );

					$this->save_token( $token_res );
				} elseif ( ! empty( $_GET['error'] ) && ! empty( $_GET['error_description'] ) ) {
					$error_description = sanitize_text_field( $_GET['error_description'] );
				}
			} else {
				$error_description = esc_html__( 'Invalid nonce state', 'pdf-flipbook-heyzine' );
			}
		}

		require_once CL_HEYZINE_PLUGIN_PATH . 'admin/partials/cl-heyzine-admin-invalid-token.php';
	}

	/**
	 * Render the settings page for this plugin.
	 */
	private function render_settings_page_valid_token() {
		$notice_deleted_transient = false;

		$nonce     = wp_create_nonce( $this->nonce_action );
		$oauth_url = $this->base_url . $this->api_auth . '?response_type=code&client_id=' . $this->client_id . '&state=' . $nonce . '&redirect_uri=' . $this->redirect_uri;

		if (
			isset( $_GET['cl_action'] )
			&& 'cl_heyzine_delete_transient' === $_GET['cl_action']
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['heyzine_nonce'] ) ), 'cl_heyzine_delete_transient' )
		) {
			$res = delete_transient( 'cl_heyzine_flipbook_list' );

			if ( $res ) {
				$notice_deleted_transient = true;
			}
		}

		require_once CL_HEYZINE_PLUGIN_PATH . 'admin/partials/cl-heyzine-admin-valid-token.php';
	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function render_settings_page() {
		if (
			isset( $_GET['cl_action'] )
			&& 'cl_heyzine_delete_token' === $_GET['cl_action']
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['heyzine_nonce'] ) ), 'cl_heyzine_delete_token' )
		) {
			delete_option( 'cl_heyzine_oauth_token' );
			delete_option( 'cl_heyzine_oauth_refresh_token' );
			delete_option( 'cl_heyzine_oauth_token_expires_at' );
			delete_transient( 'cl_heyzine_flipbook_list' );
		}

		if ( false === $this->get_db_oauth_token() ) {
			$this->render_settings_page_invalid_token();
		} else {
			$this->render_settings_page_valid_token();
		}
	}
}
