<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit;
}

// Lista de usuários com permissão para criar, editar e excluir
$usuariosPermitidos = [1, 7, 17];
$usuarioLogadoId = $_SESSION['user_id'];
$temPermissao = in_array($usuarioLogadoId, $usuariosPermitidos);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Reservas da Cozinha</title>
</head>
<body class="d-flex flex-column align-items-center justify-content-center min-vh-100">
    <div class="form-container p-4 rounded shadow-sm">
        <h1 class="mb-4 text-center">Reservas da Cozinha</h1>
        
        <?php if ($temPermissao): ?>
            <form id="formReserva" action="processar-dados-cozinha.php" method="post">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Requisitante:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="dataReserva" class="form-label">Data de Reserva:</label>
                    <input type="date" id="dataReserva" name="dataReserva" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Período:</label><br>
                    <input type="radio" id="manha" name="periodo" value="Manhã" required>
                    <label for="manha">Manhã</label><br>
                    <input type="radio" id="noite" name="periodo" value="Noite" required>
                    <label for="noite">Noite</label><br>
                    <input type="radio" id="ambos" name="periodo" value="Ambos" required>
                    <label for="ambos">Ambos</label>
                </div>
                <div class="mb-3">
                    <label for="finalidade" class="form-label">Finalidade da reserva:</label>
                    <select id="finalidade" name="finalidade" class="form-select" required>
                        <option value="">Selecione uma finalidade</option>
                        <option value="Kids">Kids</option>
                        <option value="Gerações">Gerações</option>
                        <option value="Louvor">Louvor</option>
                        <option value="Diaconato">Diaconato</option>
                        <option value="Midia">Midia</option>
                        <option value="Hero">Hero</option>
                        <option value="Shine">Shine</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reservar cozinha</button>
            </form>
        <?php else: ?>
            <p class="text-center text-danger">Você não tem permissão para realizar reservas.</p>
        <?php endif; ?>

        <a href="listagem-cozinha.php" class="btn btn-link d-block text-center mt-2">Ver reservas da cozinha</a>
    </div>
    <div class="d-flex justify-content-center mt-3">
        <a href="../logout.php" class="btn btn-danger me-2">Logout</a>
        <a href="../dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
    </div>
</body>
</html>
