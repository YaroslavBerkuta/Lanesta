<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @var $desktop_report Smartcrawl_Lighthouse_Report
 * @var $mobile_report Smartcrawl_Lighthouse_Report
 */
$desktop_report = empty( $desktop_report ) ? false : $desktop_report;
$mobile_report = empty( $mobile_report ) ? false : $mobile_report;
if ( ! $desktop_report || ! $mobile_report ) {
	return;
}
$username = empty( $username ) ? '' : $username;
$device = empty( $device ) ? '' : $device;

$hide_branding = Smartcrawl_White_Label::get()->is_hide_wpmudev_branding();
$lighthouse_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH ) . '&tab=tab_lighthouse';
$reporting_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH ) . '&tab=tab_reporting';
$time_string = $desktop_report->get_last_checked();
$desktop_icon_2x = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'icon-desktop@2x.png' );
$desktop_icon = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'icon-desktop.png' );
$mobile_icon_2x = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'icon-mobile@2x.png' );
$mobile_icon = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'icon-mobile.png' );
$icon_success = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'icon-success.png' );
$icon_warning = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'icon-warning.png' );
$icon_error = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'icon-error.png' );
?>

<table class="wrapper main" align="center"
       style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
	<tbody>
	<tr style="padding: 0; text-align: left; vertical-align: top;">
		<td class="wrapper-inner main-inner"
		    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #555555; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px; font-weight: normal; hyphens: auto; line-height: 30px; margin: 0; padding: 40px 60px; text-align: left; vertical-align: top; word-wrap: break-word;">

			<table class="main-content"
			       style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
				<tbody>
				<tr style="padding: 0; text-align: left; vertical-align: top;">
					<td class="main-content-text"
					    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #333333; font-family: 'Open Sans', Arial, sans-serif; font-size: 15px; font-weight: normal; hyphens: auto; line-height: 30px; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word;">
						<?php /* translators: %s: Username. */ ?>
						<p style="color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 18px;font-weight: normal;line-height: 24px;margin: 0 0 10px;padding: 0;text-align: left"><?php printf( esc_html__( 'Hi %s,', 'wds' ), esc_attr( $username ) ); ?></p>

						<p style="color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 18px;font-weight: normal;line-height: 28px;margin: 0 0 30px;padding: 0;text-align: left;letter-spacing: -0.3px;">
							<?php esc_html_e( 'Here’s your latest SEO Test summary of', 'wds' ); ?>&nbsp;
							<a class="brand" href="<?php echo esc_attr( $lighthouse_url ); ?>"
							   target="_blank"
							   style="color: #17A8E3;font-family: 'Open Sans', Arial, sans-serif;font-weight: inherit;line-height: 30px;margin: 0;padding: 0;text-align: left;text-decoration: none">
								<?php echo esc_html( site_url() ); ?>
							</a>&nbsp;
							<?php
							printf( /* translators: %s - tested on */
								__( 'tested on %s.', 'wds' ),
								$time_string
							);
							?>
						</p>

						<p style="color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 25px;font-weight: 600;line-height: 34px;margin: 0 0 5px;padding: 0;text-align: left;">
							<?php esc_html_e( 'Overall Score', 'wds' ); ?>
						</p>

						<p style="color: #666666;font-family: 'Open Sans', Arial, sans-serif;font-size: 14px;letter-spacing: -0.23px;line-height: 22px;margin: 0 0 15px;padding: 0;text-align: left;">
							<?php esc_html_e( 'Here are your latest performance test results.', 'wds' ); ?>
						</p>

						<table class="reports-list" align="center"
						       style="border-collapse: collapse;border-spacing: 0;margin: 0 0 30px;padding: 0;text-align: left;vertical-align: top;width: 100%">
							<thead>
							<tr style="background-color: #F2F2F2">
								<?php if ( 'both' === $device || 'desktop' === $device ) : ?>
									<td style="border-radius: 4px 0 0 0;color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 12px;font-weight: 600;line-height: 27px; letter-spacing: -0.23px; text-align: center">
										<img alt=""
										     src="<?php echo esc_url( $desktop_icon_2x ); ?>"
										     srcset="<?php echo esc_url( $desktop_icon ); ?>, <?php echo esc_url( $desktop_icon_2x ); ?> 2x"
										     style="height: 16px;vertical-align: middle;">
										<span style="margin-left: 5px"><?php esc_html_e( 'Desktop', 'wds' ); ?></span>
									</td>
								<?php endif; ?>
								<?php if ( 'both' === $device || 'mobile' === $device ) : ?>
									<td style="border-radius: 0 4px 0 0;color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 12px;font-weight: 600;line-height: 27px; letter-spacing: -0.23px; text-align: center">
										<img alt=""
										     src="<?php echo esc_url( $mobile_icon_2x ); ?>"
										     srcset="<?php echo esc_url( $mobile_icon ); ?>, <?php echo esc_url( $mobile_icon_2x ); ?> 2x"
										     style="height: 16px;vertical-align: middle;">
										<span style="margin-left: 5px"><?php esc_html_e( 'Mobile', 'wds' ); ?></span>
									</td>
								<?php endif; ?>
							</tr>
							</thead>
							<tbody>
							<tr class="report-list-item" style="height: 95px;">
								<?php if ( 'both' === $device || 'desktop' === $device ) : ?>
									<td class="report-list-item-result" align="center"
									    style="border: 1px solid #F2F2F2;color: #555555;font-family: 'Open Sans', Arial, sans-serif;">
										<table>
											<tr>
												<td rowspan="2"
												    style="font-size: 50px;font-weight: 600"><?php echo absint( $desktop_report->get_score() ); ?></td>
												<td style="text-align: left">
													<?php if ( 'a' === $desktop_report->get_score_grade() ) : ?>
														<img src="<?php echo esc_url( $icon_success ); ?>"
														     alt="<?php esc_attr_e( 'Ok', 'wds' ); ?>"
														     style="-ms-interpolation-mode: bicubic; border: none; height: 16px; outline: none; text-decoration: none; width: auto;"/>
													<?php elseif ( 'c' === $desktop_report->get_score_grade() ) : ?>
														<img src="<?php echo esc_url( $icon_warning ); ?>"
														     alt="<?php esc_attr_e( 'Warning', 'wds' ); ?>"
														     style="-ms-interpolation-mode: bicubic; border: none; height: 16px; outline: none; text-decoration: none; width: auto;"/>
													<?php elseif ( 'f' === $desktop_report->get_score_grade() ) : ?>
														<img src="<?php
														echo esc_url( $icon_error ); ?>"
														     alt="<?php esc_attr_e( 'Critical', 'wds' ); ?>"
														     style="-ms-interpolation-mode: bicubic; border: none; height: 16px; outline: none; text-decoration: none; width: auto;"/>
													<?php endif; ?>
												</td>
											</tr>
											<tr>
												<td>
													<span style="color: #555555;font-family: 'Open Sans', Arial, sans-serif;font-size: 13px;font-weight: normal;line-height: 22px;letter-spacing: -0.3px;width: 300px;vertical-align: top">/100</span>
												</td>
											</tr>
										</table>
									</td>
								<?php endif; ?>

								<?php if ( 'both' === $device || 'mobile' === $device ) : ?>
									<td class="report-list-item-result" align="center"
									    style="border: 1px solid #F2F2F2;color: #555555;font-family: 'Open Sans', Arial, sans-serif;">
										<table>
											<tr>
												<td rowspan="2"
												    style="font-size: 50px;font-weight: 600"><?php echo absint( $mobile_report->get_score() ); ?></td>
												<td style="text-align: left">
													<?php if ( 'a' === $mobile_report->get_score_grade() ) : ?>
														<img src="<?php echo esc_url( $icon_success ); ?>"
														     alt="<?php esc_attr_e( 'Ok', 'wds' ); ?>"
														     style="-ms-interpolation-mode: bicubic; border: none; height: 16px; outline: none; text-decoration: none; width: auto;"/>
													<?php elseif ( 'c' === $mobile_report->get_score_grade() ) : ?>
														<img src="<?php echo esc_url( $icon_warning ); ?>"
														     alt="<?php esc_attr_e( 'Warning', 'wds' ); ?>"
														     style="-ms-interpolation-mode: bicubic; border: none; height: 16px; outline: none; text-decoration: none; width: auto;"/>
													<?php elseif ( 'f' === $mobile_report->get_score_grade() ) : ?>
														<img src="<?php
														echo esc_url( $icon_error ); ?>"
														     alt="<?php esc_attr_e( 'Critical', 'wds' ); ?>"
														     style="-ms-interpolation-mode: bicubic; border: none; height: 16px; outline: none; text-decoration: none; width: auto;"/>
													<?php endif; ?>
												</td>
											</tr>
											<tr>
												<td>
													<span style="color: #555555;font-family: 'Open Sans', Arial, sans-serif;font-size: 13px;font-weight: normal;line-height: 22px;letter-spacing: -0.3px;width: 300px;vertical-align: top">/100</span>
												</td>
											</tr>
										</table>
									</td>
								<?php endif; ?>
							</tr>
							</tbody>
						</table>

						<p style="color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 25px;font-weight: 600;line-height: 34px;margin: 0 0 5px;padding: 0;text-align: left;">
							<?php esc_html_e( 'SEO Audits', 'wds' ); ?>
						</p>

						<p style="color: #666666;font-family: 'Open Sans', Arial, sans-serif;font-size: 14px;letter-spacing: -0.23px;line-height: 22px;margin: 0 0 15px;padding: 0;text-align: left;">
							<?php esc_html_e( 'These SEO audits ensure that your page is optimized for search engine results ranking. We recommend actioning as many as possible.', 'wds' ); ?>
						</p>

						<?php foreach ( $desktop_report->get_groups() as $group_id => $group ): ?>
							<?php if ( Smartcrawl_Lighthouse_Report::GROUP_MANUAL === $group_id ) {
								continue;
							} ?>
							<table class="reports-list" align="center"
							       style="border-collapse: collapse;border-spacing: 0;margin: 0 0 30px;padding: 0;text-align: left;vertical-align: top;width: 100%">
								<thead>
								<tr style="background-color: #F2F2F2">
									<td style="padding-left: 20px;border-radius: 4px 0 0 0;color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 12px;font-weight: 600;line-height: 27px; letter-spacing: -0.23px; text-align: left">
										<?php echo esc_html( $group->get_label() ); ?>
									</td>

									<?php if ( 'both' === $device || 'desktop' === $device ) : ?>
										<td style="padding: 0 10px;color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 12px;font-weight: 600;line-height: 27px; letter-spacing: -0.23px; text-align: center; width:75px;">
											<img alt=""
											     src="<?php echo esc_url( $desktop_icon_2x ); ?>"
											     srcset="<?php echo esc_url( $desktop_icon ); ?>, <?php echo esc_url( $desktop_icon_2x ); ?> 2x"
											     style="height: 16px;vertical-align: middle;">
											<span style="margin-left: 5px; vertical-align: top;"><?php esc_html_e( 'Desktop', 'wds' ); ?></span>
										</td>
									<?php endif; ?>

									<?php if ( 'both' === $device || 'mobile' === $device ) : ?>
										<td style="padding: 0 10px;border-radius: 0 4px 0 0;color: #333333;font-family: 'Open Sans', Arial, sans-serif;font-size: 12px;font-weight: 600;line-height: 27px; letter-spacing: -0.23px; text-align: center; width: 70px">
											<img alt=""
											     src="<?php echo esc_url( $mobile_icon_2x ); ?>"
											     srcset="<?php echo esc_url( $mobile_icon ); ?>, <?php echo esc_url( $mobile_icon_2x ); ?> 2x"
											     style="height: 16px;vertical-align: middle;">
											<span style="margin-left: 5px; vertical-align: top;"><?php esc_html_e( 'Mobile', 'wds' ); ?></span>
										</td>
									<?php endif; ?>
								</tr>
								</thead>

								<tbody>
								<?php foreach ( $group->get_checks() as $check_id => $check ): ?>
									<tr class="report-list-item"
									    style="border: 1px solid #F2F2F2;padding: 0;text-align: left;vertical-align: top">

										<td class="report-list-item-info"
										    style="border-collapse: collapse !important;color: #666666;font-family: 'Open Sans', Arial, sans-serif;font-size: 12px;font-weight: 600;letter-spacing: -0.23px;line-height: 22px;margin: 0;padding: 18px 0 18px 20px;text-align: left;vertical-align: top">
											<span style="color: inherit; display: inline; font-size: inherit; font-family: inherit; line-height: inherit; vertical-align: middle; letter-spacing: -0.25px;">
												<?php echo esc_html( $check->get_title() ); ?>
											</span>
										</td>
										<?php if ( 'both' === $device || 'desktop' === $device ) : ?>
											<td class="report-list-item-info"
											    style="border-collapse: collapse !important;color: #888888;font-family: 'Open Sans', Arial, sans-serif;font-size: 13px;font-weight: 600;letter-spacing: -0.25px;line-height: 21px;margin: 0;padding: 18px 0;text-align: center;vertical-align: top">
												<?php if ( $check->get_weight() ): ?>
													<img src="<?php echo esc_url( $check->is_passed() ? $icon_success : $icon_warning ); ?>"
													     style="-ms-interpolation-mode: bicubic; border: none; clear: both; display: inline-block; height: 16px; outline: none; text-decoration: none; width: auto; vertical-align: middle;">
												<?php else: ?>
													<span>-</span>
												<?php endif; ?>
											</td>
										<?php endif; ?>

										<?php if ( 'both' === $device || 'mobile' === $device ) : ?>
											<td class="report-list-item-result"
											    style="border-collapse: collapse !important;color: #888888;font-family: 'Open Sans', Arial, sans-serif;font-size: 13px;font-weight: 600;letter-spacing: -0.25px;line-height: 21px;margin: 0;min-width: 65px;padding: 18px 0;text-align: center;vertical-align: top">

												<?php if ( $mobile_report->get_group( $group_id )->get_check( $check_id )->get_weight() ): ?>
													<img src="<?php echo esc_url( $mobile_report->get_group( $group_id )->get_check( $check_id )->is_passed() ? $icon_success : $icon_warning ); ?>"
													     style="-ms-interpolation-mode: bicubic; border: none; clear: both; display: inline-block; height: 16px; outline: none; text-decoration: none; width: auto; vertical-align: middle;">
												<?php else: ?>
													<span>-</span>
												<?php endif; ?>
											</td>
										<?php endif; ?>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						<?php endforeach; ?>

						<p style="color: #555555;font-family: 'Open Sans', Arial, sans-serif;font-size: 16px;font-weight: normal;line-height: 20px;margin: 0 0 20px;padding: 0;text-align: center">
							<a href="<?php echo esc_url( $lighthouse_url ); ?>" class="brand-button"
							   style="background: #17A8E3;color: #ffffff;font-family: 'Open Sans', Arial, sans-serif;font-size: 16px;font-weight: normal;line-height: 20px;margin: 0;padding: 10px 20px;text-align: center;text-decoration: none;display: inline-block;border-radius: 4px;text-transform: uppercase">
								<?php esc_html_e( 'View full report', 'wds' ); ?>
							</a>
						</p>

						<p style="margin: 0 0 30px;padding: 0;text-align: center">
							<a style="color: #17A8E3;font-family: 'Open Sans', Arial, sans-serif;font-size: 12px;font-weight: 500;letter-spacing: -0.25px;line-height: 16px;text-decoration: none"
							   href="<?php echo esc_url( $reporting_url ); ?>"
							   class="brand-link" target="_blank">
								<?php esc_html_e( 'Customize email report', 'wds' ); ?>
							</a>
						</p>

						<?php if ( ! $hide_branding ): ?>
							<p style="color: #666666;font-family: 'Open Sans', Arial, sans-serif;font-size: 15px;font-weight: normal;line-height: 20px;margin: 0 0 20px;padding: 0;text-align: left;clear: both"><?php esc_html_e( 'Stay optimized.', 'wds' ); ?></p>
							<strong><?php esc_html_e( 'Smartcrawl', 'wds' ); ?></strong>
							<p style="color: #666666;font-family: 'Open Sans', Arial, sans-serif;font-size: 15px;font-weight: normal;line-height: 15px;margin: 10px 0 15px;padding: 0;text-align: left"><?php esc_html_e( 'WPMU DEV SEO Hero', 'wds' ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
