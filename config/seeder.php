<?php

// set default user password when seeding, get from .env

return [
    'DEFAULT_BACKOFFICE_PASSWORD' => env('DEFAULT_BACKOFFICE_PASSWORD', 'secret')
];
