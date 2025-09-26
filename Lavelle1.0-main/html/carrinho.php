
<?php
session_start();
include('../conexao/conexao.php');

if (!isset($_SESSION['id'])) {
    header('Location: ../login com database/index.php');
    exit;
}
$user_id = $_SESSION['id'];

// Adiciona produto ao carrinho
if (isset($_POST['add']) && isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    $qtd = isset($_POST['qtd']) ? intval($_POST['qtd']) : 1;
    $check = $mysqli->query("SELECT * FROM carrinho WHERE usuario_id=$user_id AND produto_id=$produto_id");
    if ($check->num_rows > 0) {
        $mysqli->query("UPDATE carrinho SET quantidade=quantidade+$qtd WHERE usuario_id=$user_id AND produto_id=$produto_id");
    } else {
        $mysqli->query("INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES ($user_id, $produto_id, $qtd)");
    }
    header('Location: carrinho.php?msg=adicionado');
    exit;
}

// Remove produto do carrinho
if (isset($_POST['remove']) && isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    $mysqli->query("DELETE FROM carrinho WHERE usuario_id=$user_id AND produto_id=$produto_id");
    header('Location: carrinho.php?msg=removido');
    exit;
}

// Diminuir quantidade
if (isset($_POST['menos']) && isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    $item = $mysqli->query("SELECT quantidade FROM carrinho WHERE usuario_id=$user_id AND produto_id=$produto_id")->fetch_assoc();
    if ($item && $item['quantidade'] > 1) {
        $mysqli->query("UPDATE carrinho SET quantidade=quantidade-1 WHERE usuario_id=$user_id AND produto_id=$produto_id");
    } else {
        $mysqli->query("DELETE FROM carrinho WHERE usuario_id=$user_id AND produto_id=$produto_id");
    }
    header('Location: carrinho.php');
    exit;
}

// Aumentar quantidade
if (isset($_POST['mais']) && isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    $mysqli->query("UPDATE carrinho SET quantidade=quantidade+1 WHERE usuario_id=$user_id AND produto_id=$produto_id");
    header('Location: carrinho.php');
    exit;
}

// Lista produtos do carrinho
$carrinho = $mysqli->query("SELECT c.*, p.nome, p.preco, p.imagem FROM carrinho c JOIN produtos p ON c.produto_id=p.id WHERE c.usuario_id=$user_id");
$total = 0;
$itens = [];
while($item = $carrinho->fetch_assoc()) {
    $itens[] = $item;
    $total += $item['preco'] * $item['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
body {
    font-family: 'Georgia', serif;
    background-color: #f4f4f4;
    color: #333;
}
.container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
}
.section-title h2 {
    color: #8b4b8c;
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
}
.section-title p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}
.cart-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(139,75,140,0.07);
    overflow: hidden;
}
.cart-table th, .cart-table td {
    padding: 16px 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
}
.cart-table th {
    background: #8b4b8c;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    letter-spacing: 1px;
}
.cart-table tr:last-child td {
    border-bottom: none;
}
.cart-table tr:nth-child(even) {
    background: #f9f9f9;
}
.cart-table tr:hover {
    background: #f1f1f1;
}
.cart-table td img {
    max-width: 60px;
    height: auto;
    border-radius: 8px;
    border: 1px solid #eee;
}
.cart-btn {
    background: #8b4b8c;
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 1em;
    margin: 0 2px;
    transition: background-color 0.3s;
}
.cart-btn:hover {
    background: #6d3a6e;
}
.btn {
    display: inline-block;
    background: #8b4b8c;
    color: #fff;
    padding: 0.8rem 2rem;
    border-radius: 25px;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(139,75,140,0.08);
    transition: background 0.3s;
    margin: 0 8px;
}
.btn:hover {
    background: #6d3a6e;
}
.btn-outline {
    background: #fff;
    color: #8b4b8c;
    border: 2px solid #8b4b8c;
}
.btn-outline:hover {
    background: #8b4b8c;
    color: #fff;
}
.cart-empty {
    text-align: center;
    padding: 60px 0;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(139,75,140,0.07);
    margin-bottom: 2rem;
}
.cart-total {
    font-size: 1.6rem;
    font-weight: bold;
    color: #8b4b8c;
    margin-bottom: 1.5rem;
    text-align: right;
    width: 100%;
}
@media (max-width: 900px) {
    .container { padding: 0 8px; }
    .cart-table th, .cart-table td { padding: 12px 6px; }
    .cart-table td img { max-width: 40px; }
}
@media (max-width: 600px) {
    .section-title h2 { font-size: 1.2rem; }
    .btn, .btn-outline { padding: 0.6rem 1rem; font-size: 0.9rem; }
    .cart-table { font-size: 0.95rem; }
}
    </style>
</head>
<body>
    <div id="cart" class="page">
        <section class="cart-section">
            <div class="container">
                <div class="section-title">
                    <h2>Meu Carrinho</h2>
                    <p>Revise seus itens antes de finalizar a compra</p>
                </div>
                <?php if (count($itens) === 0): ?>
                <div id="cartEmpty" class="cart-empty">
                    <i style="font-size:3rem;color:#8b4b8c;">🛒</i>
                    <h2>Seu carrinho está vazio</h2>
                    <p>Adicione produtos ao carrinho para continuar.</p>
                    <a href="paginaprodutos.php" class="btn mt-5">Continuar Comprando</a>
                </div>
                <?php else: ?>
                <div id="cartContent">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Total</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($itens as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?= htmlspecialchars($item['imagem']) ?>" width="60" style="border-radius:8px;vertical-align:middle;margin-right:8px;">
                                    <span><?= htmlspecialchars($item['nome']) ?></span>
                                </td>
                                <td>R$ <?= number_format($item['preco'],2,',','.') ?></td>
                                <td>
                                    <form method="post" style="display:inline">
                                        <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                        <button name="menos" title="Diminuir" class="cart-btn">-</button>
                                    </form>
                                    <?= $item['quantidade'] ?>
                                    <form method="post" style="display:inline">
                                        <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                        <button name="mais" title="Aumentar" class="cart-btn">+</button>
                                    </form>
                                </td>
                                <td>R$ <?= number_format($item['preco'] * $item['quantidade'],2,',','.') ?></td>
                                <td>
                                    <form method="post" style="display:inline">
                                        <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                        <button name="remove" title="Remover item" class="cart-btn" style="color:#dc3545;font-weight:bold;">&#128465;</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart-total" style="margin-top:1.5rem;font-size:1.3rem;text-align:right;">Total: <span>R$ <?= number_format($total,2,',','.') ?></span></div>
                    <div class="d-flex justify-between align-center" style="margin-top:2rem;display:flex;justify-content:space-between;align-items:center;">
                        <a href="paginaprodutos.php" class="btn btn-outline">Continuar Comprando</a>
                        <a href="finalizar.php" class="btn">Finalizar Compra</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</body>
</html>