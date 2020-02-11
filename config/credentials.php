<?php

use BeyondCode\Credentials\Credentials;

$multipleEnvironments = false;

if (! $multipleEnvironments) {
    $file = config_path(Credentials::SINGLE_CONFIG_FILE);
} else {
    switch (config('app.env')) {
        case 'testing':
            $file = config_path(Credentials::TESTING_CONFIG_FILE);
            break;
        case 'staging':
            $file = config_path(Credentials::STAGING_CONFIG_FILE);
            break;
        case 'production':
            $file = config_path(Credentials::PRODUCTION_CONFIG_FILE);
            break;
        case 'local':
        default:
            $file = config_path(Credentials::LOCAL_CONFIG_FILE);
            break;
    }
}

return [

    /*
     * Defines the file that will be used to store and retrieve the credentials.
     */
    'file' => $file,

    /*
     * Defines if multiple files will be used, and served up depending on config('app.env')
     */
    'multiple-environments' => $multipleEnvironments,

    /*
     * Defines the key that will be used to encrypt / decrypt the credentials.
     * The default is your application key. Be sure to keep this key secret!
     */
    'key'  => config('app.key'),

    'cipher' => config('app.cipher'),

];
