<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
$email_template = empty( $email_template ) ? '' : $email_template;
$email_template_args = empty( $email_template_args ) ? array() : $email_template_args;
?>

<body style="-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; background-color: #e9ebe7; box-sizing: border-box; color: #555555; font-family: 'Open Sans', Arial, sans-serif; font-size: 15px; font-weight: normal; line-height: 26px; margin: 0; min-width: 100%; padding: 0; text-align: left; width: 100% !important;">
<table class="body"
       style="background-color: #e9ebe7; border-collapse: collapse; border-spacing: 0; color: #555555; font-family: 'Open Sans', Arial, sans-serif; font-size: 15px; font-weight: normal; height: 100%; line-height: 26px; margin: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
	<tbody>
	<tr style="padding: 0; text-align: left; vertical-align: top;">
		<td class="center" align="center" valign="top"
		    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #555555; font-family: Arial, sans-serif; font-size: 15px; font-weight: normal; hyphens: auto; line-height: 26px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word;">

			<center style="min-width: 600px; width: 100%;">

				<table class="container"
				       style="background-color: #fff; border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: inherit; vertical-align: top; width: 600px;">
					<tbody>
					<tr style="padding: 0; text-align: left; vertical-align: top;">
						<td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #555555; font-family: Arial, sans-serif; font-size: 15px; font-weight: normal; hyphens: auto; line-height: 26px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word;">
							<?php $this->_render( 'emails/email-header' ); ?>
							<?php $this->_render( $email_template, $email_template_args ); ?>
							<?php $this->_render( 'emails/email-footer' ); ?>
						</td>
					</tr>
					</tbody>
				</table>

			</center>

		</td>
	</tr>
	</tbody>
</table>
</body>
