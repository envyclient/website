<?php
return [
    'token' => env('DISCORD_TOKEN'),
    'guild' => env('DISCORD_GUILD_ID'),

    'roles' => [
        'standard' => env('STANDARD_ROLE_ID'),
        'premium' => env('PREMIUM_ROLE_ID'),
    ]
];
