<?php
namespace Concordamos;

class User_Register
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_endpoint'));
        add_action('template_redirect', array($this, 'redirect_logged_user'));
    }
    public function register_endpoint()
    {
        register_rest_route('concordamos/user/v1', '/register', array(
                'methods' => 'POST',
                'callback' => array($this, 'register'),
            )
        );
    }

    protected function validate($data)
    {
        $errors = [];
        if (is_user_logged_in()) {
            $errors['general'][] =  __("You're already logged in. No need to log in again.", 'concordamos');
        }

        if( ! isset( $data[ 'terms'] ) || empty( $data[ 'terms'] ) ) {
            $errors[ 'general' ][] = __("You must accept the Privacy Policy and Terms of Use", 'concordamos');
        }

        if( ! isset( $data[ 'image-terms'] ) || empty( $data[ 'image-terms'] ) ) {
            $errors[ 'general' ][] = __("You must accept the Image Terms of Use", 'concordamos');
        }

        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'][] = __('Username field is empty.', 'concordamos');
        }

        if (!isset($data['email']) || empty($data['email'])) {
            $errors['email'][] = __('Email field is empty.', 'concordamos');
        } else {
            if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'][] = __('Email field is invalid.', 'concordamos');
            } else {
                $user_exist = get_user_by('email', $data['email']);
                if( $user_exist && ! is_wp_error( $user_exist ) && $user_exist !== false ) {
                    $errors['email'][] = __('This email address is already registered. Please use a different email or log in.', 'concordamos');
                }
            }
        }

        if ((!isset($data['password']) || empty($data['password'])) || (!isset($data['repeat-password']) || empty($data['repeat-password']))) {
            $errors['password'][] = __('Password field is empty.', 'concordamos');
        } else {
            if ($data['password'] != $data['repeat-password']) {
                $errors['repeat-password'][] = __("Passwords fields don't match.", 'concordamos');
            }
        }

        return $errors;
    }

    protected function clear_data($data)
    {
        $data['name'] = sanitize_text_field($data['name']);
        $data['password'] = sanitize_text_field($data['password']);
        $data[ 'email' ] = sanitize_email( $data['email'] );

        return $data;
    }

    public function register($request)
    {
        $data = $request->get_params();

        $validate = $this->validate($data);
        
        if (is_array($validate) && ! empty( $validate )) {
            $response = new \WP_REST_Response($validate);
            $response->set_status(400);
            return $response;
        }

        $data = $this->clear_data($data);
        $username = $this->generate_username( $data['name'] );
        $user_id = wp_insert_user( [
            'user_login' => $username,
            'user_pass' => $data[ 'password' ],
            'user_email' => $data[ 'email' ],
            'display_name' => $data['name']
        ]);

        if( ! $user_id || is_wp_error( $user_id ) ) {
            $response = new \WP_REST_Response($user_id->get_error_message());
            $response->set_status(400);
            return $response;
        }

        $user = wp_authenticate($username, $data['password']);
        if (!is_wp_error($user) && $user->ID === $user_id) {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            return array('message' => __('Account created successfully.', 'concordamos'));
        } else {
            $response = new \WP_REST_Response(__('Authentication failed.', 'concordamos'));
            $response->set_status(500);
            return $response;
        }
    
    }

    public function redirect_logged_user()
    {
        if (is_user_logged_in() && strstr(get_page_template(), 'template-create-user.php')) {
            wp_redirect(home_url());
            exit;
        }
    }

    
    protected function generate_username( $full_name ) {
        $username = strtolower(str_replace(' ', '', $full_name));
        if (username_exists($username)) {
            $suffix = 1;
            while (username_exists($username . $suffix)) {
                $suffix++;
            }
            $username = $username . $suffix;
        }
        return $username;
    }
}

new User_Register();
