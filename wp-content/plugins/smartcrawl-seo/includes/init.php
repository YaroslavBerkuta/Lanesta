<?php
/**
 * General plugin initialization
 *
 * @package wpmu-dev-seo
 */

/**
 * Init WDS
 */
class Smartcrawl_Init {


	/**
	 * Init plugin
	 *
	 * @return  void
	 */
	public function __construct() {

		$this->init();

	}

	/**
	 * Init
	 *
	 * @return  void
	 */
	private function init() {

		/**
		 * Load textdomain.
		 *
		 * TODO: move to a separate method
		 */
		if ( defined( 'WPMU_PLUGIN_DIR' ) && file_exists( WPMU_PLUGIN_DIR . '/wpmu-dev-seo.php' ) ) {
			load_muplugin_textdomain( 'wds', dirname( SMARTCRAWL_PLUGIN_BASENAME ) . '/languages' );
		} else {
			load_plugin_textdomain( 'wds', false, dirname( SMARTCRAWL_PLUGIN_BASENAME ) . '/languages' );
		}

		require_once SMARTCRAWL_PLUGIN_DIR . 'core/core.php';

		Smartcrawl_Controller_Sitemap_Front::get()->run();
		Smartcrawl_Controller_Sitemap_Native::get()->run();
		Smartcrawl_Controller_Sitemap::get()->run();
		Smartcrawl_Sitemaps_Dashboard_Widget::get()->run();
		Smartcrawl_Moz_Metabox::get()->run();
		Smartcrawl_Moz_Dashboard_Widget::get()->run();
		Smartcrawl_Controller_Moz_Cron::get()->run();
		Smartcrawl_Controller_Cron::get()->run();
		Smartcrawl_Compatibility::get()->run();

		Smartcrawl_Autolinks_UI::get()->run();
		Smartcrawl_OnPage_UI::get()->run();
		Smartcrawl_Sitemap_UI::get()->run();
		Smartcrawl_SEO_Analysis_UI::get()->run();
		Smartcrawl_Readability_Analysis_UI::get()->run();
		Smartcrawl_Social_UI::get()->run();
		Smartcrawl_Controller_Analysis_Content::get()->run();
		Smartcrawl_Controller_Robots::get()->run();
		Smartcrawl_Controller_Data::get()->run();
		Smartcrawl_Controller_Checkup::get()->run();
		Smartcrawl_Controller_Plugin_Links::get()->run();
		Smartcrawl_Admin::get()->run();
		Smartcrawl_Controller_Media_Schema_Data::get()->run();
		Smartcrawl_Controller_Upgrade_Page::get()->run();
		Smartcrawl_Controller_Welcome::get()->run();
		Smartcrawl_Controller_Schema_Types::get()->run();
		Smartcrawl_Schema_Printer::run();
		Smartcrawl_Controller_Lighthouse::get()->run();
		Smartcrawl_Controller_Configs::get()->run();
		Smartcrawl_Controller_Crawler::get()->run();
		Smartcrawl_Network_Configs_Controller::get()->run();
		Smartcrawl_Sitewide_Deprecation_Controller::get()->run();
		Smartcrawl_Controller_Ajax_Search::get()->run();
		Smartcrawl_Controller_Black_Friday::get()->run();

		if ( is_admin() ) {
			Smartcrawl_Recommended_Plugins::get()->run();
			Smartcrawl_Controller_Onboard::get()->run();
			Smartcrawl_Controller_Analysis::get()->run();
			Smartcrawl_Controller_Assets::get()->run();
			Smartcrawl_White_Label::get()->run();
			Smartcrawl_Controller_Pointers::get()->run();
			Smartcrawl_Metabox::get()->run();
			Smartcrawl_Taxonomy::get()->run();
			Smartcrawl_Controller_Checkup_Progress::get()->run();
			Smartcrawl_Network_Settings_Page_Controller::get()->run();
		} else {
			Smartcrawl_Controller_Redirection::get()->run();
			Smartcrawl_Autolinks::get()->run();
			Smartcrawl_OnPage::get()->run();
			Smartcrawl_Social_Front::get()->run();
			Smartcrawl_Front::get()->run();
			Smartcrawl_Report_Permalinks_Controller::get()->run();
		}

		// Boot up the hub controller.
		Smartcrawl_Controller_Hub::serve();
		Smartcrawl_Controller_Third_Party_Import::serve();
	}

}

// instantiate the Init class.
$smartcrawl_init = new Smartcrawl_Init();
