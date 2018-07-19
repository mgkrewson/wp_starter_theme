<?php

function wpdocs_theme_slug_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'textdomain' ),
        'id'            => 'main_sidebar',
        'description'   => __( 'Widgets in this area will be shown on Home page and all static pages.', 'textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widgettitle">',
        'after_title'   => '</h3>',
	) );
	register_sidebar( array(
        'name'          => __( 'Footer 1', 'textdomain' ),
        'id'            => 'footer_widgets1',
        'description'   => __( 'Widgets in this area will be shown footer Section 1.', 'textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
    ) );
	register_sidebar( array(
        'name'          => __( 'Footer 2', 'textdomain' ),
        'id'            => 'footer_widgets2',
        'description'   => __( 'Widgets in this area will be shown footer Section 2.', 'textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
    ) );
	register_sidebar( array(
        'name'          => __( 'Footer 3', 'textdomain' ),
        'id'            => 'footer_widgets3',
        'description'   => __( 'Widgets in this area will be shown footer Section 3.', 'textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );

// Push Yoast form down on post editor
add_filter( 'wpseo_metabox_prio', function() { return 'low';});


/** Woocommerce functions */
/* add_filter( 'woocommerce_show_page_title', '__return_false' );

add_action( 'init', 'jk_remove_wc_breadcrumbs' );
function jk_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

function woocommerce_template_product_description() {
	wc_get_template( 'single-product/tabs/description.php' );
  }
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 20 ); */





function timber_set_product( $post ) {
    global $product;
    
    if ( is_woocommerce() ) {
        $product = wc_get_product( $post->ID );
    }
}

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'custom-logo', array(
			'height' => 500,
			'width' => 500,
			'flex-height' => true,
			'flex-width' => true,
		 ) );
		add_theme_support( 'widgets' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		$context['footer_widgets'][0] = Timber::get_widgets('footer_widgets1');
		$context['footer_widgets'][1] = Timber::get_widgets('footer_widgets2');
		$context['footer_widgets'][2] = Timber::get_widgets('footer_widgets3');
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$context['logo_src'] = wp_get_attachment_image_src( $custom_logo_id , 'full' )[0];
		$context['foo'] = 'foo';
		$context['qux'] = 'qux';
		return $context;
	}

	function wrap_first_letters( $str ) {
		$search = '/(^[a-z]|\b[a-z])+/mi';
		$replacement = '<span class="first-letter">$1</span>';
		$result = preg_replace( $search, $replacement, $str );
		return $result;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('wrap_first_letters', new Twig_SimpleFilter('wrap_first_letters', array($this, 'wrap_first_letters')));
		return $twig;
	}

}

new StarterSite();


/******** FILTERS **********/

add_filter( 'timber_context', 'mytheme_timber_context'  );

function mytheme_timber_context( $context ) {
    $context['options'] = get_fields('option');
    return $context;
}