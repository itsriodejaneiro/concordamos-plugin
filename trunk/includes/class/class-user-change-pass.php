<?php
namespace Concordamos;

class User_Change_Pass {

    public const TRANSIENT_PREFIX = 'concordamospwu';

    public function __construct() {
        add_action('rest_api_init', array($this, 'register_endpoint'));
        add_action( 'template_redirect', array($this, 'redirect_logged_user') );
    }

    public function register_endpoint() {
        register_rest_route('concordamos/user/v1/change-pass', '/generate-token', array(
            'methods' => 'POST',
            'callback' => array($this, 'generate_token'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('concordamos/user/v1/change-pass', '/change', array(
            'methods' => 'POST',
            'callback' => array($this, 'change_password'),
            'permission_callback' => '__return_true',
        ));

    }
    protected function validate_generate_token( $data ) {

        if( is_user_logged_in() ) {
            return new \WP_Error('invalid_data', __("You're already logged in.", 'concordamos'), array('status' => 400));
        }
        if ( ! isset($data['email']) || empty( $data['email'])) {
            return new \WP_Error('invalid_data', __('Email field is empty.', 'concordamos'), array('status' => 400));
        }

        return true;
    }

    protected function clear_data_generate_token( $data ) {
        $data['email'] = sanitize_email( $data[ 'email'] );
        return $data;
    }
    protected function generate_token_key( int $user_id ) {
        return substr(md5(time() . uniqid()), 0, 8);
    }

    protected function delete_old_tokens( $user_id ) {
        global $wpdb;

        $transient_prefix = '_transient_' . User_Change_Pass::TRANSIENT_PREFIX  . $user_id . '_';
    
        $transients = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s",
                $wpdb->esc_like($transient_prefix) . '%'
            )
        );
    
        foreach ($transients as $transient) {
            delete_transient(str_replace('_transient_', '', $transient));
        }
    
    }

    protected function send_token_email( string $token_key, string $user_email ) {
        try {

            $site_name = get_bloginfo('name');
            $url = get_permalink( get_page_by_template('concordamos/template-change-pass.php') ) . '?concordamos_change_pass_tk=' . $token_key;
            $link = sprintf( '<a href="%s">%s</a>', $url, $url );
            
            
            $email_subject = sprintf( __('[%s] Password Reset', 'concordamos'), $site_name );
            $email_message = sprintf( __('You have requested a password reset. Click the following link to reset your password: %s', 'concordamos'), $link );
            $email_headers = array('Content-Type: text/html; charset=UTF-8');
            
            wp_mail($user_email, $email_subject, $email_message, $email_headers);
            
            return true;

        } catch( \Exception $e ) {
            return new \WP_Error('general_error', __("Error sending email. Please try again later.", 'concordamos'), array('status' => 500));
        } 

    }

    protected function save_token_transient( string $token_key_prefixed, object $user ) {
        $this->delete_old_tokens( $user->ID );
        return set_transient( $token_key_prefixed, $user->data->user_email, HOUR_IN_SECONDS / 2 );
    }


    public function generate_token($request) {
        $data = $request->get_json_params();

        $validate = $this->validate_generate_token( $data );
        if( $validate !== true ) {
            return $validate;
        }
        $data = $this->clear_data_generate_token( $data );
        // try to get user
        $user = get_user_by('email', $data['email']);
        if (is_wp_error($user)) {
            return new \WP_Error('user_not_found',  __( "Email not found. Please make sure you've entered the correct email address.", 'concordamos' ), array('status' => 401));
        }

        // generate token
        $token_key = $user->ID . '_' . $this->generate_token_key( $user->ID );
        $token_prefixed = User_Change_Pass::TRANSIENT_PREFIX . $token_key;

        // save transient
        $this->save_token_transient( $token_prefixed, $user );
        // send email
        $this->send_token_email( $token_key, $user->data->user_email );
        
        return array('message' => __('Link sent successfully! Please check your email.', 'concordamos'));
    }

    public function redirect_logged_user() {
        if( is_user_logged_in() && strstr(get_page_template(), 'template-login.php') ) {
            wp_redirect( home_url() );
            exit;
        }
    }

    public static function get_token( string $token_key ) {
        $token_key_prefixed = User_Change_Pass::TRANSIENT_PREFIX . $token_key;
        $transient = get_transient( sanitize_text_field( $token_key_prefixed ) );
        return $transient;

    }
    protected function validate_change_password( $data ) {

        if( is_user_logged_in() ) {
            return new \WP_Error('invalid_data', __("You're already logged in.", 'concordamos'), array('status' => 400));
        }
        if ( ! isset($data['password']) || empty( $data['password'])) {
            return new \WP_Error('invalid_data', __('Password field is empty.', 'concordamos'), array('status' => 400));
        }
        if ( ! isset( $data[ 'repeat_password'] ) || $data['password'] != $data['repeat_password'] ) {
            return new \WP_Error('invalid_data', __("Passwords fields don't match.", 'concordamos'), array('status' => 400));
        }
        if ( ! isset($data['token']) || empty( $data['token'])) {
            return new \WP_Error('invalid_data', __('Token is empty.', 'concordamos'), array('status' => 400));
        }
        if ( ! User_Change_Pass::get_token( $data[ 'token'] ) ) {
            return new \WP_Error('invalid_data', __("The link you've used is invalid or has expired. Please restart the password reset process to receive a new link.", 'concordamos'), array('status' => 400));
        }


        return true;
    }

    protected function clear_data_change_password( $data ) {
        $data['token'] = sanitize_text_field( $data['token'] );
        $data['password'] = sanitize_text_field( $data[ 'password'] );
        return $data;
    }

    public function change_password( $data ) {
        $validate = $this->validate_change_password( $data );
        if( $validate !== true ) {
            return $validate;
        }
        $data = $this->clear_data_change_password($data);
        
        try {

            $user = get_user_by('email', User_Change_Pass::get_token( $data['token'] ) );
            if( ! $user || is_wp_error( $user ) ) {
                return new \WP_Error('invalid_token', __("The link you've used is invalid or has expired. Please restart the password reset process to receive a new link.", 'concordamos'), array('status' => 400));
            }
            $this->delete_old_tokens($user->ID);
            wp_set_password( $data['password'], $user->ID );
            return array('message' => __('Password successfully changed! You can now log in with your new password.', 'concordamos'));

        } catch( \Exception $e ) {
            return new \WP_Error('general', __("An unknown error occurred while attempting to change your password. Please try again later.", 'concordamos'), array('status' => 400));
        }
    }

}

new User_Change_Pass();
