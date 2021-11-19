<?php

class Smartcrawl_Lighthouse_Tap_Targets_Table extends Smartcrawl_Lighthouse_Table {
	private $rows = array();
	private $tap_target_screenshots = array();
	private $overlapping_screenshots = array();

	public function add_row( $row, $tap_target_node_id = '', $overlapping_node_id = '' ) {
		$this->rows[] = $row;
		$this->tap_target_screenshots[] = $this->get_screenshot( $tap_target_node_id, 100, 75 );
		$this->overlapping_screenshots[] = $this->get_screenshot( $overlapping_node_id, 100, 75 );
	}

	public function render() {
		if ( empty( $this->rows ) ) {
			return;
		}
		?>
		<table class="sui-table">
			<tr>
				<?php foreach ( $this->get_header() as $head_col ): ?>
					<th><?php echo wp_kses_post( $head_col ); ?></th>
				<?php endforeach; ?>
			</tr>

			<?php foreach ( $this->rows as $index => $row_details ): ?>
				<?php
				$row = $row_details;
				$tap_target_screenshot = smartcrawl_get_array_value( $this->tap_target_screenshots, $index );
				$overlapping_screenshot = smartcrawl_get_array_value( $this->overlapping_screenshots, $index );
				?>
				<tr>
					<?php foreach ( $row as $col_index => $col ): ?>
						<td>
							<div style="display: flex; align-items: center;">
								<div style="margin-right: 10px; word-break: break-all;">
									<?php echo esc_html( $col ); ?>
								</div>

								<?php if ( $col_index === 0 ): ?>
									<?php echo $tap_target_screenshot; ?>
								<?php elseif ( $col_index === 2 ): ?>
									<?php echo $overlapping_screenshot; ?>
								<?php endif; ?>
							</div>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}

	public function get_rows() {
		return $this->rows;
	}
}
