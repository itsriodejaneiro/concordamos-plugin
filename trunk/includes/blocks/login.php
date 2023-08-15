<?php if( is_user_logged_in() ) return;?>
<section class="concordamos-login-block login-form">

    <form id="login-form">
        <div class="container">
            <div class="email-field">
                <label>
                    <span>
                        <?php _e('E-mail', 'concordamos'); ?>
                    </span>
                    <input name="email" type="text"></input>
                </label>
            </div>
            <div class="login-password-field">
                <label>
                    <span>
                        <?php _e('Password', 'concordamos'); ?>
                    </span>
                    <input name="password" type="password"></input>
                </label>
                <a href="#">
                    <?php _e('I forgot my password', 'concordamos'); ?>
                </a>
            </div>
            <div class="login-button">
                <button class="login-submit" type="submit">
                    <?php _e("Let's go!", 'concordamos'); ?>
                </button>
                <p>
                    <?php _e("Don't have an account?", 'concordamos'); ?> <a href="<?php echo get_permalink( concordamos\get_page_by_template('concordamos/template-create-user.php') );?>">
                        <?php _e('Register here', 'concordamos'); ?>
                    </a>.
                </p>
            </div>
            <div id="login-message"></div>
        </div>
    </form>
</section>