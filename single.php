<?php

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['comment_form'] = TimberHelper::get_comment_form();

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'partials/single-password.twig', $context );
} else {
	Timber::render( array( 'single/' . $post->post_type . '.twig', 'single/single.twig' ), $context );
}
