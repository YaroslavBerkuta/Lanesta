<?php
$id = empty( $id ) ? '' : $id;
$title = empty( $title ) ? '' : $title;
$description = empty( $description ) ? '' : $description;
$header_actions_template = empty( $header_actions_template ) ? '' : $header_actions_template;
$body_template = empty( $body_template ) ? '' : $body_template;
$body_template_args = empty( $body_template_args ) ? array() : $body_template_args;
$footer_template = empty( $footer_template ) ? '' : $footer_template;
$footer_template_args = empty( $footer_template_args ) ? array() : $footer_template_args;
$small = empty( $small ) ? false : $small;
$is_member = empty( $_view['is_member'] ) ? false : true;
?>

<div class="sui-modal <?php echo $small ? 'sui-modal-sm' : 'sui-modal-lg'; ?>">
	<div role="dialog"
	     id="<?php echo esc_attr( $id ); ?>"
	     class="sui-modal-content <?php echo esc_attr( $id ); ?>-dialog <?php echo $is_member ? 'is-member' : ''; ?>"
	     aria-modal="true"
	     aria-labelledby="<?php echo esc_attr( $id ); ?>-dialog-title"
	     aria-describedby="<?php echo esc_attr( $id ); ?>-dialog-description">

		<div class="sui-box" role="document">
			<div class="sui-box-header <?php echo $small ? 'sui-flatten sui-content-center sui-spacing-top--40' : ''; ?>">
				<h3 class="sui-box-title <?php echo $small ? 'sui-lg' : ''; ?>"
				    id="<?php echo esc_attr( $id ); ?>-dialog-title">
					<?php echo esc_html( $title ); ?>
				</h3>

				<?php if ( $small ): ?>
					<button class="sui-button-icon sui-button-float--right" data-modal-close
					        id="<?php echo esc_attr( $id ); ?>-close-button"
					        type="button">
						<span class="sui-icon-close sui-md" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'wds' ); ?></span>
					</button>
				<?php else: ?>
					<div class="sui-actions-right">
						<?php if ( $header_actions_template ): ?>
							<?php $this->_render( $header_actions_template ); ?>
						<?php else: ?>
							<button class="sui-button-icon" data-modal-close
							        id="<?php echo esc_attr( $id ); ?>-close-button"
							        type="button">
								<span class="sui-icon-close sui-md" aria-hidden="true"></span>
								<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'wds' ); ?></span>
							</button>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="sui-box-body <?php echo $small ? 'sui-content-center' : ''; ?>">
				<?php if ( $description ): ?>
					<p class="sui-description"
					   id="<?php echo esc_attr( $id ); ?>-dialog-description"><?php echo wp_kses_post( $description ); ?></p>
				<?php endif; ?>
				<?php if ( $body_template ): ?>
					<?php $this->_render(
						$body_template,
						array_merge(
							array( 'id' => $id ),
							$body_template_args
						)
					); ?>
				<?php endif; ?>
			</div>

			<?php if ( $footer_template ): ?>
				<div class="sui-box-footer">
					<?php $this->_render(
						$footer_template,
						array_merge(
							array( 'id' => $id ),
							$footer_template_args
						)
					); ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
