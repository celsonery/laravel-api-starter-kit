<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS - Cross-Origin Resource Sharing
    |--------------------------------------------------------------------------
    |
    | Configurado para SPAs externas e Apps Capacitor.js.
    | Adicione as origens do seu frontend em 'allowed_origins'.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',       // Vite dev server
        'http://localhost:8100',       // Ionic/Capacitor local
        'capacitor://localhost',       // iOS Capacitor (obrigatório!)
        'https://sind.oregon.net.br',  // Frontend producao
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Deve ser false para API stateless com tokens Bearer
    // (true apenas para SPA com cookies de sessão)
    'supports_credentials' => false,
];
