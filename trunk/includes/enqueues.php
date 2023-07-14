<?php

namespace Concordamos;

function enqueue_scripts() {
	wp_enqueue_style( 'concordamos-admin', CONCORDAMOS_PLUGIN_URL . 'build/css/admin.css', [], CONCORDAMOS_PLUGIN_VERSION );
}

add_action( 'admin_enqueue_scripts', 'Concordamos\enqueue_scripts' );
