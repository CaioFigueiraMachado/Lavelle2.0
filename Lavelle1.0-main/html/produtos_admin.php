<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['email']) || $_SESSION['email'] !== 'adm@gmail.com') {
    header('Location: index.php');
    exit();
}
include('../conexao/conexao.php');

$msg = '';

// INSERT produto
if (isset($_POST['insert_prod'])) {
    $nome = $mysqli->real_escape_string($_POST['prod_nome']);
    $descricao = $mysqli->real_escape_string($_POST['prod_descricao']);
    $preco = floatval($_POST['prod_preco']);
    $imagem = $mysqli->real_escape_string($_POST['prod_imagem']);
    $sql = "INSERT INTO produtos (nome, descricao, preco, imagem) VALUES ('$nome', '$descricao', $preco, '$imagem')";
    if ($mysqli->query($sql)) {
        $msg = 'Produto inserido com sucesso!';
    } else {
        $msg = 'Erro ao inserir produto: ' . $mysqli->error;
    }
}

// DELETE produto
if (isset($_POST['delete_prod'])) {
    $id = intval($_POST['delete_prod_id']);
    $sql = "DELETE FROM produtos WHERE id = $id";
    if ($mysqli->query($sql)) {
        $msg = 'Produto deletado com sucesso!';
    } else {
        $msg = 'Erro ao deletar produto: ' . $mysqli->error;
    }
}

// UPDATE produto
if (isset($_POST['update_prod'])) {
    $id = intval($_POST['update_prod_id']);
    $nome = $mysqli->real_escape_string($_POST['update_prod_nome']);
    $descricao = $mysqli->real_escape_string($_POST['update_prod_descricao']);
    $preco = floatval($_POST['update_prod_preco']);
    $imagem = $mysqli->real_escape_string($_POST['update_prod_imagem']);
    $sql = "UPDATE produtos SET nome='$nome', descricao='$descricao', preco=$preco, imagem='$imagem' WHERE id=$id";
    if ($mysqli->query($sql)) {
        $msg = 'Produto atualizado com sucesso!';
    } else {
        $msg = 'Erro ao atualizar produto: ' . $mysqli->error;
    }
}

// Listar produtos
$result = $mysqli->query('SELECT id, nome, descricao, preco, imagem FROM produtos');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos</title>
    <style>
        body { background: #efdacb; color: #50382b; font-family: Georgia, serif; }
        .container { max-width: 800px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #e2acac; padding: 2rem; }
        h1 { color: #7c3b1a; text-align: center; }
        form { margin-bottom: 2rem; }
        input, textarea, button { padding: 0.5rem; border-radius: 8px; border: 1px solid #e2acac; margin-bottom: 0.5rem; }
        button { background: #7494a7; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #6c7173; }
        table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
        th, td { border: 1px solid #e2acac; padding: 0.5rem; text-align: left; }
        th { background: #efbbcc; color: #7c3b1a; }
        tr:nth-child(even) { background: #f8f4f0; }
        .msg { background: #e2acac; color: #50382b; padding: 0.5rem; border-radius: 8px; margin-bottom: 1rem; text-align: center; }
        img { max-width: 80px; border-radius: 8px; }
        nav ul { display: flex; gap: 2rem; list-style: none; justify-content: center; align-items: center; padding: 0; }
        nav a { color: #7c3b1a; text-decoration: none; font-weight: bold; }
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
        <h1>Gerenciar Produtos</h1>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="POST">
            <h2>Inserir Produto</h2>
            <input type="text" name="prod_nome" placeholder="Nome" required><br>
            <textarea name="prod_descricao" placeholder="Descrição" required></textarea><br>
            <input type="number" step="0.01" name="prod_preco" placeholder="Preço" required><br>
            <input type="text" name="prod_imagem" placeholder="URL da Imagem"><br>
            <button type="submit" name="insert_prod">Inserir Produto</button>
        </form>
        <form method="POST">
            <h2>Deletar Produto</h2>
            <input type="number" name="delete_prod_id" placeholder="ID do produto" required>
            <button type="submit" name="delete_prod">Deletar Produto</button>
        </form>
        <form method="POST">
            <h2>Atualizar Produto</h2>
            <input type="number" name="update_prod_id" placeholder="ID do produto" required><br>
            <input type="text" name="update_prod_nome" placeholder="Novo nome" required><br>
            <textarea name="update_prod_descricao" placeholder="Nova descrição" required></textarea><br>
            <input type="number" step="0.01" name="update_prod_preco" placeholder="Novo preço" required><br>
            <input type="text" name="update_prod_imagem" placeholder="Nova URL da Imagem"><br>
            <button type="submit" name="update_prod">Atualizar Produto</button>
        </form>
        <h2>Produtos Cadastrados</h2>
        <table>
            <tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Imagem</th></tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['descricao']) ?></td>
                    <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                    <td><?php if($row['imagem']): ?><img src="<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem"><?php endif; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
