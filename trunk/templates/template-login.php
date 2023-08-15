<?php
$site_title = get_bloginfo('name');
$register_url = get_permalink(concordamos\get_page_by_template('concordamos/template-create-user.php'));
?>
<!DOCTYPE html>
<html>

<head>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <section class="concordamos-balloon">
        <div class="balloon-content">
            <div class="arrow">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/arrow1.svg'; ?>" alt="voltar">
            </div>

            <p class="balloon-subtitle">
                <?php _e('A way to vote for consensus', 'concordamos'); ?>
            </p>
            <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/login-logo.png'; ?>"
                alt="<?php _e('Login logo', 'concordamos'); ?>'">
            <p class="balloon-subtitle with">
                <?php _e('With', 'concordamos'); ?>
                <?php echo $site_title ?>
            </p>
        </div>
    </section>
    <form id="login-form" class="login-form">
        <div class="container">
            <div class="email-field">
                <label>
                    <span>
                        <?php _e('E-mail', 'concordamos'); ?>
                    </span>
                    <input name="email" type="text">
                </label>
            </div>
            <div class="login-password-field">
                <label>
                    <span>
                        <?php _e('Password', 'concordamos'); ?>
                    </span>
                    <div class="password-input-box">
                        <button class="show-password" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M12 9C11.2044 9 10.4413 9.31607 9.87868 9.87868C9.31607 10.4413 9 11.2044 9 12C9 12.7956 9.31607 13.5587 9.87868 14.1213C10.4413 14.6839 11.2044 15 12 15C12.7956 15 13.5587 14.6839 14.1213 14.1213C14.6839 13.5587 15 12.7956 15 12C15 11.2044 14.6839 10.4413 14.1213 9.87868C13.5587 9.31607 12.7956 9 12 9ZM12 17C10.6739 17 9.40215 16.4732 8.46447 15.5355C7.52678 14.5979 7 13.3261 7 12C7 10.6739 7.52678 9.40215 8.46447 8.46447C9.40215 7.52678 10.6739 7 12 7C13.3261 7 14.5979 7.52678 15.5355 8.46447C16.4732 9.40215 17 10.6739 17 12C17 13.3261 16.4732 14.5979 15.5355 15.5355C14.5979 16.4732 13.3261 17 12 17ZM12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5Z"
                                    fill="#666666" />
                            </svg>
                        </button>
                        <input name="password" type="password">
                    </div>
                </label>
                <a href="#">
                    <?php _e('I forgot my password', 'concordamos'); ?>
                </a>
            </div>
            <div class="login-button">
                <button class="login-submit" data-loading-text="<?php _e('Loading..', 'concordamos'); ?>" type="submit">
                    <?php _e("Let's go!", 'concordamos'); ?>
                </button>
                <p>
                    <?php _e("Don't have an account?", 'concordamos'); ?> <a href="<?php echo $register_url; ?>"><?php _e('Register here', 'concordamos'); ?></a>.
                </p>
            </div>
            <div id="login-message"></div>
            <div class="its-logo">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/its-login-logo.png'; ?>"
                    alt="<?php _e('Logo ITS'); ?>">
            </div>

        </div>
    </form>
    <?php wp_footer(); ?>
</body>

</html>