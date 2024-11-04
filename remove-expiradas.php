<?php
require_once 'config.php';

$conn->query("DELETE FROM reservas WHERE data < CURDATE() OR (data = CURDATE() AND hora_fim < CURTIME())");
echo "Reservas expiradas removidas com sucesso.";
?>
