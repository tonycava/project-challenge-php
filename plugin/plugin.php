<?php
/**
 * Plugin Name: LAphant
 * Plugin URI: https://www.your-site.com/
 * Description: LAphant.
 * Version: 0.1
 * Author: LAphant/
 **/


require "utils/consoleLog.php";

function launchPLugin()
{
    global $wp;
    $query = add_query_arg($wp->query_vars, home_url($wp->request));

    add_action('comment_post', 'show_message_function', 10, 2);

    function show_message_function($comment_ID)
    {
        $comment_array = get_comment($comment_ID);
        $comment_post_id = $comment_array->comment_post_ID;


        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://jsonplaceholder.typicode.com/todos/1");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($curl);

        curl_close($curl);
    }

}

launchPLugin();