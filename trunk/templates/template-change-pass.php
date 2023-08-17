<?php
$site_title = get_bloginfo('name');
$register_url = get_permalink(concordamos\get_page_by_template('concordamos/template-create-user.php'));
$token = concordamos\get_change_password_token_status();
$change_pass_url = get_permalink(concordamos\get_page_by_template('concordamos/template-change-pass.php'));

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
                alt="<?php _e('Concordamos Logo', 'concordamos'); ?>'">
            <p class="balloon-subtitle with">
                <?php _e('With', 'concordamos'); ?>
                <?php echo $site_title ?>
            </p>
        </div>
    </section>
    <form id="change-password-form" class="change-password-form">
        <div class="container">
                <?php if( $token === false || 'invalid' === $token ) : ?>
                    <h1><?php _e('Reset Password', 'concordamos');?></h1>
                    <?php if( 'invalid' === $token ) : ?>
                        <p class="error-invalid"><?php _e('This link is invalid or already expired.', 'concordamos' );?></p>
                    <?php endif;?>
                    <p><?php _e('Enter your email and we will send instructions to reset your password', 'concordamos');?></p>
                    <div class="email-field">
                        <label>
                            <span><?php _e('Email', 'concordamos');?></span>
                            <input name="email" type="email">
                        </label>
                    </div>
                    </div>
                    <div class="change-password-button">
                        <button class="change-password-submit" data-loading-text="<?php _e('Loading...', 'concordamos'); ?>" type="submit">
                            <?php _e("Send link", 'concordamos'); ?>
                        </button>
                    </div>
                <?php else: ?>
                    <h1><?php _e('Insert new password', 'concordamos');?></h1>
                    <input name="token" type="hidden" value="<?php echo esc_attr( $_GET[ 'concordamos_change_pass_tk'] );?>">
                    <div class="password-field">
                        <label>
                            <span><?php _e('Password', 'concordamos');?></span>
                            <input name="password" type="password">
                        </label>
                    </div>
                    <div class="repeat-password-field">
                        <label>
                            <span><?php _e('Repeat password', 'concordamos');?></span>
                            <input name="repeat-password" type="password">
                        </label>
                    </div>
                    <div class="change-password-button">
                        <button class="change-password-submit" data-loading-text="<?php _e('Loading...', 'concordamos'); ?>" type="submit">
                            <?php _e("Confirm", 'concordamos'); ?>
                        </button>
                    </div>
                <?php endif;?>
            <div id="change-password-message"></div>
            <div class="its-logo">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/its-login-logo.png'; ?>"
                    alt="<?php _e('ITS Logo');?>">
            </div>
        </div>
    </form>
    <?php wp_footer(); ?>
</body>

</html>
