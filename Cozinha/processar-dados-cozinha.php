<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $dataReserva = $_POST["dataReserva"];
    $periodo = $_POST["periodo"];
    $finalidade = $_POST["finalidade"];

    $sql = "INSERT INTO reservas_cozinha (nome, data_reserva, periodo, finalidade) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $dataReserva, $periodo, $finalidade);

    if ($stmt->execute()) {
        echo "<script>alert('Reserva realizada com sucesso!'); window.location.href='listagem-cozinha.php';</script>";
    } else {
        echo "<script>alert('Erro ao realizar a reserva.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
