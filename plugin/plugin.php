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
            "comment_author" => $comment_array->comment_author,
            "comment_date" => $comment_array->comment_date,
            "webhook_url" => $webhookUrl
        ]
    ));

    curl_exec($ch);
    curl_close($ch);
}


function notifications_admin_menu_discord()
{
    ?>
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Aria, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #23242a;
        }

        .title {
            color: #1ca086;
        }

        span {
            margin-top: 20px;
        }

        .box {
            position: relative;
            width: 380px;
            height: 420px;
            background: #1c1c1c;
            border-radius: 8px;
            overflow: hidden;
            margin-left: auto;
            margin-right: auto;
            transform: translateY(25%);
        }

        .box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 380px;
            height: 420px;
            background: linear-gradient(0deg, transparent, #45f3ff, #45f3ff);
            transform-origin: bottom right;
            animation: animate 6s linear infinite;
        }

        .box::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 380px;
            height: 420px;
            background: linear-gradient(0deg, transparent, #45f3ff, #45f3ff);
            transform-origin: bottom right;
            animation: animate 6s linear infinite;
            animation-delay: -3s;
        }

        @keyframes animate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .form {
            position: absolute;
            inset: 2px;
            border-radius: 8px;
            background-color: #28292d;
            z-index: 10;
            padding: 80px 40px;

            display: flex;
            flex-direction: column;
        }

        .form h2 {
            color: #45f3ff;
            font-weight: 500;
            text-align: center;
            letter-spacing: 0.1em;
        }

        .inputBox {
            position: relative;
            width: 300px;
            margin-top: 25px;

        }

        .inputBox input {
            position: relative;
            width: 100%;
            padding: 10px 10px 10px;
            background: transparent;
            border: none;
            outline: none;
            color: #23242a;
            font-size: 1.5em;
            letter-spacing: 0.05em;
            z-index: 10;

        }

        .inputBox span {
            position: absolute;
            left: 0;
            padding: 30px 0px 10px;
            font-size: 1em;
            color: #8f8f8f;
            pointer-events: none;
            letter-spacing: 0.05em;
            transition: 0.5s;
        }

        .inputBox input:valid ~ span,
        .inputBox input:focus ~ span {
            color: #45f3ff;
            transform: translateX(0px) translateY(-50px);
            font-size: 0.9em;
        }

        .inputBox i {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            border-radius: 4px;
            transition: 0.5s;
            pointer-events: none;
            z-index: 9;
        }

        .inputBox input:valid ~ i,
        .inputBox input:focus ~ i {
            height: 44px;
        }

        input[type="submit"] {
            border: none;
            outline: none;
            background: #45f3ff;
            padding: 11px 25px;
            width: 100px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;

        }
        .save {
            margin-top: 40px;
            text-align: center;
        }
    </style>
    <div class="box">
        <form class="form" action="admin.php?page=notifications-admin-menu-discord" method="post">
            <h1 class="title">
                <?php esc_html_e('Discord WebHook', 'notif_discord'); ?>
            </h1>
            <div class="inputBox">
                <input type="text" name="webhook"
                       placeholder="<?php if (get_option('webhook') != null) {
                           echo get_option('webhook');
                       } else echo "Entre webhook" ?>">
                <i></i>
            </div>
            <div class="save">
                <input style="width: 47%" type="submit" name="submit" value="Save Settings" class="button-primary">
            </div>
        </form>
    </div>
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