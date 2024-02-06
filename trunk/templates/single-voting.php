<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/**
 * Sample URL: http://localhost/voting/v-67464b987448ca64/u-98564b987448ca50
 */

$single_id     = get_the_ID();
$raw_post_meta = get_post_meta( $single_id );

$voting_id = get_post_field( 'post_name', $single_id );
$options   = Concordamos\get_options_by_voting( $single_id );

$author_id     = get_post_field( 'post_author', $single_id );
$voting_author = get_the_author_meta( 'display_name', $author_id );

$date_format = _x( 'M d, Y', 'date', 'concordamos' );
$time_format = _x( 'H\hi', 'time', 'concordamos' );

$date_start = $raw_post_meta['date_start'][0];
$date_end   = $raw_post_meta['date_end'][0];

$date_start_class = ( Concordamos\is_future_date( Concordamos\format_datetime( $date_start ) ) ) ? 'date date-start' : 'date date-start started';
$date_end_class   = ( Concordamos\is_future_date( Concordamos\format_datetime( $date_end ) ) ) ? 'date date-end' : 'date date-end finished';

$is_panel      = get_query_var( 'panel' );
$voting_closed = Concordamos\is_voting_closed( $single_id );

$results_end = ( isset( $raw_post_meta['results_end'] ) && isset( $raw_post_meta['results_end'][0] ) ) ? $raw_post_meta['results_end'][0] : 'no';
?>
<div>
	<div class="voting-header">
		<div class="container">
		<?php require 'part/navbar-mobile.php'; ?>

			<div class="info">
				<span class="voting-breadcrumb">
					<?php esc_html_e( 'Voting', 'concordamos' ); ?>
				</span>

				<h1 class="voting-title"><?php echo wp_kses( apply_filters( 'the_title', $raw_post_meta['voting_name'][0] ), 'data' ); ?></h1>

				<?php if ( $raw_post_meta['description'][0] ) : ?>
					<?php echo wp_kses_post( apply_filters( 'the_content', $raw_post_meta['description'][0] ) ); ?>
				<?php endif; ?>

				<div class="meta">
					<?php if ( ! $is_panel ) : ?>
						<?php echo wp_kses_post( Concordamos\get_html_terms( $single_id, 'tag' ) ); ?>
						<span class="author"><?php esc_html_e( 'Created by:', 'concordamos' ); ?> <?php echo esc_html( $voting_author ); ?></span>
					<?php else : ?>
						<?php if ( Concordamos\is_voting_owner( $single_id ) ) : ?>
							<div id="concordamos-voting-admin"></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="dates">
				<div class="<?php echo esc_attr( $date_start_class ); ?>">
					<div class="icon">1</div>
					<h3><?php esc_html_e( 'Start', 'concordamos' ); ?></h3>
					<time class="date" datetime="<?php echo esc_attr( Concordamos\format_datetime( $date_start, 'Y-m-d' ) ) ?>">
						<?php echo esc_html( Concordamos\format_datetime( $date_start, $date_format ) ); ?>
					</time>
					<time class="date" datetime="<?php echo esc_attr( Concordamos\format_datetime( $date_start, 'H:i' ) ) ?>">
						<?php echo esc_html( Concordamos\format_datetime( $date_start, $time_format ) ); ?>
					</time>
					<!-- <span class="start-edit">Editar</span> -->
				</div>
				<div class="<?php echo esc_attr( $date_end_class ); ?>">
					<div class="icon">2</div>
					<h3><?php esc_html_e( 'End', 'concordamos' ); ?></h3>
					<time class="date" datetime="<?php echo esc_attr( Concordamos\format_datetime( $date_end, 'Y-m-d' ) ) ?>">
						<?php echo esc_html( Concordamos\format_datetime( $date_end, $date_format ) ); ?>
					</time>
					<time class="date" datetime="<?php echo esc_attr( Concordamos\format_datetime( $date_start, 'H:i' ) ) ?>">
						<?php echo esc_html( Concordamos\format_datetime( $date_end, $time_format ) ); ?>
					</time>
					<!-- <span class="end-edit">Editar</span> -->
				</div>
			</div>
		</div>
	</div>

	<?php $content_class = $is_panel ? 'voting-content is-panel' : 'voting-content'; ?>

	<div class="<?php echo esc_attr( $content_class ); ?>">
		<div class="container">
			<div class="render" id="concordamos-voting-single"
				data-credits_voter="<?php echo esc_attr( $raw_post_meta['credits_voter'][0] ); ?>"
				data-options="<?php echo esc_attr( wp_json_encode( $options ) ); ?>"
				data-date_start="<?php echo esc_attr( $date_start ); ?>"
				data-date_end="<?php echo esc_attr( $date_end ); ?>"
				data-logged="<?php echo esc_attr( is_user_logged_in() ); ?>"
				data-results_end="<?php echo esc_attr( $results_end ); ?>"
				data-voting_closed="<?php echo esc_attr( $voting_closed ); ?>"
				data-is_panel="<?php echo esc_attr( $is_panel ); ?>"
			></div>
		</div>
	</div>

</div>

<?php get_footer(); ?>
