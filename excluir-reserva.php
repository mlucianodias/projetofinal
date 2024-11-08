<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Reserva excluída com sucesso.";
    } else {
        echo "Erro ao excluir a reserva: " . $stmt->error;
    }

    $stmt->close();
    header("Location: listagem.php");
    exit;
}
?>
