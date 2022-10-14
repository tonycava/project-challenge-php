<?php

function notifications_admin_menu_discord()
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
            gap: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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
            <div style="display: flex; flex-direction: column; margin-bottom: 25px">
                <h1 class="title">
                    Discord Webhook
                </h1>
                <div class="inputBox">
                    <input type="text"
                           name="webhook_discord"
                           placeholder="<?php if (get_option('webhook_discord') != null) echo get_option('webhook_discord'); else echo "Enter a discord webhook" ?>">
                </div>
                <div class="save">
                    <input
                            style="display: flex; justify-content: center; padding-inline: 64px"
                            type="submit" name="submit" value="Save Settings" class="button-primary">
                </div>
            </div>
        </form>
    </div>
    <?php
    $webhook_discord_url = "";

    if (isset($_POST['submit'])) {
        $webhook_discord_url = $_POST['webhook_discord'];
        echo "Settings save";
    }

    if ($webhook_discord_url != "" && $webhook_discord_url != get_option('webhook_discord')) {
        update_option('webhook_discord', $webhook_discord_url);
    }
    ?>

    <?php
}

?>