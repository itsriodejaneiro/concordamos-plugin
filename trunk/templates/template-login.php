<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php the_title() ?></title>
    
<?php

$site_title = get_bloginfo( 'name' );

?>
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
    <section class="concordamos-balloon">
        <div class="balloon-content">
        <div class="arrow">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/arrow1.svg'; ?>" alt="voltar">
            </div>

            <p class="balloon-subtitle"><?php _e('A way to vote for consensus', 'concordamos' );?></p>
            <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/login-logo.png'; ?>" alt="<?php _e('Login logo', 'concordamos' );?>'">
            <p class="balloon-subtitle with"><?php _e('With', 'concordamos');?>
                <?php echo $site_title ?>
            </p>
        </div>
    </section>
    <section class="login-form">
        <div class="container">
            <div class="email-field">
                <label>
                    <span>E-mail</span>
                    <input name="email" type="text">
                </label>
            </div>
            <div class="login-password-field">
                <label>
                    <span>Password</span>
                    <input name="password" type="text">
                </label>
                <a href="#">I forgot my password</a>
            </div>
            <div class="login-button">
                <button class="login-submit" type="submit">Let's go!</button>
                <p>Don't have an account? <a href="/cadastro-de-usuario">Register here</a>.</p> 
            </div>
            <div class="its-logo">
                <img  src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/its-login-logo.png'; ?>" alt="Logo its Login">
            </div>
           
        </div>
    </section>
    <?php wp_footer(); ?>
</body>
</html>
