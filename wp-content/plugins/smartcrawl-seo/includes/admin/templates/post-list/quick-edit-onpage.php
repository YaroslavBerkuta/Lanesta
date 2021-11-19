<fieldset class="inline-edit-col-left">
	<div class="inline-edit-col long-label">
		<h4></h4>
		<label>
			<span class="title"><?php esc_html_e( 'Title Tag', 'wds' ); ?></span>
			<span class="input-text-wrap">
				<input class="ptitle smartcrawl_title" type="text" value="" name="wds_title"/>
			</span>
		</label>
	</div>
	<div class="inline-edit-col long-label">
		<label>
			<span class="title metadesc"><?php esc_html_e( 'Meta Description', 'wds' ); ?></span>
			<span class="input-text-wrap">
				<textarea class="ptitle smartcrawl_metadesc" name="wds_metadesc"></textarea>
			</span>
		</label>
	</div>
</fieldset>
<input type="hidden"
       value="<?php echo esc_attr( wp_create_nonce( 'wds-metabox-nonce' ) ); ?>"
       name="_wds_nonce"/>
<style>
	.inline-edit-col .title.metadesc {
		display: block;
		width: 100%;
	}

	.inline-edit-col.long-label .title {
		width: 10em;
	}

	.inline-edit-col.long-label .input-text-wrap {
		margin-left: 10em;
	}
</style>
