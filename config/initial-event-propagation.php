<?php

return [
    'app_code' => '',
    'set_initial_event_http_middleware' => [
        'default_user_type' => '',

        /**
         * If is set to `false` the middleware does not try to get current user
         * Defaults to `true`.
         */
        'try_auth' => true,

        /**
         * If is set to `true` the middleware does not override the InitialEvent if it was already set for current context earlier.
         * Defaults to `false`.
         */
        'preserve_existing_event' => false,

        /**
         * Middleware parses this header to get `appCode`.
         * If the header is not specified here or in a request, `appCode` is taken from `app_code` config value
         */ 
        'app_code_header' => '',

        /**
         * Middleware parses this header to get `correlationId`
         * If the header is not specified here or in a request, `correlationId` is generated from scratch.
         */
        'corelation_id_header' => '',

        /**
         * Middleware parses this header to get `timestamp`
         * If the header is not specified here or in a request, `timestamp` is generated from scratch.
         */
        'timestamp_header' => '',
    ]
];
