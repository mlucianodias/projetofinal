<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["logado"]) || !$_SESSION["is_admin"]) {
    header("Location: index.php");
    exit;
}

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
        $nome = $_POST['nome'];
        $senha = md5($_POST['senha']);
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

        $stmt = $conn->prepare("INSERT INTO usuarios (nome, senha, is_admin) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nome, $senha, $isAdmin);
        if ($stmt->execute()) {
            $mensagem = "Usuário cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar usuário: " . $stmt->error;
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $mensagem = "Usuário excluído com sucesso!";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] === 'alterar_senha') {
        $id = intval($_POST['id']);
        $novaSenha = md5($_POST['nova_senha']);
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $novaSenha, $id);
        if ($stmt->execute()) {
            $mensagem = "Senha alterada com sucesso!";
        }
    }
}

$usuarios = $conn->query("SELECT * FROM usuarios ORDER BY nome");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Cadastro de Usuários</h1>
    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>
    <form method="POST" class="mb-4">
        <input type="hidden" name="acao" value="cadastrar">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Usuário</label>
            <input type="text" id="nome" name="nome" class="form-control" required>
            <input type="checkbox" id="is_admin" name="is_admin" class="form-check-input">
            <label for="is_admin" class="form-check-label">Administrador</label>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" required>
            <input type="checkbox" onclick="togglePassword()"> Mostrar Senha
        </div>
       
        <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
        <button type="submit" class="btn btn-danger">Cancelar</button>
    </form>

    <h2>Usuários Cadastrados</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Administrador</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user["nome"]) ?></td>
                    <td><?= $user["is_admin"] ? "Sim" : "Não" ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirma a exclusão deste usuário?');">Excluir</button>
                        </form>
                        <button class="btn btn-warning btn-sm" onclick="document.getElementById('alterar-senha-<?= $user['id'] ?>').style.display='block'">Alterar Senha</button>
                        <div id="alterar-senha-<?= $user['id'] ?>" style="display:none;">
                            <form method="POST" class="mt-2">
                                <input type="hidden" name="acao" value="alterar_senha">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <div class="mb-3">
                                    <label for="nova_senha_<?= $user['id'] ?>" class="form-label">Nova Senha</label>
                                    <input type="password" id="nova_senha_<?= $user['id'] ?>" name="nova_senha" class="form-control" required>
                                    <input type="checkbox" onclick="togglePassword()"> Mostrar Senha
                                </div>                                
                                <button type="submit" class="btn btn-success ">Salvar Senha</button>
                                <button type="submit" class="btn btn-danger">Cancelar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary mt-3">Voltar ao Painel de Reservas</a>

    <script>
        function togglePassword() {
            var senha = document.getElementById("senha");
            senha.type = senha.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>
