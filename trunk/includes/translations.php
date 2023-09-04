<?php

namespace Concordamos;

function localize_plugin() {
	load_plugin_textdomain( 'concordamos', false, basename( CONCORDAMOS_PLUGIN_PATH ) . '/languages/' );
}

add_action( 'init', 'Concordamos\localize_plugin', 0 );
