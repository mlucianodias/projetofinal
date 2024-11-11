<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <title>Reserva de Sala</title>
</head>
<body class="d-flex flex-column min-vh-100 align-items-center justify-content-center">
    <div class="form-container p-4 rounded shadow-sm">
        <h1 class="text-center">Reserva de Salas de Reunião</h1>
        <form class="form_Reserva" id="formReserva" action="processar-dados.php" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Requisitante:</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="dataReserva" class="form-label">Data de Reserva:</label>
                <input type="date" id="dataReserva" name="dataReserva" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="horaInicio" class="form-label">Horário de Início:</label>
                <input type="time" id="horaInicio" name="horaInicio" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="horaFim" class="form-label">Horário de Término:</label>
                <input type="time" id="horaFim" name="horaFim" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="sala" class="form-label">Escolha uma Sala:</label>
                <select id="sala" name="sala" class="form-select" required>
                    <option value="">Selecione uma sala</option>
                    <option value="Sala do Pastor">Sala do Pastor</option>
                    <option value="Sala Kids 04 a 06 anos">Sala Kids 04 a 06 anos</option>
                    <option value="Sala Kids 07 a 09 anos">Sala Kids 07 a 09 anos</option>
                    <option value="Sala Link 10 a 13 anos">Sala Link 10 a 13 anos</option>
                    <option value="Templo">Templo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="finalidade" class="form-label">Finalidade da reserva:</label>
                <select id="finalidade" name="finalidade" class="form-select" required>
                    <option value="">Selecione uma sala</option>
                    <option value="Aconselhamento">Aconselhamento</option>
                    <option value="Cursos">Cursos</option>
                    <option value="Ensaios">Ensaios</option>
                    <option value="Reunião Mensal">Reunião Mensal</option>
                    <option value="Reunião Quinzenal">Reunião Quinzenal</option>
                    <option value="GC">GC</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Reservar</button>
            <a href="listagem.php" class="btn btn-link d-block text-center mt-2">Ver salas reservadas</a>
        </form>
    </div>
    <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) : ?>
        <a href="cadastro.php" class="btn btn-success mt-3">Cadastrar Novo Usuário</a>
    <?php endif; ?>
    <footer class="mt-auto text-center">
        <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>
    </footer>
    <script src="js/index.js"></script>
</body>
</html>
