<?php

$login_url = get_permalink( concordamos\get_page_by_template('concordamos/template-login.php') );
$site_title = get_bloginfo('name');

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php the_title() ?>
    </title>
    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <section class="concordamos-balloon">
        <div class="balloon-content">
        <div class="arrow">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/arrow1.svg'; ?>" alt="voltar">
            </div>
            <p class="balloon-subtitle"><?php _e('A way to vote for consensus', 'concordamos'); ?></p>
            <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/login-logo.png'; ?>" alt="<?php _e('Concordamos Logo', 'concordamos'); ?>">
            <p class="balloon-subtitle with"><?php printf(__('With %s', 'concordamos'), $site_title); ?></p>
        </div>
    </section>
    <form class="register-form" id="register-form">
        <div class="container">
            <div class="name-field">
                <label>
                    <span>
                        <?php _e('Name', 'concordamos'); ?>
                    </span>
                    <input name="name" type="text">
                    <span class="errors" id="name-errors"></span>
                </label>
            </div>
            <div class="email-field">
                <label>
                    <span>
                        <?php _e('Email', 'concordamos'); ?>
                    </span>
                    <input name="email" type="text">
                    <span class="errors" id="email-errors"></span>
                </label>
            </div>
            <div class="register-password-field">
                <label>
                    <span>
                        <?php _e('Password', 'concordamos'); ?>
                    </span>
                    <button class="show-password" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 9C11.2044 9 10.4413 9.31607 9.87868 9.87868C9.31607 10.4413 9 11.2044 9 12C9 12.7956 9.31607 13.5587 9.87868 14.1213C10.4413 14.6839 11.2044 15 12 15C12.7956 15 13.5587 14.6839 14.1213 14.1213C14.6839 13.5587 15 12.7956 15 12C15 11.2044 14.6839 10.4413 14.1213 9.87868C13.5587 9.31607 12.7956 9 12 9ZM12 17C10.6739 17 9.40215 16.4732 8.46447 15.5355C7.52678 14.5979 7 13.3261 7 12C7 10.6739 7.52678 9.40215 8.46447 8.46447C9.40215 7.52678 10.6739 7 12 7C13.3261 7 14.5979 7.52678 15.5355 8.46447C16.4732 9.40215 17 10.6739 17 12C17 13.3261 16.4732 14.5979 15.5355 15.5355C14.5979 16.4732 13.3261 17 12 17ZM12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5Z"
                                fill="#666666" />
                        </svg>
                    </button>
                    <input name="password" type="password">
                    <span class="errors" id="password-errors"></span>
                </label>
                <label>
                    <span>
                        <?php _e('Repeat password', 'concordamos'); ?>
                    </span>
                    <input name="repeat-password" type="password">
                    <span class="errors" id="repeat-password-errors"></span>
                </label>
            </div>
            <div class="accept-checkbox-terms">
                <input type="checkbox" name="terms" />
                <label for="scales">
                    <?php _e('You accept the', 'concordamos'); ?> <a>
                        <?php _e('Privacy Policy', 'concordamos'); ?>
                    </a>
                    <?php _e('and', 'concordamos'); ?> <a>
                        <?php _e('Terms of Use', 'concordamos'); ?>
                    </a>
                </label>
            </div>
            <div class="accept-checkbox-image">
                <input type="checkbox" name="image-terms" />
                <label for="scales">
                    <?php _e('You accept the', 'concordamos'); ?> <a>
                        <?php _e('Image Terms of Use', 'concordamos'); ?>
                    </a>
                </label>
            </div>
            <div id="status"></div>
            <div class="register-button">
                <button class="register-submit" type="submit"
                    data-loading-text="<?php _e('Loading...', 'concordamos'); ?>"><?php _e("Let's go!", 'concordamos'); ?></button>
                <p>
                    <?php _e('Already have an account?', 'concordamos'); ?> <a href="<?php echo $login_url;?>">
                        <?php _e('Access here', 'concordamos'); ?>
                    </a>.
                </p>
                <img src="" alt="">
            </div>
            <div class="its-logo">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/its-login-logo.png'; ?>"
                    alt="<?php _e('ITS Logo', 'concordamos'); ?>">
            </div>
        </div>
    </form>
    <?php wp_footer(); ?>
</body>

</html>
