<?php
/**
 * Template Name: Video Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
?>

    <main class="main">
        <section class="p-100">
            <div class="container">
                <div class="video__slider">
                    <div class="video__wrapper">

                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php
get_footer();
