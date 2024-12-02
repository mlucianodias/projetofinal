<?php
session_start();
require_once '../config.php';

// Remove reservas expiradas automaticamente
$sql = "DELETE FROM reservas_cozinha WHERE data_reserva < CURDATE() OR (data_reserva = CURDATE() AND hora_fim < CURTIME())";
$conn->query($sql);

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit;
}

// Lista de usuários com permissão para criar, editar e excluir
$usuariosPermitidos = [1, 7, 17]; // IDs dos usuários permitidos
$usuarioLogadoId = $_SESSION['user_id'];
$temPermissao = in_array($usuarioLogadoId, $usuariosPermitidos);

$sql = "SELECT * FROM reservas_cozinha ORDER BY data_reserva, hora_inicio";
$result = $conn->query($sql);

// Conta o total de reservas filtradas
$totalReservas = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Listagem de Reservas - Cozinha</title>
</head>
<body class="container mt-5">
    <h1 class="mb-4 text-center">Reservas da Cozinha</h1>
    <p class="fw-bold">Total de Reservas: <?= $totalReservas ?></p>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Data</th>
                <th>Início</th>
                <th>Término</th>
                <th>Finalidade</th>
                <?php if ($temPermissao): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $dataFormatada = DateTime::createFromFormat('Y-m-d', $row["data_reserva"])->format('d/m/Y');
                    $horaInicioFormatada = DateTime::createFromFormat('H:i:s', $row["hora_inicio"])->format('H:i');
                    $horaFimFormatada = DateTime::createFromFormat('H:i:s', $row["hora_fim"])->format('H:i');
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row["nome"]) ?></td>
                        <td><?= htmlspecialchars($dataFormatada) ?></td>
                        <td><?= htmlspecialchars($horaInicioFormatada) ?></td>
                        <td><?= htmlspecialchars($horaFimFormatada) ?></td>
                        <td><?= htmlspecialchars($row["finalidade"]) ?></td>
                        <?php if ($temPermissao): ?>
                            <td>
                                <a href="editar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="excluir.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta reserva?')">Excluir</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $temPermissao ? '6' : '5' ?>" class="text-center">Nenhuma reserva encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($temPermissao): ?>
        <!-- Botão visível apenas para usuários com permissão -->
        <a href="reservas_cozinha.php" class="btn btn-primary">Fazer Nova Reserva</a>
    <?php else: ?>
        <!-- Botão de voltar para usuários sem permissão -->
        <a href="../dashboard.php" class="btn btn-secondary">Voltar</a>
    <?php endif; ?>
</body>
</html>
