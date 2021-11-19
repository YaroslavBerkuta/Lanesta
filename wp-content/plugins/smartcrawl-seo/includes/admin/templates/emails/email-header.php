<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hide_branding = Smartcrawl_White_Label::get()->is_hide_wpmudev_branding();
$header_image_url = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'graphic-checkup-summary.png' );
$header_image_url_2x = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'graphic-checkup-summary@2x.png' );
$alt_text = esc_html__( 'Smartcrawl Report', 'wphb' );
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&display=swap" rel="stylesheet">
<table class="wrapper hero" align="left"
       style="background-color: #e9ebe7; border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
	<tbody>
	<tr style="padding: 0; text-align: left; vertical-align: top;">
		<td class="wrapper-inner hero-inner"
		    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #555555; font-family: 'Open Sans', Arial, sans-serif; font-size: 15px; font-weight: normal; hyphens: auto; line-height: 26px; margin: 0; padding: 20px 0 0; text-align: left; vertical-align: top; word-wrap: break-word;">

			<table class="hero-content" align="left"
			       style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
				<tbody>
				<tr style="padding: 0; text-align: center; vertical-align: bottom;">
					<?php if ( ! $hide_branding ): ?>
						<td class="hero-image"
						    style="background-color: #DE1829; border-radius: 4px 4px 0 0; height: 150px; border-collapse: collapse !important; margin: 0; padding: 0; text-align: center; vertical-align: bottom;">
							<img src="<?php echo esc_url( $header_image_url_2x ); ?>"
							     srcset="<?php echo esc_url( $header_image_url ); ?>, <?php echo esc_url( $header_image_url_2x ); ?> 2x"
							     alt="<?php echo esc_attr( $alt_text ); ?>"
							     style="-ms-interpolation-mode: bicubic; border: none; vertical-align:bottom; clear: both; display: inline-block; outline: none; text-decoration: none; width: auto; height: 147px">
						</td>
					<?php endif; ?>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
