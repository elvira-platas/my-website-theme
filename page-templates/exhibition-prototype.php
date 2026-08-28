<?php
/**
 * Template Name: Exhibition Prototype
 * Template Post Type: page
 *
 * A quiet, sequential space for a small, deliberately ordered group of images.
 *
 * @package Kilka
 */

remove_action( 'kilka_header_style', 'kilka_header_style_1' );
remove_action( 'kilka_footer_style', 'kilka_footer_style_1' );

/**
 * Collect attachment IDs from nested image and gallery blocks.
 *
 * @param array $blocks Parsed Gutenberg blocks.
 * @return array
 */
if ( ! function_exists( 'kilka_exhibition_collect_image_ids' ) ) {
	function kilka_exhibition_collect_image_ids( $blocks ) {
		$image_ids = array();

		foreach ( $blocks as $block ) {
			if ( 'core/image' === $block['blockName'] && ! empty( $block['attrs']['id'] ) ) {
				$image_ids[] = absint( $block['attrs']['id'] );
			}

			if ( 'core/gallery' === $block['blockName'] && ! empty( $block['attrs']['ids'] ) ) {
				$image_ids = array_merge( $image_ids, array_map( 'absint', $block['attrs']['ids'] ) );
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$image_ids = array_merge( $image_ids, kilka_exhibition_collect_image_ids( $block['innerBlocks'] ) );
			}
		}

		return $image_ids;
	}
}

get_header();

while ( have_posts() ) :
	the_post();

	$image_ids = get_post_meta( get_the_ID(), 'kilka_exhibition_image_ids', true );

	if ( is_string( $image_ids ) ) {
		$image_ids = preg_split( '/\s*,\s*/', $image_ids );
	}

	if ( ! is_array( $image_ids ) || empty( $image_ids ) ) {
		$image_ids = kilka_exhibition_collect_image_ids( parse_blocks( get_the_content() ) );
	}

	$image_ids = array_values( array_unique( array_filter( array_map( 'absint', $image_ids ) ) ) );
	$layouts   = array( 'threshold', 'right', 'wide', 'small', 'left' );
	$works     = array();
	$schema    = array();
	$total     = count( $image_ids );
	$creator_name     = get_post_meta( get_the_ID(), 'kilka_exhibition_creator', true );
	$copyright_notice = get_post_meta( get_the_ID(), 'kilka_exhibition_copyright_notice', true );

	foreach ( $image_ids as $image_id ) {
		$image = get_post( $image_id );

		if ( ! $image || 'attachment' !== $image->post_type || 0 !== strpos( (string) get_post_mime_type( $image ), 'image/' ) ) {
			continue;
		}

		$title       = get_the_title( $image );
		$caption     = wp_get_attachment_caption( $image_id );
		$alt_text    = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		$description = $alt_text ? $alt_text : $caption;
		$description = $description ? $description : $title;
		$image_url   = wp_get_attachment_image_url( $image_id, 'full' );
		$thumb_url   = wp_get_attachment_image_url( $image_id, 'large' );

		$works[] = array(
			'id'          => $image_id,
			'title'       => $title,
			'description' => $description,
		);

		$image_schema = array(
			'@type'        => 'ImageObject',
			'name'         => $title,
			'description'  => wp_strip_all_tags( $description ),
			'contentUrl'   => $image_url,
			'thumbnailUrl' => $thumb_url,
		);

		if ( $creator_name ) {
			$image_schema['creator'] = array(
				'@type' => 'Person',
				'name'  => $creator_name,
			);
		}

		if ( $copyright_notice ) {
			$image_schema['copyrightNotice'] = $copyright_notice;
		}

		$schema[] = array_filter( $image_schema );
	}

	$total = count( $works );
	?>
	<main id="content" class="kilka-exhibition">
		<h1 class="screen-reader-text"><?php the_title(); ?></h1>

		<a class="kilka-exhibition__home" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Back to site', 'kilka' ); ?>">
			<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
		</a>
		<button class="kilka-exhibition__information-toggle" type="button" aria-label="<?php esc_attr_e( 'About this exhibition', 'kilka' ); ?>" aria-controls="kilka-exhibition-information" aria-expanded="false">i</button>

		<section class="kilka-exhibition__sequence" aria-label="<?php esc_attr_e( 'Exhibition', 'kilka' ); ?>">
			<?php foreach ( $works as $index => $work ) : ?>
				<article id="work-<?php echo esc_attr( $index + 1 ); ?>" class="kilka-exhibition__piece kilka-exhibition__piece--<?php echo esc_attr( $layouts[ $index % count( $layouts ) ] ); ?>">
					<figure class="kilka-exhibition__frame">
						<?php
						echo wp_get_attachment_image(
							$work['id'],
							'full',
							false,
							array(
								'class'         => 'kilka-exhibition__image',
								'loading'       => 0 === $index ? 'eager' : 'lazy',
								'fetchpriority' => 0 === $index ? 'high' : 'auto',
								'sizes'         => '(max-width: 767px) calc(100vw - 40px), 88vw',
							)
						);
						?>
						<figcaption class="screen-reader-text">
							<?php
							echo esc_html(
								sprintf(
									/* translators: 1: current image number, 2: total image count, 3: image description. */
									__( 'Image %1$s of %2$s: %3$s', 'kilka' ),
									$index + 1,
									$total,
									$work['description']
								)
							);
							?>
						</figcaption>
					</figure>
				</article>

				<?php if ( $index + 1 < $total ) : ?>
					<div class="kilka-exhibition__interval<?php echo 1 === $index ? ' kilka-exhibition__interval--long' : ''; ?>" aria-hidden="true"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</section>

		<aside id="kilka-exhibition-information" class="kilka-exhibition__information" role="dialog" aria-modal="true" aria-labelledby="kilka-exhibition-information-title" hidden>
			<div class="kilka-exhibition__information-panel">
				<button class="kilka-exhibition__information-close" type="button" aria-label="<?php esc_attr_e( 'Close information', 'kilka' ); ?>">&times;</button>
				<h2 id="kilka-exhibition-information-title" class="kilka-exhibition__information-title"><?php the_title(); ?></h2>
				<?php if ( has_excerpt() ) : ?>
					<div class="kilka-exhibition__information-note"><?php echo wp_kses_post( wpautop( get_the_excerpt() ) ); ?></div>
				<?php endif; ?>

				<?php if ( $works ) : ?>
					<h3 class="screen-reader-text"><?php esc_html_e( 'Works in this prototype', 'kilka' ); ?></h3>
					<ol class="kilka-exhibition__works">
						<?php foreach ( $works as $work ) : ?>
							<li><?php echo esc_html( $work['description'] ); ?></li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
			</div>
		</aside>

		<?php
		$collection_schema = array(
			'@context'           => 'https://schema.org',
			'@type'              => 'CollectionPage',
			'name'               => get_the_title(),
			'url'                => get_permalink(),
			'primaryImageOfPage' => ! empty( $schema ) ? $schema[0] : null,
			'hasPart'            => $schema,
		);
		?>
		<script type="application/ld+json"><?php echo wp_json_encode( array_filter( $collection_schema ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
	</main>
	<?php
endwhile;

get_footer();
