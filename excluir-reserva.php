<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $user_id = $_SESSION['user_id'];
    $is_admin = $_SESSION['is_admin'];

    // Verifica o user_id do criador da reserva
    $sql = "SELECT user_id FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $reserva = $result->fetch_assoc();

        // Permite exclusão apenas se for o criador ou administrador
        if ($reserva['user_id'] == $user_id || $is_admin) {
            $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "Reserva excluída com sucesso.";
            } else {
                echo "Erro ao excluir a reserva: " . $stmt->error;
            }
        } else {
            echo "Acesso negado. Você não tem permissão para excluir esta reserva000.";
        }
    } else {
        echo "Reserva não encontrada.";
    }

    $stmt->close();
    header("Location: listagem.php");
    exit;
}
?>
