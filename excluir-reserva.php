<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Reserva excluída!";
    } else {
        echo "Erro ao excluir: " . $stmt->error;
    }
} else {
    echo "ID da reserva não especificado.";
}
?>
