<?php

require __DIR__ . "/utils/consoleLog.php";

function launchPLugin()
{
    global $wp;
    $query = add_query_arg($wp->query_vars, home_url($wp->request));

    if (str_contains($query, "wp-comments-post.php")) {
        wp_redirect("https://laphant.tonycava.dev/", 303);
        exit;
    }
}
