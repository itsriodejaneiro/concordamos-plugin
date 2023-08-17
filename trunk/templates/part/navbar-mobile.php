<?php
namespace Concordamos;

if(is_user_logged_in()) : ?>
	<nav class="navbar-mobile">
		<a class="btn-1" href="<?php echo get_permalink( get_voting_page() ) ?>">
			<span><img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/calendar-mob.svg'; ?>"></span>
			<p><?php _e('Create voting', 'concordamos') ?></p>
		</a>
		<a class="btn-2" href="<?php echo get_post_type_archive_link( 'voting' ) ?>">
			<span><img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/votations-mob.svg'; ?>"></span>
			<p><?php _e('Votings', 'concordamos') ?></p>

		</a>
		<a class="btn-3" href="<?php echo get_permalink( get_page_by_template( 'concordamos/template-my-account.php' ) ) ?>">
			<span><img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/account-mob.svg'; ?>"></span>
			<p><?php _e('My account', 'concordamos') ?></p>
		</a>
	</nav>
<?php endif ?>
