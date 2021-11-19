


<?php

global $product;
$term = get_the_terms($product->get_id(), 'product_cat');
$loop = new WP_Query( array(
    'post_type' => 'product',
));

?>

    <a href="<?php the_permalink(); ?>">
        <img src="<?=wp_get_attachment_url( get_post_thumbnail_id() ); ?>" alt="">
        <h2><?php echo $term[0]->name ?></h2>
        <h3><?php the_title(); ?></h3>
    </a>


