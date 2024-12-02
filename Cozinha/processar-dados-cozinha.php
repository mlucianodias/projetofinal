<?php
session_start();
require_once '../config.php'; // Caminho atualizado para o arquivo de configuração

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $dataReserva = $_POST["dataReserva"];
    $horaInicio = $_POST["horaInicio"];
    $horaFim = $_POST["horaFim"];
    $finalidade = $_POST["finalidade"];

    $sql = "INSERT INTO reservas_cozinha (nome, data_reserva, hora_inicio, hora_fim, finalidade) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $dataReserva, $horaInicio, $horaFim, $finalidade);

    if ($stmt->execute()) {
        echo "<script>alert('Reserva realizada com sucesso!'); window.location.href='listagem-cozinha.php';</script>";
    } else {
        echo "<script>alert('Erro ao realizar a reserva. Tente novamente.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
