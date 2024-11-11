<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $is_admin = $_SESSION['is_admin'];

    // Busca o user_id da reserva
    $sql = "SELECT user_id FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $reserva = $result->fetch_assoc();
        
        // Permite exclusão somente se for o criador ou administrador
        if ($reserva['user_id'] == $user_id || $is_admin) {
            $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "<script>alert('Reserva excluída com sucesso!'); window.location.href='listagem.php';</script>";
            } else {
                echo "<script>alert('Erro ao excluir reserva!'); window.location.href='listagem.php';</script>";
            }
        } else {
            echo "<script>alert('Você não tem permissão para excluir esta reserva.'); window.location.href='listagem.php';</script>";
        }
    } else {
        echo "<script>alert('Reserva não encontrada.'); window.location.href='listagem.php';</script>";
    }
} else {
    echo "<script>alert('ID de reserva inválido!'); window.location.href='listagem.php';</script>";
}

$conn->close();
?>
