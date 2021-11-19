<?php

get_header();

?>

<main class="main">

    <section class="collection__header" style="background: url(<?php echo get_template_directory_uri().'/assets/img/collection-title.png'?>) top center/cover">
        <div class="page__title">
            <div class="container">
                <div class="page__title-wrapper">
                    <h1><?php woocommerce_page_title() ?></h1>
                </div>
            </div>
        </div>
    </section>
    <section class="model__list p-100">
        <div class="container">
            <div class="model__top">
                <div class="model__count">
                    <p>Models in the collection:</p>
                    <span><?php echo wc_get_loop_prop('total')?></span>
                </div>
                <div class="model__sort">
                    <?php do_action( 'woocommerce_before_shop_loop' );?>
                </div>
            </div>
            <div class="model__list">

                <?php
                if (wc_get_loop_prop('total')) {
                    while (have_posts()) {
                        the_post();

                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action('woocommerce_shop_loop');

                        wc_get_template_part('content', 'product');
                    }
                } ?>
            </div>
        </div>
    </section>
</main>
<?php
get_footer( 'shop' );


