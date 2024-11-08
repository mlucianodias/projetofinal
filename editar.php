<?php
require_once 'config.php';

// Verifica se o ID foi passado e obtém os dados da reserva
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém dados do formulário
    $nome = $_POST['nome'];
    $dataReserva = $_POST['dataReserva'];
    $horaInicio = $_POST['horaInicio'];
    $horaFim = $_POST['horaFim'];
    $sala = $_POST['sala'];

    // Atualiza a reserva no banco de dados
    $sql = "UPDATE reservas SET nome = ?, data = ?, hora_inicio = ?, hora_fim = ?, sala = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nome, $dataReserva, $horaInicio, $horaFim, $sala, $id);

    if ($stmt->execute()) {
        $mensagem = "Reserva atualizada com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar reserva: " . $stmt->error;
    }
} else {
    // Carrega dados da reserva se o método for GET
    $sql = "SELECT * FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reserva = $result->fetch_assoc();

    // Atribui os valores da reserva às variáveis
    $nome = $reserva['nome'];
    $dataReserva = $reserva['data'];
    $horaInicio = $reserva['hora_inicio'];
    $horaFim = $reserva['hora_fim'];
    $sala = $reserva['sala'];
}

// Carrega a lista de salas para o menu suspenso
$salasDisponiveis = ['Sala do Pastor', 'Sala Kids 04 a 06 anos', 'Sala Kids 07 a 09 anos', 'Sala Link 10 a 13 anos', 'Templo']; // Exemplo, modifique conforme as salas do sistema
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Reserva</title>
</head>
<body class="container mt-5">
    <h1>Editar Reserva</h1>

    <?php if ($mensagem): ?>
        <div class="alert alert-success"><?= $mensagem ?></div>
    <?php endif; ?>

    <form action="editar.php?id=<?= $id ?>" method="post">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" required value="<?= htmlspecialchars($nome) ?>">
        </div>

        <div class="mb-3">
            <label for="dataReserva" class="form-label">Data</label>
            <input type="date" id="dataReserva" name="dataReserva" class="form-control" required value="<?= htmlspecialchars($dataReserva) ?>">
        </div>

        <div class="mb-3">
            <label for="horaInicio" class="form-label">Hora de Início</label>
            <input type="time" id="horaInicio" name="horaInicio" class="form-control" required value="<?= htmlspecialchars($horaInicio) ?>">
        </div>

        <div class="mb-3">
            <label for="horaFim" class="form-label">Hora de Término</label>
            <input type="time" id="horaFim" name="horaFim" class="form-control" required value="<?= htmlspecialchars($horaFim) ?>">
        </div>

        <div class="mb-3">
            <label for="sala" class="form-label">Sala</label>
            <select id="sala" name="sala" class="form-select" required>
                <?php foreach ($salasDisponiveis as $salaDisponivel): ?>
                    <option value="<?= htmlspecialchars($salaDisponivel) ?>" <?= $sala === $salaDisponivel ? 'selected' : '' ?>>
                        <?= htmlspecialchars($salaDisponivel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
    <a href="listagem.php" class="btn btn-secondary mt-3">Voltar à Lista de Reservas</a>
</body>
</html>
