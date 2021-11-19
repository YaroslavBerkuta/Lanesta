<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lanesta
 */
$size = array(470,470);

get_header();
?>

	<main class="main">
        <section class="p-100">
            <div class="container">
                <div class="blog__container">
                    <?php if ( have_posts() ) : ?>

                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) :
                            the_post();?>

                            <div class="blog__item" id="post-<?php the_ID(); ?>">
                                <a href="<?php the_permalink() ?>">
                                    <? if( has_post_thumbnail( $post_id ) ): ?>
                                            <img alt="blog-img"src="<?=wp_get_attachment_url( get_post_thumbnail_id() ); ?>" width="500px" height="500px" style="object-fit: cover">
                                    <? endif; ?>
                                    <div class="blog__category"><?php the_category( ',', 'single ', $post->ID ); ?></div>
                                    <h4><?php the_title() ?></h4>
                                    <p><?php echo get_the_date(); ?></p>
                                </a>
                            </div>
                        <?php
                        endwhile;

                        the_posts_navigation();

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                    ?>
                </div>

            </div>
        </section>
	</main><!-- #main -->

<?php

get_footer();
