<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepara a exclusão da reserva com base no ID recebido
    $sql = "DELETE FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Reserva excluída com sucesso!'); window.location.href='listagem.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir reserva!'); window.location.href='listagem.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('ID de reserva inválido!'); window.location.href='listagem.php';</script>";
}

$conn->close();
?>
