<?php
return [
    'token' => env('DISCORD_BOT_TOKEN'),
    'guild' => [
        'id' => env('DISCORD_GUILD'),
        'roles' => [
            'standard' => env('DISCORD_GUILD_STANDARD_ROLE'),
            'premium' => env('DISCORD_GUILD_PREMIUM_ROLE'),
        ]
    ],
];
