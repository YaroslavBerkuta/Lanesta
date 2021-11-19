<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lanesta
 */
$categories = get_categories( [
    'taxonomy'     => 'category',
    'type'         => 'post',
    'child_of'     => 0,
    'parent'       => '',
    'orderby'      => 'name',
    'order'        => 'ASC',
    'hide_empty'   => 1,
    'hierarchical' => 1,
    'exclude'      => '',
    'include'      => '',
    'number'       => 0,
    'pad_counts'   => false,
    // полный список параметров смотрите в описании функции http://wp-kama.ru/function/get_terms
] );
?>


<div class="post__flex" id="<?php the_ID(); ?>">
    <div class="post__block">
        <div class="post__media">
            <? if( has_post_thumbnail( $post_id ) ): ?>
                <img alt="blog-img"src="<?=wp_get_attachment_url( get_post_thumbnail_id() ); ?>" width="100%" height="100%">
            <? endif; ?>
        </div>
    </div>
    <div class="post__block">
        <div class="post__info">
            <span class="post__category"><?php the_category( ',', 'single ', $post->ID ); ?></span>
            <h4><?php the_title() ?></h4>
            <p><?php echo get_the_date(); ?></p>
        </div>
        <div class="post__content">
            <p><?php the_content(); ?></p>
        </div>
    </div>
</div>