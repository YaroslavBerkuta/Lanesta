<?php
/**
 * Template part for displaying results in footer pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lanesta
 */

?>

<section class="slide-section footer">
    <div class="container">
        <div class="footer__flex">
            <div class="footer__left">
                <div class="footer__logo">
                    <img src="<?php echo get_template_directory_uri(). '/assets/img/logo-black.png' ?>" width="120" height="40">
                </div>
                <div class="footer__subscribe">
                    <form action="">
                        <input type="text" placeholder="EMAIL">
                        <button type="submit"><img src="<?php echo get_template_directory_uri(). '/assets/img/arrow-right.svg' ?>" width="25" height="25" /></button>
                    </form>
                </div>
                <p class="footer__form-description">Sign up for newsletter, discounts or good commercial offers</p>
            </div>
            <div class="footer__right">
                <div class="footer__menu">
                    <h3>MENU</h3>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-footer',
                            'container'       => false,
                            'menu_id'        => 'Footer',
                            'container_class' => ' ',
                            'container_id'    => ' ',
                            'menu_class'      => ' ',
                            'menu_id'         => ' ',
                        )
                    );
                    ?>
                </div>
                <div class="footer__contact">
                    <h3>CONTACT</h3>
                    <p>Lublin, Poland
                        ul. 3 Maja 7/2, 20-078<br>
                        <br>Opening hours:
                        from 10 to 18.</p><br>
                    <a href="tel:+48507564856" class="footer__contact-number">+48 507 564 856</a>
                </div>
                <div class="footer__social">
                    <h3>Social</h3>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-social',
                            'container'       => false,
                            'menu_id'        => 'Social',
                            'menu_class'      => 'footer__social-list',
                            'menu_id'         => ' ',
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
