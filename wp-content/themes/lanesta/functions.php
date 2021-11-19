<?php
/**
 * lanesta functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package lanesta
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'lanesta_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function lanesta_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on lanesta, use a find and replace
		 * to change 'lanesta' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'lanesta', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'lanesta' ),
                'menu-footer' => esc_html__( 'Footer', 'lanesta' ),
                'menu-social' => esc_html__( 'Social', 'lanesta' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'lanesta_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 100,
				'width'       => 400,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

	}
endif;
add_action( 'after_setup_theme', 'lanesta_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function lanesta_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'lanesta_content_width', 640 );
}
add_action( 'after_setup_theme', 'lanesta_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function lanesta_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'lanesta' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'lanesta' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'lanesta_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function lanesta_scripts() {
	wp_enqueue_style( 'lanesta-style',get_template_directory_uri().'/assets/css/style.min.css', array(), _S_VERSION );
    wp_deregister_script('jquery');
    wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), _S_VERSION, true );
    wp_enqueue_script( 'lanesta-ajax', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js', array(), _S_VERSION, true );
    wp_enqueue_script( 'lanesta-slider', 'https://unpkg.com/swiper@7/swiper-bundle.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'lanesta-main', get_template_directory_uri() . '/assets/js/main.min.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'lanesta_scripts' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
function woo_catalog_orderby( $orderby ) {
    unset($orderby["price"]); // Сортировка по цене по возрастанию
    unset($orderby["price-desc"]); // Сортировка по цене по убыванию
    unset($orderby["popularity"]); // Сортировка по популярности
    unset($orderby["rating"]); // Сортировка по рейтингу
    unset($orderby["date"]);    // Сортировка по дате
    unset($orderby["title"]);	 // Сортировка по названию
    unset($orderby["menu_order"]); // Сортировка по умолчанию (можно определить порядок в админ панели)
    return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "woo_catalog_orderby", 20 );

// По каким критериям мы будем осуществлять нашу сортировку
add_filter( 'woocommerce_get_catalog_ordering_args', 'woocommerce_get_catalog_ordering_name_args' );

function woocommerce_get_catalog_ordering_name_args( $args ) {
    if (isset($_GET['orderby'])) {
        switch ($_GET['orderby']) :
            case 'name_list_asc' :
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                $args['meta_key'] = '';
                break;
            case 'name_list_desc' :
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                $args['meta_key'] = '';
                break;
        endswitch;
    }

    return $args;
}
// Добавляем условия в стандартный вывод сортировки WP (выпадающий список)
function woocommerce_catalog_name_orderby( $sortby ) {
    $sortby['name_list_asc'] = 'A-Z';
    $sortby['name_list_desc'] = 'Z-A';
    return $sortby;
}
add_filter( 'woocommerce_catalog_orderby', 'woocommerce_catalog_name_orderby', 1 );