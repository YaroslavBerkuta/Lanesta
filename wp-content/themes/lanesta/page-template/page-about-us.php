<?php
/**
 * Template Name: About Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
?>
<main class="main">
    <div id="pagepiling">
        <section class="slide-section bg-black height-100vh">
            <div class="container">
                <h1 class="title">Perfection in everything</h1>
            </div>
        </section>
        <section class="slide-section bg-gray height-100vh">
            <div class="container">
                <div class="text-center">
                    <h2 class="title">AN IDEAL WEDDING DRESS</h2>
                    <p>Lanesta is a Polish wedding dress brand established in 2014. Luxurious romance is the key to understanding the brand’s aesthetics that remain unchanged through time. Our designers are inspired by classic beauty, art and the most wonderful corners of the world, where the pure and deep feelings of couples in love are saturated with unforgettable emotions.</p>
                </div>
            </div>
        </section>
        <section class="slide-section height-100vh">
            <div class="container">
                <div class="text-center">
                    <h2 class="title">HARMONY AND CONTEMPORANEITY</h2>
                    <p>Expensive materials, luxurious hand-embroidery, perfect silhouettes and unusual details make the brand’s dresses unforgettable. “Lanesta” is associated among fashionistas all over the world with a refined sense of style, high level of quality and elite execution of unsurpassed outfits.In addition to modern trends in the design of wedding dresses, the creators of the brand have provided an innovative approach to the development of patterns and tailoring technologies. Thanks to the perfected sewing patterns, dresses fit perfectly to the figure, and high-class modernized production guarantees exceptional quality. The brand’s master-hands make all efforts to make the dresses look perfect from the first stitch to the last pearl on the corset.</p>
                </div>
            </div>
        </section>
        <section class="slide-section bg-gray ">
            <div class="container">
                <div class="about__media">
                    <div class="img__container">
                        <img src="<?php echo get_template_directory_uri() . '/assets/img/about1.png'?>" alt="">
                    </div>
                    <div class="img__container">
                        <video src="<?php echo get_template_directory_uri() . '/assets/img/aboutVideo.mp4'?>" muted autoplay loop></video>
                    </div>
                    <div class="img__container">
                        <img src="<?php echo get_template_directory_uri() . '/assets/img/about3.png'?>" alt="">
                    </div>
                </div>
            </div>
        </section>
       <?php  get_template_part( 'template-parts/content', 'footer' ); ?>
    </div>
</main>
<?php
get_footer();
