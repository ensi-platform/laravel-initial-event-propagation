<?php

return [
    'app_code' => '',
    'set_initial_event_http_middleware' => [
        'default_user_type' => '',

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
