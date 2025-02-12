<?php
require_once '../../config/config.php';
require_once '../../includes/Database.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = Database::getInstance()->getConnection();

// Buscar todas as entregas
$stmt = $db->query("
    SELECT * FROM delivery_codes 
    ORDER BY created_at DESC
");
$deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
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
                <li class="active"><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="new-delivery.php"><i class="fas fa-plus"></i> Nova Entrega</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
        </nav>
        
        <main class="content">
            <header>
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </header>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <i class="fas fa-truck"></i>
                    <div class="stat-info">
                        <h3>Entregas Ativas</h3>
                        <p>12</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-info">
                        <h3>Concluídas Hoje</h3>
                        <p>5</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <div class="stat-info">
                        <h3>Pendentes</h3>
                        <p>3</p>
                    </div>
                </div>
            </div>
            
            <div class="deliveries-list">
                <div class="list-header">
                    <h2>Entregas Recentes</h2>
                    <a href="new-delivery.php" class="btn-new">Nova Entrega</a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Validade</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveries as $delivery): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($delivery['tracking_code']); ?></td>
                            <td><?php echo htmlspecialchars($delivery['description']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($delivery['valid_until'])); ?></td>
                            <td>
                                <span class="status-badge <?php echo $delivery['status']; ?>">
                                    <?php echo ucfirst($delivery['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $delivery['id']; ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $delivery['id']; ?>" class="btn-delete" 
                                   onclick="return confirm('Tem certeza que deseja excluir?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html> 