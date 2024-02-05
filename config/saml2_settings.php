<?php

return [

    'strict' => true,

    'sp' => [
        'entityId' => env('SAML_SP_ENTITY_ID', 'your-entity-id'),
        'assertionConsumerService' => [
            'url' => env('SAML_SP_ACS_URL', 'your-acs-url'),
            'binding' => env('SAML_SP_ACS_BINDING', 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST'),
        ],
        'singleLogoutService' => [
            'url' => env('SAML_SP_SLS_URL', 'your-sls-url'),
            'binding' => env('SAML_SP_SLS_BINDING', 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect'),
        ],
        // Other SP settings...
    ],

    'idp' => [
        'entityId' => env('SAML_IDP_ENTITY_ID', 'idp-entity-id'),
        'singleSignOnService' => [
            'url' => env('SAML_IDP_SSO_URL', 'idp-sso-url'),
            'binding' => env('SAML_IDP_SSO_BINDING', 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect'),
        ],
        'singleLogoutService' => [
            'url' => env('SAML_IDP_SLS_URL', 'idp-sls-url'),
            'binding' => env('SAML_IDP_SLS_BINDING', 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect'),
        ],
        'x509cert' => env('SAML_IDP_X509_CERT', ''),
        // Other IdP settings...
    ],

    // Other general settings...

];
