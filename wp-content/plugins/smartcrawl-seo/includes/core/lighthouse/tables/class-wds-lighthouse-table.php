<?php

class Smartcrawl_Lighthouse_Table {
	private $header;
	private $report;
	private $rows = array();
	private $screenshots = array();

	public function __construct( $header, $report ) {
		$this->header = $header;
		$this->report = $report;
	}

	public function add_row( $row, $screenshot_node_id = '' ) {
		$this->rows[] = $row;
		$this->screenshots[] = $this->get_screenshot( $screenshot_node_id );
	}

	public function render() {
		if ( empty( $this->rows ) ) {
			return;
		}
		?>
		<table class="sui-table">
			<tr>
				<?php foreach ( $this->header as $head_col ): ?>
					<th><?php echo wp_kses_post( $head_col ); ?></th>
				<?php endforeach; ?>

				<?php if ( array_filter( $this->screenshots ) ): ?>
					<th class="wds-lh-screenshot-th"><?php esc_html_e( 'Screenshot', 'wds' ); ?></th>
				<?php endif; ?>
			</tr>

			<?php foreach ( $this->rows as $index => $row_details ): ?>
				<?php
				$row = $row_details;
				$screenshot = smartcrawl_get_array_value( $this->screenshots, $index );
				?>
				<tr>
					<?php foreach ( $row as $col ): ?>
						<td><?php echo esc_html( $col ); ?></td>
					<?php endforeach; ?>

					<?php if ( $screenshot ): ?>
						<td><?php echo $screenshot; ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}

	public function get_screenshot( $node_id, $thumb_width = 160, $thumb_height = 120 ) {
		$thumbnail = $this->get_screenshot_markup( $node_id, $thumb_width, $thumb_height );
		if ( ! $thumbnail ) {
			return '';
		}
		$screenshot = $this->get_screenshot_markup( $node_id, 600, 450 );
		ob_start();
		?>
		<div class="wds-lighthouse-thumbnail-container">
			<?php echo $thumbnail; ?>
		</div>
		<div class="wds-lighthouse-screenshot-container">
			<?php echo $screenshot; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	protected function get_screenshot_markup( $node_id, $scaled_frame_width, $scaled_frame_height ) {
		if ( empty( $node_id ) ) {
			return '';
		}

		$screenshot = $this->report->get_screenshot();
		$screenshot_height = (int) $this->report->get_screenshot_height();
		$screenshot_width = (int) $this->report->get_screenshot_width();
		if ( ! $screenshot || ! $screenshot_height || ! $screenshot_width ) {
			return '';
		}

		$node = $this->report->get_screenshot_node( $node_id );
		$node_details = array( 'top', 'right', 'bottom', 'left', 'width', 'height' );
		foreach ( $node_details as $node_detail ) {
			if ( ! isset( $node[ $node_detail ] ) ) {
				return '';
			}
		}
		if ( empty( $node['width'] ) || empty( $node['height'] ) ) {
			return '';
		}

		$scale = $scaled_frame_width / $screenshot_width;

		$scaled_screenshot_height = $screenshot_height * $scale;
		if ( $scaled_screenshot_height < $scaled_frame_height ) {
			$scaled_frame_height = $scaled_screenshot_height;
		}

		$frame_height = ( $scaled_frame_height / $scaled_screenshot_height ) * $screenshot_height;
		$top_offset = $this->calculate_top_offset( $node, $frame_height, $screenshot_height );

		ob_start();
		?>
		<div class="wds-lighthouse-screenshot"
		     style="
				     --element-screenshot-url: url(<?php echo esc_attr( $screenshot ); ?>);
				     --element-screenshot-width: <?php echo esc_attr( $screenshot_width ); ?>px;
				     --element-screenshot-height:<?php echo esc_attr( $screenshot_height ); ?>px;
				     --element-screenshot-scaled-height: <?php echo esc_attr( $scaled_frame_height ); ?>px;
				     --element-screenshot-scaled-width: <?php echo esc_attr( $scaled_frame_width ); ?>px;
				     --element-screenshot-scale: <?php echo esc_attr( $scale ); ?>;
				     --element-screenshot-top-offset: -<?php echo esc_attr( $top_offset ); ?>px;
				     --element-screenshot-highlight-width: <?php echo esc_attr( $node['width'] ); ?>px;
				     --element-screenshot-highlight-height: <?php echo esc_attr( $node['height'] ); ?>px;
				     --element-screenshot-highlight-top: <?php echo esc_attr( $node['top'] ); ?>px;
				     --element-screenshot-highlight-left: <?php echo esc_attr( $node['left'] ); ?>px;
				     --element-screenshot-highlight-left-width: <?php echo esc_attr( $node['left'] + $node['width'] ); ?>px;
				     --element-screenshot-highlight-top-height: <?php echo esc_attr( $node['top'] + $node['height'] ); ?>px;
				     ">
			<div class="wds-lighthouse-screenshot-inner">
				<div class="wds-lighthouse-screenshot-frame">
					<div class="wds-lighthouse-screenshot-image"></div>
					<div class="wds-lighthouse-screenshot-marker"></div>
					<div class="wds-lighthouse-screenshot-clip"></div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function calculate_top_offset( $node, $frame_height, $screenshot_height ) {
		if ( $node['height'] > $frame_height ) {
			// The highlighted element is too large to fit in the frame, show as much of it as possible
			return $node['top'];
		}

		if ( $node['bottom'] < $frame_height ) {
			// The highlighted element is within the frame already, no offset necessary
			return 0;
		}

		$ideal_space = ( $frame_height - $node['height'] ) / 2; // Ideal space will center the element vertically
		$space_available_under = $screenshot_height - $node['bottom'];
		if ( $space_available_under < $ideal_space ) {
			return $screenshot_height - $frame_height;
		}

		$space_available_over = $screenshot_height - $space_available_under - $node['height'];
		if ( $space_available_over < $ideal_space ) {
			return 0;
		}

		return $node['top'] - $ideal_space; // Center the element
	}

	/**
	 * @return mixed
	 */
	public function get_header() {
		return $this->header;
	}

	/**
	 * @return mixed
	 */
	public function get_rows() {
		return $this->rows;
	}
}
