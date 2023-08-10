<?php
namespace Concordamos;

class User_Login {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_endpoint'));
        add_action( 'template_redirect', array($this, 'redirect_logged_user') );
    }

    public function register_endpoint() {
        register_rest_route('concordamos/user/v1', '/login', array(
            'methods' => 'POST',
            'callback' => array($this, 'login'),
        ));
    }
    protected function validate( $data ) {
        if( is_user_logged_in() ) {
            return new \WP_Error('invalid_data', __("You're already logged in. No need to log in again.", 'concordamos'), array('status' => 400));
        }
        if ( ! isset($data['username']) || empty( $data['username'])) {
            return new \WP_Error('invalid_data', __('Username field is empty.', 'concordamos'), array('status' => 400));
        }
        if ( ! isset($data['password']) || empty( $data['password'])) {
            return new \WP_Error('invalid_data', __('Password field is empty.', 'concordamos'), array('status' => 400));
        }

        return true;
    }

    protected function clear_data( $data ) {
        $data['username'] = sanitize_text_field($data['username']);
        $data['password'] = sanitize_text_field($data['password']);
        return $data;
    }
    public function login($request) {
        $data = $request->get_json_params();

        $validate = $this->validate( $data );
        if( $validate !== true ) {
            return $validate;
        }
        $data = $this->clear_data( $data );
        
        $user = wp_authenticate($data['username'], $data['password']);
        if (is_wp_error($user)) {
            return new \WP_Error('authentication_failed',  __( 'Invalid username or password.', 'concordamos' ), array('status' => 401));
        }

        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);

        return array('message' => __('Login successful.', 'concordamos'));
    }

    public function redirect_logged_user() {
        if( is_user_logged_in() && strstr(get_page_template(), 'template-login.php') ) {
            wp_redirect( home_url() );
            exit;
        }
    }
}

new User_Login();
