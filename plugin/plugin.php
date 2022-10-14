<?php
/**
 * Plugin Name: LAphant
 * Plugin URI: https://www.your-site.com/
 * Description: The plugin allows you to retrieve comments from a post to publish them on a specific Discord channel. The goal is to receive a Discord notification for each comment posted.
 * Version: 0.1
 * Author: Anthony CAVAGNÉ / Lucas ESCAFFRE
 **/

function init_plugin()
{
    add_option('webhook');
}

register_activation_hook(__FILE__, 'init_plugin');
add_action('comment_post', 'discord_notif', 10, 2);

add_action('admin_menu', 'notification_admin_menu');

function notification_admin_menu()
{
    add_menu_page('Discord', 'Discord', 'manage_options', 'notifications-admin-menu-discord', 'notifications_admin_menu_discord', 'dashicons-bell', 2);
}

function discord_notif($comment_ID)
{
    $comment_array = get_comment($comment_ID);
    $headers = ['Content-Type: application/json; charset=utf-8'];

    $webhookUrl = get_option('webhook') == null ? null : get_option('webhook');
    if ($webhookUrl == null) {
        echo "Please enter a webhook in admin interface";
        return;
    }


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.laphant.tonycava.dev/new-comment");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(
        [
            "comment_tittle" => $comment_array->comment_content,
            "webhook_url" => $webhookUrl
        ]
    ));

    curl_exec($ch);

    curl_close($ch);
}


function notifications_admin_menu_discord()
{
    ?>
    <form action="admin.php?page=notifications-admin-menu-discord" method="post">
        <div class="wrapper">
            <div>
                <h1>
                    <?php esc_html_e('Discord WebHook', 'notif_discord'); ?>
                </h1>
                <input type="text" name="webhook" minlength="32"
                       placeholder="<?php if (get_option('webhook') != null) {
                           echo get_option('webhook');
                       } else echo "Entre webhook" ?>">
                <span style="font-size:16px">Webhook URL.</span>
            </div>
        </div>
        <input type="submit" name="submit" value="Save Settings" class="button-primary">
    </form>
    <?php
    $webhookurl = "";

    if (isset($_POST['submit'])) {
        $webhookurl = $_POST['webhook'];
        echo "Settings save";
    }

    if ($webhookurl != "" && $webhookurl != get_option('webhook')) {
        $options = update_option('webhook', $webhookurl);
    }
    ?>

    <?php
}