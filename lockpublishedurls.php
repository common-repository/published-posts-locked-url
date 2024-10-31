<?php
/*
Plugin Name: Published Posts Locked URL
Plugin URI:  https://monrifnet.github.io/mnetqnlocal/
Description: Post name will not be editable when post is public
Version:     1.1
Author:      Ireneo Piccinini
Author URI:  https://www.quotidiano.net
*/
defined('ABSPATH') or die('These violent delights have violent ends');

function lockpublishedurls_post_updated($data , $postarr) {
    global $post;
    # https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_insert_post_data
    $is_public = $post->post_status == "publish" && $data["post_status"] == "publish";
    $has_changed = $post->post_name != $data["post_name"];
    if ($is_public && $has_changed) {
        $data["post_name"] = $post->post_name;
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'lockpublishedurls_post_updated', 99, 2 );

function lockpublishedurls_ajax_inline_save() {
    if (!isset($_POST['post_ID']) || !($post_ID = (int)$_POST['post_ID']))
        wp_die();
    $data = &$_POST;
    $post = get_post($post_ID);
    $is_public = $post->post_status == "publish" && $data["_status"] == "publish";
    $has_changed = $post->post_name != $data["post_name"];
    if ($is_public && $has_changed) {
        $_POST["post_name"] = $post->post_name;
    }
}
add_action( "wp_ajax_inline-save", "lockpublishedurls_ajax_inline_save", 0 );
