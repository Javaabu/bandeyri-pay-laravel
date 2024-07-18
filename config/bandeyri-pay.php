<?php

return [

    /*
     * Bandeyri API URL
     *
     * This is the URL of the Bandeyri API
     * */
    'bandeyri_api_url' =>  env('BANDEYRI_API_URL'),

    /*
     * Bandeyri Client ID
     *
     * This is the client ID of the Bandeyri API. You can get this from the Bandeyri pay dashboard.
     * OAuth clients are listed in the Bandeyri dashboard >  Developers > OAuth Clients
     * */
    'bandeyri_client_id' => env('BANDEYRI_CLIENT_ID'),

    /*
     * Bandeyri Client Secret
     *
     * This is the client secret of the Bandeyri API. You can get this from the Bandeyri pay dashboard.
     * OAuth clients are listed in the Bandeyri dashboard >  Developers > OAuth Clients
     * */
    'bandeyri_client_secret' => env('BANDEYRI_CLIENT_SECRET'),

    /*
     * This is the app signing secret for the Bandeyri API.
     * You can obtain it from the Bandeyri Pay dashboard.
     * OAuth clients are listed under the Bandeyri
     * dashboard > Developers > OAuth Clients.
     *
     * Click the options on the OAuth client you need the app
     * signing secret for, and select "Signing Secret"
     * and it will display the signing secret.
     */
    'bandeyri_app_signing_secret' => env('BADEYRI_APP_SIGNING_SECRET'),


    /*
     * Purpose Identifier
     *
     * This is the identifier for the purpose of the
     * transaction. Purpose can use either "id" or
     * "local_code" as the identifier.
     */
    'purpose_identifier' => env('PURPOSE_IDENTIFIER', 'id'),
];
