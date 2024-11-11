<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$nome = $_POST['nome'];
$dataReserva = $_POST['dataReserva'];
$horaInicio = $_POST['horaInicio'];
$horaFim = $_POST['horaFim'];
$sala = $_POST['sala'];
$finalidade = $_POST['finalidade'];
$user_id = $_SESSION['user_id'];  // Usar o ID do usuário logado

// Verificar conflito de horários
$sql = "SELECT * FROM reservas WHERE data = ? AND sala = ? AND 
        ((hora_inicio < ? AND hora_fim > ?) OR 
        (hora_inicio < ? AND hora_fim > ?))";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $dataReserva, $sala, $horaFim, $horaInicio, $horaInicio, $horaFim);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Conflito de horário! Escolha outro horário ou verifique as reservas.";
} else {
    // Insere a reserva no banco de dados
    $stmt = $conn->prepare("INSERT INTO reservas (nome, data, hora_inicio, hora_fim, sala, finalidade, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $nome, $dataReserva, $horaInicio, $horaFim, $sala, $finalidade, $user_id);
    if ($stmt->execute()) {
        echo "Reserva realizada com sucesso!";
    } else {
        echo "Erro na reserva: " . $stmt->error;
    }
}
?>
