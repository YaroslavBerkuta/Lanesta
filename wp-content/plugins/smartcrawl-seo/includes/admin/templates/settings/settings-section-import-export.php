<?php
$aioseo_importer = new Smartcrawl_AIOSEOP_Importer();
?>
<?php wp_nonce_field( 'wds-io-nonce', '_wds_nonce' ); ?>
<div class="wds-io">
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label"><?php esc_html_e( 'Import', 'wds' ); ?></label>
			<p class="sui-description"><?php esc_html_e( 'Use this tool to import your SmartCrawl settings from another site.', 'wds' ); ?></p>
		</div>
		<div class="sui-box-settings-col-2 wds-io wds-import">
			<label class="sui-settings-label"><?php esc_html_e( 'Third Party', 'wds' ); ?></label>
			<p class="sui-description">
				<?php esc_html_e( 'Automatically import your SEO configuration from other SEO plugins.', 'wds' ); ?>
			</p>

			<table class="sui-table">
				<tr class="wds-yoast">
					<td>
						<strong><?php esc_html_e( 'Yoast SEO', 'wds' ); ?></strong>
					</td>
					<td>
						<button type="button" class="sui-button">
							<span class="sui-icon-download-cloud" aria-hidden="true"></span>

							<?php esc_html_e( 'Import', 'wds' ); ?>
						</button>
					</td>
				</tr>
				<?php if ( $aioseo_importer->data_exists() ): ?>
					<tr class="wds-aioseop">
						<td>
							<strong><?php esc_html_e( 'All In One SEO', 'wds' ); ?></strong>
						</td>
						<td>
							<button type="button" class="sui-button">
								<span class="sui-icon-download-cloud" aria-hidden="true"></span>

								<?php esc_html_e( 'Import', 'wds' ); ?>
							</button>
						</td>
					</tr>
				<?php endif; ?>
			</table>
			<p class="sui-description">
				<?php esc_html_e( 'Automatically import your SEO configuration from other SEO plugins. Note: This will override all of your current settings. We recommend exporting your current settings first, just in case.', 'wds' ); ?>
			</p>
		</div>
	</div>
</div>
<?php $this->_render( 'settings/import-status-modal' ); ?>
