<?php
/**
 * Plugin Name: LAphant
 * Plugin URI: https://www.your-site.com/
 * Description: The plugin allows you to retrieve comments from a post to publish them on a specific Discord channel. The goal is to receive a Discord notification for each comment posted.
 * Version: 0.1
 * Author: Anthony CAVAGNÉ / Lucas ESCAFFRE
 **/

require_once('./discord.php');
require_once('./telegram.php');

function init_plugin()
{
    add_option('webhook_discord');
    add_option('webhook_telegram');
    add_option('telegram_chat_id');
    add_option('send_on_discord');
    add_option('is_send_on_telegram');
    add_option('is_send_on_discord');
}

register_activation_hook(__FILE__, 'init_plugin');
add_action('comment_post', 'notif', 10, 2);
add_action('admin_menu', 'notification_admin_menu');

function notification_admin_menu()
{
    add_menu_page('Discord', 'Discord', 'manage_options', 'notifications-admin-menu-discord', 'notifications_admin_menu_discord', 'dashicons-bell', 2);
    add_menu_page('Telegram', 'Telegram', 'manage_options', 'notifications-admin-menu-telegram', 'notifications_admin_menu_telegram', 'dashicons-bell', 2);
}

function notif($comment_ID)
{
    $comment_array = get_comment($comment_ID);
    $headers = ['Content-Type: application/json; charset=utf-8'];

    $webhook_discord_url = get_option('webhook_discord') == null ? null : get_option('webhook_discord');
    $webhook_telegram_url = get_option('webhook_telegram') == null ? null : get_option('webhook_telegram');
    $chat_telegram_id = get_option('telegram_chat_id') == null ? null : get_option('telegram_chat_id');
    $is_send_on_telegram = get_option('is_send_on_telegram') == null ? null : get_option('is_send_on_telegram');
    $is_send_on_discord = get_option('is_send_on_discord') == null ? null : get_option('is_send_on_discord');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.laphant.tonycava.dev/new-comment");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(
        [
            "comment_tittle" => $comment_array->comment_content,
            "comment_author" => $comment_array->comment_author,
            "comment_date" => $comment_array->comment_date,
            "comment_id" => $comment_ID,

            "webhook_discord_url" => $webhook_discord_url,
            "webhook_telegram_url" => $webhook_telegram_url,
            "telegram_chat_id" => $chat_telegram_id,

            "is_send_on_telegram" => $is_send_on_telegram,
            "is_send_on_discord" => $is_send_on_discord,
        ]
    ));

    curl_exec($ch);
    curl_close($ch);
}