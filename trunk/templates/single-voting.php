<?php
get_header();

/**
 * Sample URL: http://localhost/voting/v-67464b987448ca64/u-98564b987448ca50
 */

$post_id = get_the_ID();
$raw_post_meta = get_post_meta( $post_id );

$voting_id = get_post_field( 'post_name', $post_id );
$options = Concordamos\get_options_by_voting( $post_id );

$author_id = get_post_field( 'post_author', $post_id );
$voting_author = get_the_author_meta( 'display_name', $author_id );

$date_start = DateTime::createFromFormat( 'd/m/Y \a\t H:i:s', $raw_post_meta['date_start'][0] );
$date_start_atom = $date_start->format( DateTime::ATOM ); // ISO 8601 format

$date_end = DateTime::createFromFormat( 'd/m/Y \a\t H:i:s', $raw_post_meta['date_end'][0] );
$date_end_atom = $date_end->format( DateTime::ATOM ); // ISO 8601 format

$date_current = new DateTime();

$date_start_class = ( $date_start >= $date_current ) ? 'date date-start started' : 'date date-start';
$date_end_class = ( $date_end >= $date_current ) ? 'date date-end finished' : 'date date-end';
?>

	<div class="voting-header">
		<div class="container">
			<div class="info">
				<span class="voting-breadcrumb">
					<?php _e( 'Voting', 'concordamos-textdomain' ); ?>
				</span>

				<h1><?php echo apply_filters( 'the_content', $raw_post_meta['voting_name'][0] ); ?></h1>

				<?php if ( $raw_post_meta['description'][0] ) : ?>
					<?php echo apply_filters( 'the_content', $raw_post_meta['description'][0] ); ?>
				<?php endif; ?>

				<div class="meta">
					<?php echo Concordamos\get_html_terms( $post_id, 'tag' ); ?>
					<span class="author"><?php _e( 'Criado por:' ); ?> <?php echo $voting_author; ?></span>
				</div>
			</div>
			<div class="dates">
				<div class="<?php echo $date_start_class; ?>">
					<div class="icon">1</div>
					<h3><?php _e( 'Start', 'concordamos-textdomain' ); ?></h3>
					<span class="date"><?php echo /* @todo use WP format */ $date_start->format( 'd/m/Y' ); ?></span>
					<span class="time"><?php echo /* @todo use WP format */ $date_start->format( 'h\hi' ); ?></span>
				</div>
				<div class="<?php echo $date_end_class; ?>">
					<div class="icon">2</div>
					<h3><?php _e( 'End', 'concordamos-textdomain' ); ?></h3>
					<span class="date"><?php echo /* @todo use WP format */ $date_end->format( 'd/m/Y' ); ?></span>
					<span class="time"><?php echo /* @todo use WP format */ $date_end->format( 'h\hi' ); ?></span>
				</div>
			</div>

		</div>
	</div>

	<div class="voting-content">
		<div class="container">
 			<div class="render" id="concordamos-voting-single"
				data-credits_voter="<?php echo $raw_post_meta['credits_voter'][0]; ?>"
				data-options="<?php echo htmlspecialchars( json_encode( $options ) , ENT_QUOTES, 'UTF-8' ); ?>"
				data-date_end="<?php echo htmlspecialchars( $date_end_atom, ENT_QUOTES, 'UTF-8' ); ?>"
			></div>
		</div>
	</div>

<?php get_footer(); ?>
