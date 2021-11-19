import ErrorBoundary from "./components/error-boundry";
import {render} from "react-dom";
import React from "react";
import CrawlReport from "./components/crawler/crawl-report";
import UrlUtil from "./components/utils/url-util";
import NewsSitemapTab from "./components/sitemaps/news-sitemap-tab";
import Config_Values from "./es6/config-values";

(function ($, undefined) {
	const reportContainer = document.getElementById('wds-url-crawler-report');
	if (reportContainer) {
		render(
			<ErrorBoundary>
				<CrawlReport
					onActiveIssueCountChange={update_page_after_report_reload}/>
			</ErrorBoundary>,
			reportContainer
		);
	}

	const newsSitemapTab = document.getElementById('wds-news-sitemap-tab');
	if (newsSitemapTab) {
		render(<ErrorBoundary><NewsSitemapTab
			homeUrl={Config_Values.get('home_url', 'news')}
			schemaEnabled={Config_Values.get('schema_enabled', 'news')}
			enabled={Config_Values.get('enabled', 'news')}
			publication={Config_Values.get('publication', 'news')}
			postTypes={Config_Values.get('post_types', 'news')}
		/></ErrorBoundary>, newsSitemapTab);
	}

	function update_page_after_report_reload(active_issues, active_sitemap_issues) {
		const $title_issues_indicator = $('#tab_url_crawler .sui-box-header .sui-tag'),
			$crawler_tab = $('li.tab_url_crawler'),
			$label_issues_indicator = $crawler_tab.find('.sui-tag'),
			$label_tick = $crawler_tab.find('.sui-icon-check-tick'),
			$label_spinner = $crawler_tab.find('.sui-icon-loader'),
			$new_crawl_button = $('.wds-new-crawl-button'),
			$summary_number = $('.sui-summary-large'),
			$summary_icon = $('.sui-summary-large + [class*="sui-icon-"]'),
			$sitemap_issue_count = $('.wds-invisible-urls-count'),
			$title_ignore_all_button = $('.sui-box-header .wds-ignore-all').closest('div');

		if (active_issues === undefined) {
			// In progress or no data
			return;
		}

		if (active_issues > 0) {
			$title_issues_indicator.show().html(active_issues);
			$label_issues_indicator.show().html(active_issues);
			$title_ignore_all_button.show();
			$label_tick.hide();
			$summary_icon
				.removeClass('sui-icon-check-tick sui-success')
				.addClass('sui-icon-info sui-warning');
		} else {
			$title_issues_indicator.hide();
			$label_issues_indicator.hide();
			$title_ignore_all_button.hide();
			$label_tick.show();
			$summary_icon
				.removeClass('sui-icon-info sui-warning')
				.addClass('sui-icon-check-tick sui-success');
		}

		// Show active issue count in top section
		$summary_number.html(active_issues);
		$sitemap_issue_count.html(active_sitemap_issues);

		// Hide the spinner and show the new crawl button regardless of the result
		$label_spinner.hide();
		$new_crawl_button.show();
	}

	function update_progress() {
		const $container = $('.tab_url_crawler');
		if (
			!$container.find('.wds-url-crawler-progress').length
		) {
			return;
		}

		/**
		 * @param {{data:{in_progress:boolean, progress: int}}} response
		 */
		get_crawl_progress().done(function (response) {
			const in_progress = response?.data?.in_progress;
			const progress = response?.data?.progress;
			const $progress_bar = $('#tab_url_crawler .wds-progress');

			if (in_progress) {
				Wds.update_progress_bar($progress_bar, progress);
				setTimeout(update_progress, 5000);
			} else {
				Wds.update_progress_bar($progress_bar, 100);
				window.location.reload();
			}
		});
	}

	function get_crawl_progress() {
		return $.post(
			ajaxurl,
			{
				action: 'wds_get_crawl_progress',
				_wds_nonce: Wds.get('crawler', 'nonce')
			},
			() => false,
			'json'
		);
	}

	function update_sitemap_sub_section_visibility() {
		$('.wds-sitemap-toggleable').each(function () {
			const $toggleable = $(this),
				$nested_table = $toggleable.next('tr').find('.sui-table');

			if ($toggleable.find('input[type="checkbox"]').is(':checked')) {
				$nested_table.show();
			} else {
				$nested_table.hide();
			}
		});
	}

	function switch_to_native_sitemap() {
		const $button = $('#wds-switch-to-native-button');

		Wds.open_dialog(
			'wds-switch-to-native-modal',
			'wds-switch-to-native-sitemap',
			$button.attr('id')
		);
		$button.off().on('click', function () {
			$button.addClass('sui-button-onload');
			override_native(false, function () {
				window.location.href = add_query_params({
					'switched-to-native': 1
				});
			});
		});
	}

	function switch_to_smartcrawl_sitemap() {
		const $button = $('#wds-switch-to-smartcrawl-button');

		Wds.open_dialog(
			'wds-switch-to-smartcrawl-modal',
			'wds-switch-to-smartcrawl-sitemap',
			$button.attr('id')
		);
		$button.off().on('click', function () {
			$button.addClass('sui-button-onload');
			override_native(true, function () {
				window.location.href = add_query_params({
					'switched-to-sc': 1
				});
			});
		});
	}

	function add_query_params(params) {
		const current_url = window.location.href,
			current_params = new URLSearchParams(window.location.search);

		return current_url.split('?')[0] + '?' + $.param($.extend({}, {page: current_params.get('page')}, params));
	}

	function override_native(override, callback) {
		return $.post(
			ajaxurl,
			{
				action: 'wds-override-native',
				override: override ? '1' : '0',
				_wds_nonce: Wds.get('sitemaps', 'nonce')
			},
			callback,
			'json'
		);
	}

	function manually_notify_search_engines() {
		const $button = $(this);
		$button.addClass('sui-button-onload');
		return $.post(
			ajaxurl,
			{
				action: 'wds-manually-update-engines',
				_wds_nonce: Wds.get('sitemaps', 'nonce')
			},
			function () {
				Wds.show_floating_message(
					'wds-sitemap-manually-notify-search-engines',
					Wds.l10n('sitemaps', 'manually_notified_engines'),
					'success'
				);
				$button.removeClass('sui-button-onload');
			},
			'json'
		);
	}

	function manually_update_sitemap() {
		const $button = $(this);
		$button.addClass('sui-button-onload');
		return $.post(
			ajaxurl,
			{
				action: 'wds-manually-update-sitemap',
				_wds_nonce: Wds.get('sitemaps', 'nonce')
			},
			function () {
				Wds.show_floating_message(
					'wds-sitemap-manually-updated',
					Wds.l10n('sitemaps', 'manually_updated'),
					'success'
				);
				$button.removeClass('sui-button-onload');
			},
			'json'
		);
	}

	function deactivate_sitemap_module() {
		$(this).addClass('sui-button-onload');
		return $.post(
			ajaxurl,
			{
				action: 'wds-deactivate-sitemap-module',
				_wds_nonce: Wds.get('sitemaps', 'nonce')
			},
			function () {
				window.location.reload();
			},
			'json'
		);
	}

	function init() {
		window.Wds.hook_conditionals();
		window.Wds.hook_toggleables();
		window.Wds.conditional_fields();
		window.Wds.dismissible_message();
		window.Wds.vertical_tabs();
		window.Wds.reporting_schedule();

		update_progress();
		UrlUtil.removeQueryParam('crawl-in-progress');

		$(document)
			.on('change', '.wds-sitemap-toggleable input[type="checkbox"]', update_sitemap_sub_section_visibility)
			.on('change', '#wds_sitemap_options-sitemap-disable-automatic-regeneration', function () {
				const $checkbox = $(this),
					$notice = $checkbox.closest('.sui-toggle').find('.sui-notice');

				$notice.toggleClass('hidden', $checkbox.is(':checked'));
			})
			.on('click', '#wds-switch-to-native-sitemap', switch_to_native_sitemap)
			.on('click', '#wds-switch-to-smartcrawl-sitemap', switch_to_smartcrawl_sitemap)
			.on('click', '#wds-deactivate-sitemap-module', deactivate_sitemap_module)
			.on('click', '#wds-manually-update-sitemap', manually_update_sitemap)
			.on('click', '#wds-manually-notify-search-engines', manually_notify_search_engines)
		;

		$(update_sitemap_sub_section_visibility);
	}

	$(init);
})(jQuery);
