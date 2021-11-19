<?php
/**
 * @var $lighthouse_group Smartcrawl_Lighthouse_Group
 */
$lighthouse_group = empty( $lighthouse_group ) ? false : $lighthouse_group;
if ( ! $lighthouse_group ) {
	return;
}
$notices = array(
	Smartcrawl_Lighthouse_Report::GROUP_RESPONSIVE => esc_html__( 'Your page is mobile-friendly – Google is loving it.', 'wds' ),
	Smartcrawl_Lighthouse_Report::GROUP_VISIBILITY => esc_html__( 'Way to go! It appears your Homepage is crawlable and indexable!', 'wds' ),
	Smartcrawl_Lighthouse_Report::GROUP_CONTENT    => esc_html__( "You don't have any outstanding content audit – Google is loving it.", 'wds' ),
);
?>

<p><?php echo esc_html( $lighthouse_group->get_description() ) ?></p>

<?php foreach ( $notices as $notice_group => $notice ): ?>
	<?php
	if (
		$lighthouse_group->get_id() === $notice_group
		&& $lighthouse_group->get_failing_count() === 0
	) {
		Smartcrawl_Simple_Renderer::render( 'notice', array(
			'class'   => 'sui-notice-success',
			'message' => $notice,
		) );
	}
	?>
<?php endforeach; ?>

<div class="sui-accordion sui-accordion-flushed">
	<?php
	foreach ( $lighthouse_group->get_checks() as $check ) {
		$this->_render( 'lighthouse/lighthouse-check-item', array( 'check' => $check ) );
	}
	?>
</div>
