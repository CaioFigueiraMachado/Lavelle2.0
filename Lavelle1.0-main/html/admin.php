<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['email']) || $_SESSION['email'] !== 'adm@gmail.com') {
    header('Location: index.php');
    exit();
}
include('../conexao/conexao.php');

// Mensagem de feedback
$msg = '';

// INSERT
if (isset($_POST['insert'])) {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $mysqli->real_escape_string($_POST['senha']); // Salva senha sem criptografia
    // Verifica se o email já existe
    $sql_check = "SELECT id FROM usuarios WHERE email = '$email'";
    $result_check = $mysqli->query($sql_check);
    if ($result_check->num_rows > 0) {
        $msg = 'E-mail já cadastrado!';
    } else {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
        if ($mysqli->query($sql)) {
            $msg = 'Usuário inserido com sucesso!';
        } else {
            $msg = 'Erro ao inserir: ' . $mysqli->error;
        }
    }
}

// DELETE
if (isset($_POST['delete'])) {
    $id = intval($_POST['delete_id']);
    $sql = "DELETE FROM usuarios WHERE id = $id";
    if ($mysqli->query($sql)) {
        $msg = 'Usuário deletado com sucesso!';
    } else {
        $msg = 'Erro ao deletar: ' . $mysqli->error;
    }
}

// UPDATE
if (isset($_POST['update'])) {
    $id = intval($_POST['update_id']);
    $nome = $mysqli->real_escape_string($_POST['update_nome']);
    $email = $mysqli->real_escape_string($_POST['update_email']);
    $sql = "UPDATE usuarios SET nome='$nome', email='$email' WHERE id=$id";
    if ($mysqli->query($sql)) {
        $msg = 'Usuário atualizado com sucesso!';
    } else {
        $msg = 'Erro ao atualizar: ' . $mysqli->error;
    }
}

// Listar usuários
$result = $mysqli->query('SELECT id, nome, email FROM usuarios');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <style>
        body { background: #efdacb; color: #50382b; font-family: Georgia, serif; }
        .container { max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #e2acac; padding: 2rem; }
        h1 { color: #7c3b1a; text-align: center; }
        form { margin-bottom: 2rem; }
        input, button { padding: 0.5rem; border-radius: 8px; border: 1px solid #e2acac; margin-bottom: 0.5rem; }
        button { background: #7494a7; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #6c7173; }
        table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
        th, td { border: 1px solid #e2acac; padding: 0.5rem; text-align: left; }
        th { background: #efbbcc; color: #7c3b1a; }
        tr:nth-child(even) { background: #f8f4f0; }
        .msg { background: #e2acac; color: #50382b; padding: 0.5rem; border-radius: 8px; margin-bottom: 1rem; text-align: center; }
        nav { margin-bottom: 2rem; }
        nav ul { display: flex; gap: 2rem; list-style: none; justify-content: center; align-items: center; padding: 0; }
        nav a { color: #7c3b1a; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../html/paginaprodutos.php">Produtos</a></li>
            <li><a href="../html/sobre.php">Sobre</a></li>
            <li><a href="../html/contato.php">Contato</a></li>
            <?php if(isset($_SESSION['email']) && $_SESSION['email'] === 'adm@gmail.com'): ?>
                <li><a href="admin.php" >Admin</a></li>
                 <li><a href="./produtos_admin.php" style="color:#50382b; font-weight:500; display:block; padding:8px 16px;">Gen Produtos</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">
        <h1>Painel Administrativo</h1>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="POST">
            <h2>Inserir Usuário</h2>
            <input type="text" name="nome" placeholder="Nome" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <button type="submit" name="insert">Inserir</button>
        </form>
        <form method="POST">
            <h2>Deletar Usuário</h2>
            <input type="number" name="delete_id" placeholder="ID do usuário" required>
            <button type="submit" name="delete">Deletar</button>
        </form>
        <form method="POST">
            <h2>Atualizar Usuário</h2>
            <input type="number" name="update_id" placeholder="ID do usuário" required><br>
            <input type="text" name="update_nome" placeholder="Novo nome" required><br>
            <input type="email" name="update_email" placeholder="Novo email" required><br>
            <button type="submit" name="update">Atualizar</button>
        </form>
        <h2>Usuários Cadastrados</h2>
        <table>
            <tr><th>ID</th><th>Nome</th><th>Email</th></tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
