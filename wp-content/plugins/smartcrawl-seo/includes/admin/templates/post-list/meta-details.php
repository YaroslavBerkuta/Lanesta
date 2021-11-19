<?php
$post = empty( $post ) ? null : $post;
if ( ! $post ) {
	return;
}
$resolver = Smartcrawl_Endpoint_Resolver::resolve();
$resolver->simulate_post( $post );
$helper = Smartcrawl_Meta_Value_Helper::get();

$title = $helper->get_title();
$title_length = mb_strlen( trim( $title ) );
$title_tag_class = $title_length >= smartcrawl_title_min_length()
                   && $title_length <= smartcrawl_title_max_length() ? 'wds-tag-success' : 'wds-tag-warning';

$metadesc = $helper->get_description();
$metadesc_length = mb_strlen( trim( $metadesc ) );
$metadesc_tag_class = $metadesc_length >= smartcrawl_metadesc_min_length()
                      && $metadesc_length <= smartcrawl_metadesc_max_length() ? 'wds-tag-success' : 'wds-tag-warning';

$resolver->stop_simulation();

$of = esc_html__( 'of', 'wds' );
$title_min = smartcrawl_title_min_length();
$title_max = smartcrawl_title_max_length();
$metadesc_min = smartcrawl_metadesc_min_length();
$metadesc_max = smartcrawl_metadesc_max_length();
?>

<div class="wds-meta-details">
	<strong>
		<a href="#">
			<?php esc_html_e( 'SEO Meta', 'wds' ); ?>
			<span class="wds-plus">[+]</span>
			<span class="wds-minus">[-]</span>
		</a>
	</strong>

	<div class="wds-meta-details-inner hidden">
		<?php if ( $title ): ?>
			<div>
				<strong>
					<?php esc_html_e( 'Title', 'wds' ); ?>
					<span class="wds-tag <?php echo esc_attr( $title_tag_class ); ?>">
						<?php echo esc_html( "$title_length $of $title_min-$title_max" ); ?>
					</span>
				</strong>
				<p><?php echo esc_html( $title ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( $metadesc ): ?>
			<div>
				<strong>
					<?php esc_html_e( 'Description', 'wds' ); ?>
					<span class="wds-tag <?php echo esc_attr( $metadesc_tag_class ); ?>">
						<?php echo esc_html( "$metadesc_length $of $metadesc_min-$metadesc_max" ); ?>
					</span>
				</strong>
				<p><?php echo esc_html( $metadesc ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
