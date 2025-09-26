<?php
// Painel admin do carrinho
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['email']) || $_SESSION['email'] !== 'adm@gmail.com') {
    header('Location: index.php');
    exit();
}
include('../conexao/conexao.php');
$msg = '';
// INSERIR item no carrinho
if (isset($_POST['insert_carrinho'])) {
    $usuario_id = intval($_POST['usuario_id']);
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);
    $sql = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES ($usuario_id, $produto_id, $quantidade)";
    if ($mysqli->query($sql)) {
        $msg = 'Item inserido no carrinho!';
    } else {
        $msg = 'Erro ao inserir: ' . $mysqli->error;
    }
}
// DELETAR item do carrinho
if (isset($_POST['delete_carrinho'])) {
    $id = intval($_POST['delete_carrinho_id']);
    $sql = "DELETE FROM carrinho WHERE id = $id";
    if ($mysqli->query($sql)) {
        $msg = 'Item removido do carrinho!';
    } else {
        $msg = 'Erro ao remover: ' . $mysqli->error;
    }
}
// ATUALIZAR item do carrinho
if (isset($_POST['update_carrinho'])) {
    $id = intval($_POST['update_carrinho_id']);
    $usuario_id = intval($_POST['update_usuario_id']);
    $produto_id = intval($_POST['update_produto_id']);
    $quantidade = intval($_POST['update_quantidade']);
    $sql = "UPDATE carrinho SET usuario_id=$usuario_id, produto_id=$produto_id, quantidade=$quantidade WHERE id=$id";
    if ($mysqli->query($sql)) {
        $msg = 'Item atualizado!';
    } else {
        $msg = 'Erro ao atualizar: ' . $mysqli->error;
    }
}
// Listar itens do carrinho
$result = $mysqli->query('SELECT c.id, u.email, p.nome, p.preco, c.quantidade FROM carrinho c JOIN usuarios u ON c.usuario_id=u.id JOIN produtos p ON c.produto_id=p.id');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Carrinho</title>
    <style>
        body { background: #efdacb; color: #50382b; font-family: Georgia, serif; }
        .container { max-width: 800px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #e2acac; padding: 2rem; }
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
        nav ul { display: flex; gap: 2rem; list-style: none; justify-content: center; align-items: center; padding: 0; }
        nav a { color: #7c3b1a; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="admin.php">Admin</a></li>
            <li><a href="produtos_admin.php">Produtos</a></li>
            <li><a href="carrinho_admin.php">Carrinho</a></li>
        </ul>
    </nav>
    <div class="container">
        <h1>Gerenciar Carrinho</h1>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="POST">
            <h2>Inserir Item no Carrinho</h2>
            <input type="number" name="usuario_id" placeholder="ID do Usuário" required><br>
            <input type="number" name="produto_id" placeholder="ID do Produto" required><br>
            <input type="number" name="quantidade" placeholder="Quantidade" required><br>
            <button type="submit" name="insert_carrinho">Inserir Item</button>
        </form>
        <form method="POST">
            <h2>Deletar Item do Carrinho</h2>
            <input type="number" name="delete_carrinho_id" placeholder="ID do item" required>
            <button type="submit" name="delete_carrinho">Deletar Item</button>
        </form>
        <form method="POST">
            <h2>Atualizar Item do Carrinho</h2>
            <input type="number" name="update_carrinho_id" placeholder="ID do item" required><br>
            <input type="number" name="update_usuario_id" placeholder="Novo ID do Usuário" required><br>
            <input type="number" name="update_produto_id" placeholder="Novo ID do Produto" required><br>
            <input type="number" name="update_quantidade" placeholder="Nova Quantidade" required><br>
            <button type="submit" name="update_carrinho">Atualizar Item</button>
        </form>
        <h2>Itens no Carrinho</h2>
        <table>
            <tr><th>ID</th><th>Usuário</th><th>Produto</th><th>Preço</th><th>Qtd</th></tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                    <td><?= $row['quantidade'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
