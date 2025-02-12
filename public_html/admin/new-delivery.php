<?php
require_once '../../config/config.php';
require_once '../../includes/Database.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tracking_code = filter_input(INPUT_POST, 'tracking_code', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $valid_until = $_POST['valid_until'];
    
    // Validações
    if (empty($tracking_code)) {
        $error = "O código de rastreamento é obrigatório";
    } else {
        $db = Database::getInstance()->getConnection();
        
        try {
            // Verifica se o código já existe
            $check = $db->prepare("SELECT id FROM delivery_codes WHERE tracking_code = ?");
            $check->execute([$tracking_code]);
            
            if ($check->fetch()) {
                $error = "Este código de rastreamento já está cadastrado";
            } else {
                $stmt = $db->prepare("
                    INSERT INTO delivery_codes (tracking_code, description, valid_until, created_by, status) 
                    VALUES (?, ?, ?, ?, 'active')
                ");
                
                $stmt->execute([
                    $tracking_code,
                    $description,
                    $valid_until,
                    $_SESSION['user_id']
                ]);
                
                $success = "Código cadastrado com sucesso!";
            }
        } catch (PDOException $e) {
            $error = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}

// Define a data mínima como hoje e máxima como 7 dias à frente
$min_date = date('Y-m-d\TH:i');
$max_date = date('Y-m-d\TH:i', strtotime('+7 days'));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Código - Administração</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-box-open"></i>
                <h2>Admin Panel</h2>
            </div>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="active"><a href="new-delivery.php"><i class="fas fa-plus"></i> Novo Código</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
        </nav>
        
        <main class="content">
            <header>
                <h1>Cadastrar Novo Código</h1>
                <div class="user-info">
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </header>
            
            <div class="form-container">
                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="delivery-form">
                    <div class="form-group">
                        <label for="tracking_code">
                            <i class="fas fa-barcode"></i>
                            Código de Rastreamento
                        </label>
                        <input type="text" 
                               id="tracking_code" 
                               name="tracking_code" 
                               required 
                               placeholder="Digite o código de rastreamento"
                               value="<?php echo isset($_POST['tracking_code']) ? htmlspecialchars($_POST['tracking_code']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-file-alt"></i>
                            Descrição
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Descrição da entrega (opcional)"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="valid_until">
                            <i class="fas fa-calendar-alt"></i>
                            Válido até
                        </label>
                        <input type="datetime-local" 
                               id="valid_until" 
                               name="valid_until" 
                               required
                               min="<?php echo $min_date; ?>"
                               max="<?php echo $max_date; ?>"
                               value="<?php echo isset($_POST['valid_until']) ? $_POST['valid_until'] : $max_date; ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Cadastrar Código
                        </button>
                        <a href="index.php" class="btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    // Adiciona máscara ou validação ao código de rastreamento
    document.getElementById('tracking_code').addEventListener('input', function(e) {
        // Remove caracteres especiais e espaços
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
    });
    </script>
</body>
</html> 