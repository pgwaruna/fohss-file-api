<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Internal allowed secrets
    |--------------------------------------------------------------------------
    |
    | To connect the internal file api microservice, secret is used
    | here reading the comma separated secrets from env file
    |
    */

    'secret_keys' => env('ALLOWED_SECRETS',null),

];
