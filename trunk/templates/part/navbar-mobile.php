<?php if(is_user_logged_in()) : ?>
	<nav class="navbar-mobile">
		<button class="btn-1">
			<span><img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/calendar-mob.svg'; ?>"></span>
			<p><?php _e('Create voting', 'concordamos') ?></p>
		</button>
		<button class="btn-2">
			<span><img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/votations-mob.svg'; ?>"></span>
			<p><?php _e('Votings', 'concordamos') ?></p>

		</button>
		<button class="btn-3">
			<span><img src="<?php echo CONCORDAMOS_PLUGIN_URL . 'assets/images/account-mob.svg'; ?>"></span>
			<p><?php _e('My account', 'concordamos') ?></p>
		</button>
	</nav>
<?php endif ?>