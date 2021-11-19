<?php

get_header();
the_post();
global $product;
$term = get_the_terms($product->get_id(), 'product_cat');
?>
<main class="main">
        <section class="model">
            <div class="container">
                <div class="model__flex">
                    <div class="model__left">
                        <div class="model__big">
                            <img src="<?php echo get_the_post_thumbnail_url()?>" alt="" width="670px" height="100%" style="object-fit: cover;">

                                <div class="video__model">
                                    <video src="<?php the_field('video') ?>"></video>
                                </div>
                                <div class="video__btn">
                                    <img src="<?php echo get_template_directory_uri() .'/assets/img/play.svg'?>" alt="">
                                </div>
                                <div class="close__video">
                                    <img src="<?php echo get_template_directory_uri() .'/assets/img/close.svg'?>" alt="">
                                </div>

                        </div>
                    </div>
                    <div class="model__right">
                        <div class="model__nav">
                            <h3><?php echo $term[0]->name ?></h3>
                            <ul>
                                <?php $prev_post = get_previous_post(true,'','product_cat');
                                if( ! empty($prev_post) ){
                                    echo '<li><a href="'.get_permalink( $prev_post ).'">'.esc_html($prev_post->post_title).'</a></li>';
                                }
                                ?>
                                <?php
                                echo '<li><a class="active">'.get_the_title().'</a></li>';
                                ?>
                                <?php $next_post = get_next_post(true,'','product_cat');
                                if( ! empty($next_post) ){
                                    echo '<li><a href="'.get_permalink( $next_post ).'">'.esc_html($next_post->post_title).'</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="model__descriptions">
                            <p><?php echo $product->post->post_content; ?></p>
                            <ul>
                                <li><?php echo $product->get_attribute('back');?></li>
                                <li><?php echo $product->get_attribute('bodice-fabric');?></li>
                                <li><?php echo $product->get_attribute('neckline');?></li>
                                <li><?php echo $product->get_attribute('silhouette-cut');?></li>
                                <li><?php echo $product->get_attribute('skirt-fabric');?></li>
                                <li><?php echo $product->get_attribute('sleeves');?></li>
                            </ul>
                        </div>
                        <div class="model__slider">
                                <div class="model__wrapper">
                                    <?php
                                    global $product;
                                    $attachment_ids = $product->get_gallery_attachment_ids();
                                    echo '<div class="model__img"><img src='.get_the_post_thumbnail_url().'></div>';
                                    foreach( $attachment_ids as $attachment_id )
                                    {
                                        echo '<div class="model__img">'.wp_get_attachment_image($attachment_id, 'full').'</div>';
                                    }
                                    ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>
<?php
get_footer();