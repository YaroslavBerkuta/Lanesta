<?php
/**
 * Template Name: Collections Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
?>

    <main class="main">
        <section class="collections">
            <div class="container">
                <div class="collection__flex">
                    <?php
                    $args = array(
                        'taxonomy' => 'product_cat',
                        'number' => 8,
                        'exclude' => '16, 19, 20',
                        'hide_empty' => false,
                    );

                    $product_categories = get_terms( $args );
                    $count = count($product_categories);
                    if ( $count > 0 ){
                        foreach ( $product_categories as $product_category ) {
                            $thumbnail_id = get_woocommerce_term_meta( $product_category->term_id, 'thumbnail_id', true );
                            $hoverImg = get_woocommerce_term_meta( $product_category->term_id, 'hover_img', true );
                            $year = get_woocommerce_term_meta( $product_category->term_id, 'collection_year', true );

                            $item = '<div class="collections__item">';
                            $item .= '<div class="collection__img"><img class="category-block__img" src="'.  wp_get_attachment_url( $thumbnail_id ) .'" alt=""></div><div class="collection__img-hover"><img src="'. wp_get_attachment_url( $hoverImg ) .'" alt=""></div>';
                            $item .= '<div class="collections__details"><span>'.$year.'</span><a href="' . get_term_link( $product_category ) . '" class="category-block__link">' . $product_category->name . '</a><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.2402 7.90668L27.3336 16L19.2402 24.0933" stroke="#686868" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.6665 16H27.1065" stroke="#686868" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg></div>';
                            $item .= '</div>';
                            echo $item;

                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>


<?php
get_footer();

