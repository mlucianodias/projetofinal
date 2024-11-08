<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["logado"]) || !$_SESSION["logado"]) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$usuario_logado = $_SESSION["usuario"];

// Busca o usuário que fez a reserva
$sql = "SELECT usuario FROM reservas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reserva = $result->fetch_assoc();

// Verifica se o usuário logado é o admin ou o criador da reserva
if ($usuario_logado === "admin" || $usuario_logado === $reserva['usuario']) {
    $sql = "DELETE FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Reserva excluída com sucesso!";
    } else {
        echo "Erro ao excluir reserva!";
    }
} else {
    echo "Você não tem permissão para excluir esta reserva!";
}
?>
