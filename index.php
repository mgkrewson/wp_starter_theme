<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */



/** CHANGE THIS TO TOGGLE COMING SOON PAGE */
$show_coming_soon = false;


$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo_src = wp_get_attachment_image_src( $custom_logo_id , 'full' );

$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$context['site_logo'] = $logo_src[0];
$context['is_coming_soon'] = $show_coming_soon;

$templates = array( 'index.twig' );
if ( is_home() ) {
	array_unshift( $templates, 'home.twig' );
	
}
$context['banner_image'] = get_field('banner_image');

if ($post->post_name === "home") {
    
    
    // $brands = Timber::get_posts(array('post_type' => 'brands', 'orderby' => 'order', 'order' => 'ASC'));
    // $context['brands'] = $brands;
}

if ($show_coming_soon) {
	Timber::render( 'coming-soon.twig', $context );
} else {
	Timber::render( $templates, $context );
}
