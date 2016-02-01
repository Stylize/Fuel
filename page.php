<?php

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
if ( post_password_required( $post->ID ) ) {
	Timber::render( 'partials/single-password.twig', $context );
} else {
	Timber::render( array( 'partials/page-' . $post->post_name . '.twig', 'partials/page.twig' ), $context );
}
