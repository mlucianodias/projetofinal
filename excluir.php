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


<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para excluir uma reserva.");
}

$reserva_id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

// Verifique se o usuário é o mesmo que fez a reserva
$stmt = $conn->prepare("SELECT usuario_id FROM reservas WHERE id = ?");
$stmt->bind_param("i", $reserva_id);
$stmt->execute();
$stmt->bind_result($reserva_usuario_id);
$stmt->fetch();

if ($reserva_usuario_id == $usuario_id) {
    // Usuário autorizado a excluir a reserva
    $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->bind_param("i", $reserva_id);
    $stmt->execute();
    echo "Reserva excluída com sucesso!";
} else {
    echo "Você não tem permissão para excluir esta reserva.";
}
?>
