<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit;
}

// Lista de usuários com permissão para excluir
$usuariosPermitidos = [1, 7, 17];
$usuarioLogadoId = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Busca a reserva
    $sql = "SELECT user_id FROM reservas_cozinha WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $reserva = $result->fetch_assoc();

        // Verifica permissão
        if (in_array($usuarioLogadoId, $usuariosPermitidos) || $reserva['user_id'] == $usuarioLogadoId) {
            $stmt = $conn->prepare("DELETE FROM reservas_cozinha WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "<script>alert('Reserva excluída com sucesso!'); window.location.href='listagem-cozinha.php';</script>";
            } else {
                echo "<script>alert('Erro ao excluir a reserva.'); window.location.href='listagem-cozinha.php';</script>";
            }
        } else {
            echo "<script>alert('Você não tem permissão para excluir esta reserva.'); window.location.href='listagem-cozinha.php';</script>";
        }
    } else {
        echo "<script>alert('Reserva não encontrada.'); window.location.href='listagem-cozinha.php';</script>";
    }
}
?>
