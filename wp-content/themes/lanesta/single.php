<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package lanesta
 */

get_header();
?>

    <main class="main">

        <section class="p-100">
            <div class="container">
                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/content', get_post_type() );
                endwhile; // End of the loop.
                ?>
            </div>
        </section>
        <section class="p-100">
            <div class="container">
                <h2>RELATED POSTS</h2>
                <div class="relate__flex">
                <?php // необязательно, но в некоторых случаях без этого не обойтись
                global $post;

                // тут можно указать post_tag (подборка постов по схожим меткам) или даже массив array('category', 'post_tag') - подборка и по меткам и по категориям
                $related_tax = 'category';

                // получаем ID всех элементов (категорий, меток или таксономий), к которым принадлежит текущий пост
                $cats_tags_or_taxes = wp_get_object_terms( $post->ID, $related_tax, array( 'fields' => 'ids' ) );

                // массив параметров для WP_Query
                $args = array(
                    'posts_per_page' => 4, // сколько похожих постов нужно вывести,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $related_tax,
                            'field' => 'id',
                            'include_children' => false, // нужно ли включать посты дочерних рубрик
                            'terms' => $cats_tags_or_taxes,
                            'operator' => 'IN' // если пост принадлежит хотя бы одной рубрике текущего поста, он будет отображаться в похожих записях, укажите значение AND и тогда похожие посты будут только те, которые принадлежат каждой рубрике текущего поста
                        )
                    )
                );
                $misha_query = new WP_Query( $args );

                // если посты, удовлетворяющие нашим условиям, найдены
                if( $misha_query->have_posts() ) :

                    // запускаем цикл
                    while( $misha_query->have_posts() ) : $misha_query->the_post();
                        // в данном случае посты выводятся просто в виде ссылок
                        echo '<a class="relate__block" href="' . get_permalink( $misha_query->post->ID ) . '"><div class="relate__img"><img src="'.wp_get_attachment_url( get_post_thumbnail_id() ).'" width="100%" height="500px" style="object-fit: cover" /></div><div class="post__info"><h4>' . $misha_query->post->post_title . '</h4><p>'.get_the_date().'</p></div></a>';
                    endwhile;
                endif;

                // не забудьте про эту функцию, её отсутствие может повлиять на другие циклы на странице
                wp_reset_postdata();?>
                </div>
            </div>
        </section>

    </main><!-- #main -->

<?php

get_footer();
