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

$date_start = $raw_post_meta['date_start'][0];
$date_end = $raw_post_meta['date_end'][0];

$date_start_class = ( Concordamos\is_future_date( Concordamos\format_timestamp_date( $date_start ) ) ) ? 'date date-start' : 'date date-start started';
$date_end_class = ( Concordamos\is_future_date( Concordamos\format_timestamp_date( $date_end ) ) ) ? 'date date-end' : 'date date-end finished';

$is_panel = get_query_var( 'panel' );
$voting_closed = Concordamos\is_voting_closed( $post_id );

$results_end = ( isset( $raw_post_meta['results_end'] ) && isset( $raw_post_meta['results_end'][0] ) ) ? $raw_post_meta['results_end'][0] : 'no';
?>
<div>
	<div class="voting-header">
		<div class="container">
		<?php require 'part/navbar-mobile.php' ?>

			<div class="info">
				<span class="voting-breadcrumb">
					<?php _e( 'Voting', 'concordamos' ); ?>
				</span>

				<h1 class="voting-title"><?php echo apply_filters( 'the_title', $raw_post_meta['voting_name'][0] ); ?></h1>

				<?php if ( $raw_post_meta['description'][0] ) : ?>
					<?php echo apply_filters( 'the_content', $raw_post_meta['description'][0] ); ?>
				<?php endif; ?>

				<div class="meta">
					<?php if ( ! $is_panel ) : ?>
						<?php echo Concordamos\get_html_terms( $post_id, 'tag' ); ?>
						<span class="author"><?php _e( 'Created by:', 'concordamos' ); ?> <?php echo $voting_author; ?></span>
					<?php else : ?>
						<?php if ( Concordamos\is_voting_owner( $post_id ) ) : ?>
							<div id="concordamos-voting-admin"></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="dates">
				<div class="<?php echo $date_start_class; ?>">
					<div class="icon">1</div>
					<h3><?php _e( 'Start', 'concordamos' ); ?></h3>
					<span class="date"><?php echo /* @todo use WP format */ Concordamos\format_timestamp_date( $date_start, 'd/m/Y' ); ?></span>
					<span class="time"><?php echo /* @todo use WP format */ Concordamos\format_timestamp_date( $date_start, 'h\hi' ); ?></span>
					<!-- <span class="start-edit">Editar</span> -->
				</div>
				<div class="<?php echo $date_end_class; ?>">
					<div class="icon">2</div>
					<h3><?php _e( 'End', 'concordamos' ); ?></h3>
					<span class="date"><?php echo /* @todo use WP format */ Concordamos\format_timestamp_date( $date_end, 'd/m/Y' ); ?></span>
					<span class="time"><?php echo /* @todo use WP format */ Concordamos\format_timestamp_date( $date_end, 'h\hi' ); ?></span>
					<!-- <span class="end-edit">Editar</span> -->
				</div>
			</div>
		</div>
	</div>

	<?php $content_class = $is_panel ? "voting-content is-panel" : "voting-content"; ?>

	<div class="<?php echo $content_class; ?>">
		<div class="container">
 			<div class="render" id="concordamos-voting-single"
				data-credits_voter="<?php echo $raw_post_meta['credits_voter'][0]; ?>"
				data-options="<?php echo htmlspecialchars( json_encode( $options ) , ENT_QUOTES, 'UTF-8' ); ?>"
				data-date_start="<?php echo htmlspecialchars( $date_start ); ?>"
				data-date_end="<?php echo htmlspecialchars( $date_end ); ?>"
				data-logged="<?php echo is_user_logged_in(); ?>"
				data-results_end="<?php echo $results_end; ?>"
				data-voting_closed="<?php echo $voting_closed; ?>"
				data-is_panel=<?php echo $is_panel; ?>
			></div>
		</div>
	</div>

</div>

<?php get_footer(); ?>
