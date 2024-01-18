<?php
namespace Concordamos;

class User_Change_Pass {

	public const TRANSIENT_PREFIX = 'concordamospwu';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_endpoint' ) );
		add_action( 'template_redirect', array( $this, 'redirect_logged_user' ) );
	}

	public function register_endpoint() {
		register_rest_route(
			'concordamos/user/v1/change-pass',
			'/generate-token',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'generate_token' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'concordamos/user/v1/change-pass',
			'/change',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'change_password' ),
				'permission_callback' => '__return_true',
			)
		);
	}
	protected function validate_generate_token( $data ) {

		if ( is_user_logged_in() ) {
			return new \WP_Error( 'invalid_data', __( "You're already logged in.", 'concordamos' ), array( 'status' => 400 ) );
		}
		if ( ! isset( $data['email'] ) || empty( $data['email'] ) ) {
			return new \WP_Error( 'invalid_data', __( 'Email field is empty.', 'concordamos' ), array( 'status' => 400 ) );
		}

		return true;
	}

	protected function clear_data_generate_token( $data ) {
		$data['email'] = sanitize_email( $data['email'] );
		return $data;
	}
	protected function generate_token_key( int $user_id ) {
		return bin2hex( random_bytes( 64 ) );
	}

	protected function delete_old_tokens() {
		global $wpdb;

		$user_meta_key = self::TRANSIENT_PREFIX;

		$tokens = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->usermeta WHERE meta_key LIKE %s",
				$wpdb->esc_like( $user_meta_key )
			)
		);

		foreach ( $tokens as $token ) {
			$value = maybe_unserialize( $token['meta_value'] );
			// delete_transient(str_replace('_transient_', '', $transient));
		}
	}

	protected function delete_token( $user_id ) {
		return delete_user_meta( $user_id, self::TRANSIENT_PREFIX );
	}

	protected function send_token_email( string $token_key, string $user_email ) {
		try {

			$site_name = get_bloginfo( 'name' );
			$url       = get_permalink( get_page_by_template( 'concordamos/template-change-pass.php' ) ) . '?concordamos_change_pass_tk=' . $token_key;
			$link      = sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_url( $url ) );

			$email_subject = sprintf( __( '[%s] Password Reset', 'concordamos' ), esc_html( $site_name ) );
			$email_message = sprintf( __( 'You have requested a password reset. Click the following link to reset your password: %s', 'concordamos' ), wp_kses( $link, 'data' ) );
			$email_headers = array( 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $user_email, $email_subject, $email_message, $email_headers );

			return true;

		} catch ( \Exception $e ) {
			return new \WP_Error( 'general_error', __( 'Error sending email. Please try again later.', 'concordamos' ), array( 'status' => 500 ) );
		}
	}

	protected function save_token_transient( string $token_key_prefixed, object $user ) {
		$token_expiration = time() + ( HOUR_IN_SECONDS / 4 );
		$token_meta_key   = self::TRANSIENT_PREFIX;

		return update_user_meta(
			$user->ID,
			$token_meta_key,
			array(
				'token' => $token_key_prefixed,
				'exp'   => $token_expiration,
			)
		);
	}


	public function generate_token( $request ) {
		$data = $request->get_json_params();

		$validate = $this->validate_generate_token( $data );
		if ( $validate !== true ) {
			return $validate;
		}
		$data = $this->clear_data_generate_token( $data );
		// try to get user
		$user = get_user_by( 'email', $data['email'] );
		if ( is_wp_error( $user ) ) {
			return new \WP_Error( 'user_not_found', __( "Email not found. Please make sure you've entered the correct email address.", 'concordamos' ), array( 'status' => 401 ) );
		}

		// generate token
		$token_key      = $user->ID . '_' . $this->generate_token_key( $user->ID );
		$token_prefixed = self::TRANSIENT_PREFIX . $token_key;

		// save transient
		$this->save_token_transient( $token_key, $user );
		// send email
		$this->send_token_email( $token_key, $user->data->user_email );

		return array( 'message' => __( 'Link sent successfully! Please check your email.', 'concordamos' ) );
	}

	public function redirect_logged_user() {
		if ( is_user_logged_in() && strstr( get_page_template(), 'template-login.php' ) ) {
			nocache_headers();
			wp_safe_redirect( home_url() );
			exit;
		}
	}

	public static function get_token( string $token_key ) {
		$token_parts = explode( '_', $token_key );
		$user_id     = $token_parts[0];

		if ( ! is_numeric( $user_id ) || ! get_userdata( (int) $user_id ) ) {
			return false;
		}
		$user_id = (int) $user_id;

		$token_key_prefixed = self::TRANSIENT_PREFIX;

		$token_data = get_user_meta( $user_id, $token_key_prefixed, true );

		if ( $token_data && isset( $token_data['exp'] ) && $token_data['exp'] >= time() && $token_data['token'] === $token_key ) {
			return $token_data['token'];
		}

		return false;
	}
	protected function validate_change_password( $data ) {

		if ( is_user_logged_in() ) {
			return new \WP_Error( 'invalid_data', __( "You're already logged in.", 'concordamos' ), array( 'status' => 400 ) );
		}
		if ( ! isset( $data['password'] ) || empty( $data['password'] ) ) {
			return new \WP_Error( 'invalid_data', __( 'Password field is empty.', 'concordamos' ), array( 'status' => 400 ) );
		}
		if ( ! isset( $data['repeat_password'] ) || $data['password'] != $data['repeat_password'] ) {
			return new \WP_Error( 'invalid_data', __( "Passwords fields don't match.", 'concordamos' ), array( 'status' => 400 ) );
		}
		if ( ! isset( $data['token'] ) || empty( $data['token'] ) ) {
			return new \WP_Error( 'invalid_data', __( 'Token is empty.', 'concordamos' ), array( 'status' => 400 ) );
		}
		if ( ! self::get_token( $data['token'] ) ) {
			return new \WP_Error( 'invalid_data', __( "The link you've used is invalid or has expired. Please restart the password reset process to receive a new link.", 'concordamos' ), array( 'status' => 400 ) );
		}

		return true;
	}

	protected function clear_data_change_password( $data ) {
		$data['token']    = sanitize_text_field( $data['token'] );
		$data['password'] = sanitize_text_field( $data['password'] );
		return $data;
	}

	public function change_password( $data ) {
		$validate = $this->validate_change_password( $data );
		if ( $validate !== true ) {
			return $validate;
		}
		$data = $this->clear_data_change_password( $data );

		try {
			$user_id = explode( '_', $data['token'] );
			$user_id = $user_id[0];
			if ( ! is_numeric( $user_id ) ) {
				// funny guy is trying to do funny stuff
				return new \WP_Error( 'invalid_token', __( "The link you've used is invalid or has expired. Please restart the password reset process to receive a new link.", 'concordamos' ), array( 'status' => 400 ) );
			}
			$user = get_userdata( (int) $user_id );
			if ( ! $user || is_wp_error( $user ) ) {
				return new \WP_Error( 'invalid_token', __( "The link you've used is invalid or has expired. Please restart the password reset process to receive a new link.", 'concordamos' ), array( 'status' => 400 ) );
			}
			$this->delete_token( $user->ID );
			wp_set_password( $data['password'], $user->ID );
			return array( 'message' => __( 'Password successfully changed! You can now log in with your new password.', 'concordamos' ) );

		} catch ( \Exception $e ) {
			return new \WP_Error( 'general', __( 'An unknown error occurred while attempting to change your password. Please try again later.', 'concordamos' ), array( 'status' => 400 ) );
		}
	}
}

new User_Change_Pass();
