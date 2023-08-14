<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php the_title() ?></title>
    <?php wp_head(); ?>
    
<?php

$site_title = get_bloginfo( 'name' );

?>
    
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <section class="concordamos-balloon">
        <div class="balloon-content">
            <p class="balloon-subtitle"><?php _e('A way to vote for consensus', 'concordamos'); ?></p>
            <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/login-logo.png'; ?>" alt="<?php _e('Login Logo', 'concordamos'); ?>">
            <p class="balloon-subtitle with"><?php printf(__('With %s', 'concordamos'), $site_title); ?></p>
        </div>
    </section>
    <form class="register-form" id="register-form">
        <div class="container">
            <div class="name-field">
                <label>
                    <span><?php _e('Name', 'concordamos'); ?></span>
                    <input name="name" type="text">
                    <span class="errors" id="name-errors"></span>
                </label>
            </div>
            <div class="email-field">
                <label>
                    <span><?php _e('E-mail', 'concordamos'); ?></span>
                    <input name="email" type="text">
                    <span class="errors" id="email-errors"></span>
                </label>
            </div>
            <div class="register-password-field">
                <label>
                    <span><?php _e('Password', 'concordamos'); ?></span>
                    <input name="password" type="text">
                    <span class="errors" id="password-errors"></span>
                </label>
                <label>
                    <span><?php _e('Repeat password', 'concordamos'); ?></span>
                    <input name="repeat-password" type="text">
                    <span class="errors" id="repeat-password-errors"></span>
                </label>
            </div>
            <div class="accept-checkbox-terms">
                <input type="checkbox" name="terms"/>
                <label for="scales"><?php _e('You accept the', 'concordamos'); ?> <a><?php _e('Privacy Policy', 'concordamos'); ?></a> <?php _e('and', 'concordamos'); ?> <a><?php _e('Terms of Use', 'concordamos'); ?></a></label>
            </div>
            <div class="accept-checkbox-image">
                <input type="checkbox" name="image-terms"/>
                <label for="scales"><?php _e('You accept the', 'concordamos'); ?> <a><?php _e('Image Terms of Use', 'concordamos'); ?></a></label>
            </div>
            <div id="status"></div>
            <div class="register-button">
                <button class="register-submit" type="submit"><?php _e("Let's go!", 'concordamos'); ?></button>
                <p><?php _e('Already have an account?', 'concordamos'); ?> <a href="#"><?php _e('Access here', 'concordamos'); ?></a>.</p>
                <img src="" alt="">
            </div>
            <div class="its-logo">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/its-login-logo.png'; ?>" alt="<?php _e('ITS Login Logo', 'concordamos'); ?>">
            </div>
        </div>
    </form>
    <?php wp_footer(); ?>
</body>
</html>