<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard - Sistema de Reservas</title>
    <!-- <style>
        .container-central {
            max-width: 600px;
        }
        .card-container {
            margin-bottom: 20px;
        }
    </style> -->
</head>
<body class="d-flex flex-column min-vh-100 align-items-center justify-content-center">
    <div class="form-container p-4 rounded shadow-sm">
        <h1 class="mb-4">Bem-vindo, <?= htmlspecialchars($_SESSION['nome']); ?>!</h1>
        
        <!-- Container 1: Reservas de Salas -->
        <div class="card card-container shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Reservas de Salas</h5>
                <p class="card-text">Gerencie suas reservas para reuniões e eventos.</p>
                <a href=" index.php" class="btn btn-primary w-100">Ir para Reservas de Salas</a>
            </div>
        </div>
        
        <!-- Container 2: Reservas da Cozinha para Cantina -->
        <div class="card card-container shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Reservas da Cozinha</h5>
                <p class="card-text">Organize e gerencie as reservas da cozinha.</p>
                <a href="Cozinha/reservas_cozinha.php" class="btn btn-secondary w-100">Ir para Reservas da Cozinha</a>
            </div>
        </div>

        <!-- Botão de Logout -->
        <a href="logout.php" class="btn btn-danger mt-3 w-100">Logout</a>
    </div>
</body>
</html>
