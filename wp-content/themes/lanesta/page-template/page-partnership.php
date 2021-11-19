<?php
/**
 * Template Name: Partnership Page
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
                <div class="partnership__wrapper">
                    <form action="" method="post">
                        <input type="text" placeholder="name (required)" required name="name">
                        <input type="text" placeholder="e-mail (required)" required name="email">
                        <input type="tel" placeholder="phone (required)" required name="phone">
                        <input type="text" placeholder="salon" name="salon">
                        <textarea name="" id="" placeholder="Message" name="message"></textarea>
                        <p>To become an official representative of TM Lanesta in your city or region, please fill out the form. After that, our manager will contact you for further discussion of the details.</p>
                        <button type="submit">Send</button>
                    </form>
                    <div class="partnership__info">
                        <h3>THE HEAD OF SALES DEPARTMENT</h3>
                        <p>Irina</p>
                        <a href="mailto:info@lanesta.com">info@lanesta.com</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
get_footer();

