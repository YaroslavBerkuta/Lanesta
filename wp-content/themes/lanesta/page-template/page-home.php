<?php
/**
 * Template Name: Home Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
?>

    <main class="main">
        <div id="pagepiling">
            <section class="slide-section bg-video-position">

                <?php the_content(); ?>
            </section>
                <?php
                $args = array(
                    'taxonomy' => 'product_cat',
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'hide_empty' => false
                );

                $product_categories = get_terms( $args );
                $count = count($product_categories);
                    for($i = 0; $i < $count; $i++ ){
                        $thumbnail_id = get_woocommerce_term_meta( $product_categories[$i]->term_id, 'thumbnail_id', true );
                        $year = get_woocommerce_term_meta( $product_categories[$i]->term_id, 'collection_year', true );

                            echo '<section class="slide-section collerction__preview"><div class="container"><div class="collection__preview-flex"><div class="collection__preview-img"><img src="'.wp_get_attachment_url( $thumbnail_id ).'" /></div><div class="collection__preview-info"><span>'.$year.'</span>
                            <h2>' . $product_categories[$i]->name . '</h2>
                            <div class="collection__preview-btn">
                                <a href="' . get_term_link( $product_categories[$i] ) . '">Discover the collection</a>
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.2402 7.90668L27.3336 16L19.2402 24.0933" stroke="#686868" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M4.6665 16H27.1065" stroke="#686868" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>        
                            </div></div></div></div></section>';

                    };
                ?>
            <section class="slide-section">
                <div class="partnership__preview">
                    <div class="container">
                        <div class="partnership__text">
                            <h3>Lanesta Partnership</h3>
                            <p>To become an official representative of TM Lanesta</p>
                        </div>
                        <div class="partnership__btn">
                            <a href="/partnership">Become a partner</a>
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M25.3335 4.66675L6.66683 23.3334" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M25.3335 18.3601V4.66675H11.6402" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="blog__container blog__slider">
                        <div class="blog__wrapper">
                            <?php
                            $args = array(
                                'post_type'      => 'post',
                                'posts_per_page' => 15,
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                            );
                            $q = new WP_Query($args);
                            ?>

                            <?php if ( $q->have_posts() ) : ?>
                                <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                                    <div class="blog__item">
                                        <a href="<?php the_permalink(); ?>">
                                            <? if( has_post_thumbnail( $post_id ) ): ?>
                                                <img alt="blog-img"src="<?=wp_get_attachment_url( get_post_thumbnail_id() ); ?>" style="object-fit: cover;" width="100%" height="500px" >
                                            <? endif; ?>
                                            <div class="blog__category"><?php the_category( ',', 'single ', $post->ID ); ?></div>
                                            <h4><?php the_title(); ?></h4>
                                            <p><?php echo get_the_date(); ?></p>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>

                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </section>
            <?php  get_template_part( 'template-parts/content', 'footer' ); ?>
        </div>
    </main>

<?php
get_footer();
