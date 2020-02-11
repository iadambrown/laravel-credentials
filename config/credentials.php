<?php

use BeyondCode\Credentials\Credentials as CredentialsAlias;

switch (app()->environment()) {
    case 'testing':
        $file = config_path(CredentialsAlias::TESTING_CONFIG_FILE);
        break;
    case 'staging':
        $file = config_path(CredentialsAlias::STAGING_CONFIG_FILE);
        break;
    case 'production':
        $file = config_path(CredentialsAlias::PRODUCTION_CONFIG_FILE);
        break;
    case 'local':
    default:
        $file = config_path(CredentialsAlias::LOCAL_CONFIG_FILE);
        break;
}

return [

    /*
     * Defines the file that will be used to store and retrieve the credentials.
     */
    'file' => $file,

    /*
     * Defines the key that will be used to encrypt / decrypt the credentials.
     * The default is your application key. Be sure to keep this key secret!
     */
    'key'  => config('app.key'),

    'cipher' => config('app.cipher'),

];
