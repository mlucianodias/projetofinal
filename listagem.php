<?php
require_once 'config.php';

// Remove reservas expiradas automaticamente
$sql = "DELETE FROM reservas WHERE data < CURDATE() OR (data = CURDATE() AND hora_fim < CURTIME())";
$conn->query($sql);

// Inicializa variáveis de pesquisa rápida
$pesquisaNome = isset($_GET['pesquisaNome']) ? $_GET['pesquisaNome'] : '';
$pesquisaSala = isset($_GET['pesquisaSala']) ? $_GET['pesquisaSala'] : '';
$pesquisaFinalidade = isset($_GET['pesquisaFinalidade']) ? $_GET['pesquisaFinalidade'] : '';

// Monta a consulta SQL com os parâmetros de pesquisa rápida
$sql = "SELECT * FROM reservas WHERE 1=1";
if ($pesquisaNome) {
    $sql .= " AND nome LIKE '%$pesquisaNome%'";
}
if ($pesquisaSala) {
    $sql .= " AND sala LIKE '%$pesquisaSala%'";
}
if ($pesquisaFinalidade) {
    $sql .= " AND finalidade LIKE '%$pesquisaFinalidade%'";
}
$sql .= " ORDER BY data, hora_inicio";
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
    <title>Salas Reservadas</title>
</head>
<body class="container mt-5">
    <h1>Salas Reservadas</h1>

    <!-- Formulário de Pesquisa Rápida -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="pesquisaNome" class="form-label">Nome</label>
            <input type="text" id="pesquisaNome" name="pesquisaNome" class="form-control" value="<?= htmlspecialchars($pesquisaNome) ?>">
        </div>
        
        <div class="col-md-4">
            <label for="pesquisaSala" class="form-label">Sala</label>
            <select id="pesquisaSala" name="pesquisaSala" class="form-select">
                <option value="">Todas as Salas</option>
                <option value="Sala do Pastor" <?= $pesquisaSala === "Sala do Pastor" ? "selected" : "" ?>>Sala do Pastor</option>
                <option value="Sala Kids 04 a 06 anos" <?= $pesquisaSala === "Sala Kids 04 a 06 anos" ? "selected" : "" ?>>Sala Kids 04 a 06 anos</option>
                <option value="Sala Kids 07 a 09 anos" <?= $pesquisaSala === "Sala Kids 07 a 09 anos" ? "selected" : "" ?>>Sala Kids 07 a 09 anos</option>
                <option value="Sala Kids 10 a 13 anos" <?= $pesquisaSala === "Sala Kids 10 a 13 anos" ? "selected" : "" ?>>Sala Kids 10 a 13 anos</option>
                <option value="Templo" <?= $pesquisaSala === "Templo" ? "selected" : "" ?>>Templo</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="pesquisaFinalidade" class="form-label">Finalidade</label>
            <select id="pesquisaFinalidade" name="pesquisaFinalidade" class="form-select">
                <option value="">Todas as Finalidades</option>
                <option value="Aconselhamento" <?= $pesquisaFinalidade === "Aconselhamento" ? "selected" : "" ?>>Aconselhamento</option>
                <option value="Cursos" <?= $pesquisaFinalidade === "Cursos" ? "selected" : "" ?>>Cursos</option>
                <option value="Ensaios" <?= $pesquisaFinalidade === "Ensaios" ? "selected" : "" ?>>Ensaios</option>
                <option value="Reunião Mensal" <?= $pesquisaFinalidade === "Reunião Mensal" ? "selected" : "" ?>>Reunião Mensal</option>
                <option value="Reunião Quinzenal" <?= $pesquisaFinalidade === "Reunião Quinzenal" ? "selected" : "" ?>>Reunião Quinzenal</option>
                <option value="GC" <?= $pesquisaFinalidade === "GC" ? "selected" : "" ?>>GC</option>
            </select>
        </div>

        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary w-10">Pesquisar</button>
            <a href="listagem.php" class="btn btn-secondary">Limpar</a>
        </div>
    </form>

    <!-- Exibe a quantidade total de reservas -->
    <p class="fw-bold">Total de Reservas: <?= $totalReservas ?></p>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Sala</th>
                <th>Data</th>
                <th>Início</th>
                <th>Término</th>
                <th>Finalidade</th>
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
                    <td><?= htmlspecialchars($row["finalidade"]); ?></td>
                    <td>
                        <a href="editar.php?id=<?= $row['id']; ?>" class="btn btn-warning">Editar</a>
                        <a href="excluir.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta reserva?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-primary">Voltar para Reservas</a>
</body>
</html>
