<?php

return [
    'app_code' => '',
    'set_initiator_http_middleware' => [
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
         * Middleware parses this header to get `startedAt`
         * If the header is not specified here or in a request, `startedAt` is generated from scratch.
         */
        'started_at_header' => '',
    ]
];
