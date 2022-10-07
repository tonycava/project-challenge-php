<?php
function getKey(): string{
    return getenv('DISCORD_TOKEN', true) ?: getenv('DISCORD_TOKEN');
}
