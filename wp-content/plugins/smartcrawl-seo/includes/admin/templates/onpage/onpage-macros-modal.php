<?php
$macros = array(
	'General'                   => Smartcrawl_Onpage_Settings::get_general_macros(),
	'Posts'                     => Smartcrawl_Onpage_Settings::get_singular_macros(),
	'Taxonomy Archives'         => Smartcrawl_Onpage_Settings::get_term_macros(),
	'Author Archives'           => Smartcrawl_Onpage_Settings::get_author_macros(),
	'Date Archives'             => Smartcrawl_Onpage_Settings::get_date_macros(),
	'Custom Post Type Archives' => Smartcrawl_Onpage_Settings::get_pt_archive_macros(),
	'Search'                    => Smartcrawl_Onpage_Settings::get_search_macros(),
	'BuddyPress Profiles'       => Smartcrawl_Onpage_Settings::get_bp_profile_macros(),
	'BuddyPress Groups'         => Smartcrawl_Onpage_Settings::get_bp_group_macros(),
); ?>

<div class="wds-conditional">
	<p>
		<select title="">
			<?php foreach ( $macros as $type => $type_macros ): ?>
				<option value="<?php echo esc_attr( $type ); ?>">
					<?php echo esc_html( $type ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>

	<?php foreach ( $macros as $type => $type_macros ): ?>
		<div class="wds-conditional-inside" data-conditional-val="<?php echo esc_attr( $type ); ?>">
			<div id="wds-show-supported-macros">
				<table class="sui-table">
					<thead>
					<tr>
						<th><?php esc_html_e( 'Macro', 'wds' ); ?></th>
						<th><?php esc_html_e( 'Gets Replaced By', 'wds' ); ?></th>
					</tr>
					</thead>
					<tfoot>
					<tr>
						<th><?php esc_html_e( 'Title', 'wds' ); ?></th>
						<th><?php esc_html_e( 'Gets Replaced By', 'wds' ); ?></th>
					</tr>
					</tfoot>
					<tbody>

					<?php foreach ( $type_macros as $macro => $label ) { ?>
						<tr>
							<td><?php echo esc_html( $macro ); ?></td>
							<td><?php echo esc_html( $label ); ?></td>
						</tr>
					<?php } ?>

					</tbody>
				</table>
			</div>
		</div>
	<?php endforeach; ?>
</div>
