<?php
require_once '../config/config.php';
require_once '../includes/Database.php';

session_start();

// ... código PHP existente ...
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Acesso - Entregadores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="page-container">
        <div class="container">
            <div class="logo-container">
                <i class="fas fa-box-open"></i>
            </div>
            
            <div class="delivery-animation">
                <div class="scene">
                    <div class="sky">
                        <div class="sun"></div>
                        <div class="cloud cloud1"></div>
                        <div class="cloud cloud2"></div>
                    </div>
                    <div class="ground">
                        <div class="road">
                            <div class="road-line"></div>
                        </div>
                        <div class="house">
                            <div class="house-body">
                                <div class="window"></div>
                                <div class="door"></div>
                            </div>
                            <div class="roof"></div>
                            <div class="chimney"></div>
                        </div>
                        <div class="truck">
                            <div class="truck-container">
                                <div class="truck-body">
                                    <div class="truck-cabin">
                                        <div class="window-cabin"></div>
                                    </div>
                                    <div class="truck-back"></div>
                                </div>
                                <div class="wheel wheel1"></div>
                                <div class="wheel wheel2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <h1>Controle de Acesso</h1>
            <p class="subtitle">Digite seu código de rastreamento ou nota fiscal</p>
            
            <?php if (isset($message)): ?>
                <div class="alert alert-<?php echo $status; ?>">
                    <i class="fas fa-<?php echo $status === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="access-form">
                <div class="form-group">
                    <div class="input-group">
                        <i class="fas fa-barcode input-icon"></i>
                        <input type="text" 
                               id="tracking_code" 
                               name="tracking_code" 
                               placeholder="Digite o código aqui"
                               required>
                    </div>
                </div>
                
                <button type="submit">
                    <i class="fas fa-key"></i>
                    Verificar Acesso
                </button>
            </form>
        </div>
        
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Sistema de Controle de Acesso</p>
        </footer>
    </div>
</body>
</html> 