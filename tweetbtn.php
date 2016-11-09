<?php
/**
 * Plugin Name: eEngine.pl - Tweet button
 * Description: Put custom tweet button after any text marked with selection when editing.
 * Version: 1.0
 * Author: eEngine.pl / sciolkowski
 * Author URI: http://eengine.pl
 * Plugin URI: http://eengine.pl
 */


// Settings page
function tweetbtn_menu(){
    add_options_page('Tweet Button Options', 'Tweet Button', 'manage_options', 'tweetbtn-menu', 'tweetbtn_options');
    add_action( 'admin_init', 'register_tweetbtn_settings' );
}

function register_tweetbtn_settings() {
    register_setting( 'tweetbtn-settings-group', 'tweetbtn_include_link' );
    register_setting( 'tweetbtn-settings-group', 'tweetbtn_include_tag' );
}

add_action('admin_menu','tweetbtn_menu');

function tweetbtn_options(){
    include('admin/tweetbtn-admin.php');
}




// Add Tweet Button to TinyMCE
$tweetbtn_css = plugins_url( 'tweetbtn.css', __FILE__ );
wp_enqueue_style( 'tweetbtn_css', $tweetbtn_css);


function tweet_button() {

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    if ( get_user_option('rich_editing') == 'true' ) {
        add_filter( 'mce_external_plugins', 'add_plugin' );
        add_filter( 'mce_buttons', 'register_button' );
    }

}

add_action('init', 'tweet_button');


function register_button( $buttons ) {
    array_push( $buttons, "|", "tweetbtn" );
    return $buttons;
}

function add_plugin( $plugin_array ) {
    $plugin_array['tweetbtn'] = plugins_url( 'tweetbtn.js', __FILE__ );;
    return $plugin_array;
}

// Build tweet from shortcode content
function tweet_function($attr, $content=null){
    global $post;
    $tweet = strip_tags($content);

    // check plugin config for links settings
    $include_link = get_option('tweetbtn_include_link');
    $include_tag = get_option('tweetbtn_include_tag');

    if ($include_link) {
        $link = get_permalink($post->ID);
        $link = shorten_url($link);
    } else {
        $link = '';
    }

    if ($include_tag) {
        $tag = ' '.$include_tag;
    } else {
        $tag = '';
    }

    // decrease max tweet length by link length
    $max_tweet_length = 145 - strlen($link) - strlen($tag);

    if (strlen($tweet) >= $max_tweet_length) {
        $tweet = substr($tweet,0,$max_tweet_length-5);
        $tweet = $tweet."...\xA".$link.$tag;
    } else {
        $tweet = $tweet."\xA".$link.$tag;
    }

    $tweet_url = 'https://twitter.com/intent/tweet?text='.urlencode($tweet);
    $tweet_btn = '<a class="tweet_btn" target="_blank" href="'.$tweet_url.'">Tweet!</a>';

    $content = $content.$tweet_btn;

    return $content;
}


// Google URL Shortener
function shorten_url($url) {
    $response = wp_remote_post( 'https://www.googleapis.com/urlshortener/v1/url', array(
        'body' => json_encode( array( 'longUrl' => esc_url_raw($url))),
        'headers' => array( 'Content-Type' => 'application/json'),
    ));

    if (is_wp_error($response)) {
        return $url;
    }

    $response = json_decode($response['body']);
    $short_url = $response->id;

    if ($short_url) {
        return $short_url;
    }
    return $url;
}

add_shortcode( 'tweet', 'tweet_function' );
