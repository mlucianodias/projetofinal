<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

// Verifica se o ID foi passado e obtém os dados da reserva
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensagem = '';

// Carrega dados da reserva para verificar o criador
$sql = "SELECT * FROM reservas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reserva = $result->fetch_assoc();

// Verifica se o usuário logado é o criador da reserva ou um administrador
if ($reserva['user_id'] != $_SESSION["user_id"] && !$_SESSION["is_admin"]) {
    echo "<script>alert('Você não tem permissão para alterar essa reserva. Solicite ao seu Head!'); window.location.href='listagem.php';</script>";
    exit;
}

// Lógica para atualizar reserva
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $dataReserva = $_POST['dataReserva'];
    $horaInicio = $_POST['horaInicio'];
    $horaFim = $_POST['horaFim'];
    $sala = $_POST['sala'];
    $finalidade = $_POST['finalidade'];

    // Verifica a disponibilidade da sala na data e horário escolhidos
    $sql = "SELECT * FROM reservas WHERE data = ? AND sala = ? AND id != ? AND 
            ((hora_inicio < ? AND hora_fim > ?) OR 
             (hora_inicio < ? AND hora_fim > ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissss", $dataReserva, $sala, $id, $horaFim, $horaInicio, $horaInicio, $horaFim);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mensagem = "Conflito de horário! A sala já está reservada nesse horário. Escolha outro horário ou verifique as reservas.";
    } else {
        // Atualiza a reserva no banco de dados
        $sql = "UPDATE reservas SET nome = ?, data = ?, hora_inicio = ?, hora_fim = ?, sala = ?, finalidade = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nome, $dataReserva, $horaInicio, $horaFim, $sala, $finalidade, $id);

        if ($stmt->execute()) {
            $mensagem = "Reserva atualizada com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar reserva: " . $stmt->error;
        }
    }
}

// Atribui os valores da reserva às variáveis
$nome = $reserva['nome'];
$dataReserva = $reserva['data'];
$horaInicio = $reserva['hora_inicio'];
$horaFim = $reserva['hora_fim'];
$sala = $reserva['sala'];
$finalidade = $reserva['finalidade'];

$salasDisponiveis = ['Sala do Pastor', 'Sala Kids 04 a 06 anos', 'Sala Kids 07 a 09 anos', 'Sala Link 10 a 13 anos', 'Templo'];
$finalidades = ['Aconselhamento', 'Cursos', 'Ensaios', 'Reunião Mensal', 'Reunião Quinzenal', 'GC'];
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
        <div class="alert alert-<?= strpos($mensagem, 'sucesso') !== false ? 'success' : 'danger' ?>">
            <?= $mensagem ?>
        </div>
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

        <div class="mb-3">
            <label for="finalidade" class="form-label">Finalidade da reserva</label>
            <select id="finalidade" name="finalidade" class="form-select" required>
                <?php foreach ($finalidades as $finalidadeOp): ?>
                    <option value="<?= htmlspecialchars($finalidadeOp) ?>" <?= $finalidade === $finalidadeOp ? 'selected' : '' ?>>
                        <?= htmlspecialchars($finalidadeOp) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
    <a href="listagem.php" class="btn btn-secondary mt-3">Voltar à Lista de Reservas</a>
</body>
</html>
