<?php

function notifications_admin_menu_telegram()
{
    ?>
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
            padding-inline: 10px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1.5em;
            letter-spacing: 0.05em;
            z-index: 10;
            color: #45f3ff;
        }

        .inputBox span {
            position: absolute;
            left: 0;
            padding: 30px 0 10px;
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
                Telegram WebHook
            </h1>
            <div class="inputBox">
                <input type="text"
                       name="webhook_telegram"
                       placeholder="
                       <?php
                       if (get_option('webhook_telegram') != null) echo get_option('webhook_telegram');
                       else echo "Enter a webhook"
                       ?>
                ">
                <i></i>
            </div>
            <div class="save">
                <input style="width: 47%" type="submit" name="submit" value="Save Settings" class="button-primary">
            </div>
        </form>
    </div>
    <?php
    $webhook_telegram_url = "";
    $chat_id = "";

    if (isset($_POST['submit'])) {
        $webhook_telegram_url = $_POST['webhook_telegram'];
        echo "Settings save";
    }

    if ($webhook_telegram_url != "" && $webhook_telegram_url != get_option('webhook_telegram')) {
        $options = update_option('webhook_telegram', $webhook_telegram_url);
    }
    ?>

    <?php
}

?>