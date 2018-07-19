<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo_src = wp_get_attachment_image_src( $custom_logo_id , 'full' );

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['site_logo'] = $logo_src[0];

if (is_post_type_archive()) {
    $context['is_shop'] = true;
}

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );

