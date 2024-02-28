<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$slug    = sanitize_key( filter_input( INPUT_GET, 'voting_id' ) );
$voting  = Concordamos\get_post_by_slug( 'voting', $slug );
$options = Concordamos\get_options_by_voting( $voting->ID );

$locale = get_post_meta( $voting->ID, 'locale', true );
$locale = empty( $locale ) ? Concordamos\get_default_language() : $locale;

$voting_template = array(
	'locale'             => $locale,
	'voting_description' => $voting->post_content,
	'voting_id'          => $slug,
	'voting_name'        => $voting->post_title,
	'voting_options'     => $options,
);
?>
<div id="the-content"><?php the_content(); ?></div>
<div id="concordamos-translate-voting-form" data-template="<?php echo esc_attr( wp_json_encode( $voting_template ) ) ?>"></div>
<?php get_footer(); ?>
