<?php
require_once 'config.php';

// Remove reservas expiradas automaticamente
$sql = "DELETE FROM reservas WHERE data < CURDATE() OR (data = CURDATE() AND hora_fim < CURTIME())";
$conn->query($sql);

// Busca todas as reservas para exibir na tabela
$sql = "SELECT * FROM reservas ORDER BY data, hora_inicio";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Salas Reservadas</title>
</head>
<body class="container mt-5">
    <h1>Salas Reservadas</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Sala</th>
                <th>Data</th>
                <th>Início</th>
                <th>Término</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["nome"]); ?></td>
                    <td><?= htmlspecialchars($row["sala"]); ?></td>
                    <td><?= date('d/m/Y', strtotime($row["data"])); ?></td>
                    <td><?= $row["hora_inicio"]; ?></td>
                    <td><?= $row["hora_fim"]; ?></td>
                    <td>
                        <a href="excluir.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta reserva?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-primary">Voltar para Reservas</a>
</body>
</html>
