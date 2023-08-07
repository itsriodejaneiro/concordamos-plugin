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
            <p class="balloon-subtitle">A way to vote for consensus</p>
            <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/login-logo.png'; ?>" alt="Logo do Login">
            <p class="balloon-subtitle with">With <?php echo $site_title ?></p>
        </div>    
    </section>
    <section class="register-form">
        <div class="container">
            <div class="name-field">
                <label>
                    <span>Name</span>
                    <input name="name" type="text">
                </label>
            </div>
            <div class="email-field">
                <label>
                    <span>E-mail</span>
                    <input name="email" type="text">
                </label>
            </div>
            <div class="register-password-field">
                <label>
                    <span>Password</span>
                    <input name="password" type="text">
                </label>
                <label>
                    <span>Repeat password</span>
                    <input name="repeat-password" type="text">
                </label>
            </div>
            <div class="register-button">
                <button class="register-submit" type="submit">Let's go!</button>
                <p>Already have an account? <a href="#">Access here.</a></p>
                <img src="" alt="">   
            </div>
            <div class="register-terms">
                <p>By creating an account on we agree you accept the <a href="#">Privacy Policy</a> and <a href="#">Terms of Use</a></p> 
            </div>
            <div class="its-logo">
                <img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/its-login-logo.png'; ?>" alt="Logo its Login">
            </div>
        </div>
    </section>
    <?php wp_footer(); ?>
</body>
</html>
