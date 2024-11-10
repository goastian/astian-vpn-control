<?php

use Illuminate\Support\Str;

return [

    /**
     * DNS o URL actual del microservicio que se esta desarollando
     * por ejemplo :  http://clients.dominio.dom
     */
    'host' => env('APP_URL') ?: 'localhost',

    /**
     * Variable que determina la ubicacion principal del servidor de authorizacion
     * esta variable de entorno si tienes laravel puedes configurarlo en el archivo env
     * en caso contrario puedes remplazar su contenido con la url del servidor
     * por ejemplo : http://aouth2.dominio.dom
     */
    'server' => env('SERVER') ?: 'localhost:8080',

    /**
     * Indentificador del server id del microservicio que se le asigno, este ID
     * lo genera el servidor de autorizacion, esta clave solo sera necesario cuando el cliente es
     * publico
     */
    'server_id' => env('SERVER_ID') ?: null,

    /**
     * formas para pedir authorizacion, son tres formas aplicables
     * none : modo desatendido, empleado cuando la palicacion no representan ningun riesgo
     * consent: solicitara al usuario que intervenga para que otrogue la autorrizacion
     * login: modo seguro, utilizado cuando la aplicacion a conectar contiene servicios confidenciales
     * por ejemplo cuando el servicio necesita actualizar informacion de usuarios, solicitara que
     * el usuario ingrese otra vez sus credenciales para que pueda realizar estas acciones
     * valores aceptados [none, consent,login]
     */
    'prompt_mode' => env('PROMPT_MODE', 'consent') ?: 'login',

    /**
     * scopes o permisos para que los usuarios puedan accceder a las caracteristicas del cliente
     */
    'scopes' => env('CLIENT_SCOPES', ''),

    /**
     * Variable donde se manejara las credenciales de los usuarios, estas variables
     * no es necesario cambiarlas, pero si lo haces todas deben tener un nombre distinto
     */
    'ids' => [
        'jwt_token' => env('PASSPORT_TOKEN', Str::slug(env('APP_NAME', 'passport'), '_') . '_outh2_server'),
        'jwt_refresh' => env('PASSPORT_REFRESH', Str::slug(env('APP_NAME', 'passport'), '_') . '_refresh_outh2_server'),
    ],

    /**
     * ruta donde estara ubicado el login en tu aplicacion, eres libre de modificarlo
     * dependiendo de la configuracion de tu microservicio
     */
    'login' => env('WELCOME_PAGE', 'welcome'),

    /**
     * Pagina a donde debe ser redireccionado luego que se hayan
     * genereado las credenciales en el la ruta /callback , debes ajustar el valor
     * a dependiendo de la configuracion de tu microservicio
     */
    'redirect_after_login' => env('REDIRECT_TO', '/'),

    /**
     * Configuracion para la creacion de cookies, no es necesario cambiar la configuracion
     * pero puedes ajustarla a tu conveniencia
     */
    'cookie' => [
        'path' => '/',
        'domain' => env('PASSPORT_DOMAIN_SERVER'),
        'time_expires' => 10,
        'secure' => isset($_SERVER['HTTPS']) ? true : false,
        'http_only' => true,
        'same_site' => 'lax',
    ],

    /**
     * Key para permitir enviar notificaciones al server de autoriozacion
     *
     */
    'verify_notification' => env('VERIFY_NOTIFICATION'),
];
