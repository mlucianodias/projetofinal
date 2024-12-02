<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit;
}

require_once '../config.php';

// Lista de usuários com permissão para editar
$usuariosPermitidos = [1, 7, 17]; // IDs dos usuários permitidos
$usuarioLogadoId = $_SESSION['user_id'];
$temPermissaoGlobal = in_array($usuarioLogadoId, $usuariosPermitidos);

// Verifica se o ID da reserva foi passado
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    echo "<script>alert('ID inválido.'); window.location.href='listagem-cozinha.php';</script>";
    exit;
}

// Carrega a reserva a ser editada
$sql = "SELECT * FROM reservas_cozinha WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reserva = $result->fetch_assoc();

if (!$reserva) {
    echo "<script>alert('Reserva não encontrada.'); window.location.href='listagem-cozinha.php';</script>";
    exit;
}

// Verifica se o usuário logado tem permissão para editar esta reserva
if (!$temPermissaoGlobal && $reserva['user_id'] != $usuarioLogadoId) {
    echo "<script>alert('Você não tem permissão para editar esta reserva.'); window.location.href='listagem-cozinha.php';</script>";
    exit;
}

// Lógica para atualizar a reserva
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $dataReserva = $_POST['dataReserva'];
    $horaInicio = $_POST['horaInicio'];
    $horaFim = $_POST['horaFim'];
    $finalidade = $_POST['finalidade'];

    // Verifica conflitos de horário na mesma data
    $sql = "SELECT * FROM reservas_cozinha 
            WHERE data_reserva = ? AND id != ? 
              AND ((hora_inicio < ? AND hora_fim > ?) OR 
                   (hora_inicio < ? AND hora_fim > ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissss", $dataReserva, $id, $horaFim, $horaInicio, $horaInicio, $horaFim);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Conflito de horário! A cozinha já está reservada neste horário.');</script>";
    } else {
        // Atualiza a reserva no banco de dados
        $sql = "UPDATE reservas_cozinha 
                SET nome = ?, data_reserva = ?, hora_inicio = ?, hora_fim = ?, finalidade = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nome, $dataReserva, $horaInicio, $horaFim, $finalidade, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Reserva atualizada com sucesso!'); window.location.href='listagem-cozinha.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar reserva: " . $stmt->error . "');</script>";
        }
    }
}

// Preenche os dados no formulário
$nome = $reserva['nome'];
$dataReserva = $reserva['data_reserva']; // Corrigido para data_reserva
$horaInicio = $reserva['hora_inicio'];
$horaFim = $reserva['hora_fim'];
$finalidade = $reserva['finalidade'];

$finalidades = ['Kids', 'Gerações', 'Louvor', 'Diaconato', 'Midia', 'Hero', 'Shine'];
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
    <form method="post" action="editar.php?id=<?= $id ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Requisitante</label>
            <input type="text" id="nome" name="nome" class="form-control" required value="<?= htmlspecialchars($nome) ?>">
        </div>
        <div class="mb-3">
            <label for="dataReserva" class="form-label">Data</label>
            <input type="date" id="dataReserva" name="dataReserva" class="form-control" required value="<?= $dataReserva ?>">
        </div>
        <div class="mb-3">
            <label for="horaInicio" class="form-label">Hora de Início</label>
            <input type="time" id="horaInicio" name="horaInicio" class="form-control" required value="<?= $horaInicio ?>">
        </div>
        <div class="mb-3">
            <label for="horaFim" class="form-label">Hora de Término</label>
            <input type="time" id="horaFim" name="horaFim" class="form-control" required value="<?= $horaFim ?>">
        </div>
        <div class="mb-3">
            <label for="finalidade" class="form-label">Finalidade</label>
            <select id="finalidade" name="finalidade" class="form-select" required>
                <?php foreach ($finalidades as $opcao): ?>
                    <option value="<?= $opcao ?>" <?= $opcao === $finalidade ? 'selected' : '' ?>>
                        <?= $opcao ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
    <a href="listagem-cozinha.php" class="btn btn-secondary mt-3">Cancelar</a>
</body>
</html>
