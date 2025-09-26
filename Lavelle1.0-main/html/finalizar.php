<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../login com database/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pagamento</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: #f8f4f0;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .checkout-container {
            max-width: 900px;
            min-height: 70vh;
            margin: 40px auto;
            background: linear-gradient(135deg, #f8f4f0 0%, #e8ddd4 100%);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(139,75,140,0.15);
            padding: 48px 48px 32px 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 {
            color: #8b4b8c;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            letter-spacing: 1px;
            font-weight: 700;
            text-shadow: 0 2px 8px #e8ddd4;
        }
        .checkout-form {
            margin-bottom: 2rem;
            text-align: left;
            width: 100%;
            max-width: 600px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(139,75,140,0.08);
            padding: 32px 32px 24px 32px;
        }
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            color: #8b4b8c;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 2px solid #e8ddd4;
            border-radius: 10px;
            outline: none;
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
            background: #fafafa;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #8b4b8c;
        }
        .order-summary {
            background: linear-gradient(135deg, #f8f4f0 0%, #e8ddd4 100%);
            border-radius: 16px;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(139,75,140,0.07);
        }
        .order-summary h3 {
            color: #8b4b8c;
            margin-bottom: 1.2rem;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .order-summary ul {
            list-style: none;
            padding: 0;
            margin-bottom: 1rem;
        }
        .order-summary li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid #e8ddd4;
            font-size: 1.08rem;
        }
        .order-summary li:last-child {
            border-bottom: none;
        }
        .order-summary li span {
            font-weight: bold;
            color: #8b4b8c;
        }
        .order-summary li small {
            color: #888;
            font-size: 0.95em;
            margin-left: 8px;
        }
        .order-total {
            font-size: 1.5rem;
            color: #4CAF50;
            text-align: right;
            font-weight: bold;
            margin-top: 1rem;
        }
        .btn-finalizar {
            display: block;
            width: 100%;
            background: linear-gradient(90deg, #4CAF50 60%, #8b4b8c 100%);
            color: #fff;
            padding: 1.1rem;
            border-radius: 25px;
            border: none;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(76,175,80,0.08);
            transition: background 0.3s;
            letter-spacing: 1px;
        }
        .btn-finalizar:hover {
            background: linear-gradient(90deg, #388e3c 60%, #6d3a6e 100%);
        }
        .btn-voltar {
            display: inline-block;
            background: #8b4b8c;
            color: #fff;
            padding: 0.7rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(139,75,140,0.08);
            transition: background 0.3s;
        }
        .btn-voltar:hover {
            background: #6d3a6e;
        }
        @media (max-width: 900px) {
            .checkout-container {
                max-width: 98vw;
                padding: 12px;
            }
            .checkout-form {
                max-width: 98vw;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Finalizar Pagamento</h2>
        <form method="post" class="checkout-form">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>
            </div>
            <div class="form-group">
                <label>Método de Pagamento:</label>
                <select name="pagamento" required>
                    <option value="pix">Pix</option>
                    <option value="cartao">Cartão de Crédito</option>
                    <option value="boleto">Boleto</option>
                </select>
            </div>
            <div class="order-summary">
                <h3>Resumo do Pedido</h3>
                <ul>
                <?php
                $user_id = $_SESSION['id'];
                $mysqli = new mysqli('localhost','root','','login');
                $carrinho = $mysqli->query("SELECT c.*, p.nome, p.preco FROM carrinho c JOIN produtos p ON c.produto_id=p.id WHERE c.usuario_id=$user_id");
                $total = 0;
                while($item = $carrinho->fetch_assoc()):
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                    <li>
                        <?= htmlspecialchars($item['nome']) ?> x <?= $item['quantidade'] ?>
                        <span>R$ <?= number_format($subtotal,2,',','.') ?></span>
                        <br><small style="color:#888;">Adicionado em: <?= date('d/m/Y H:i', strtotime($item['criado_em'])) ?></small>
                    </li>
                <?php endwhile; ?>
                </ul>
                <div class="order-total">Total: <strong>R$ <?= number_format($total,2,',','.') ?></strong></div>
            </div>
            <button type="submit" class="btn-finalizar">Confirmar Pedido</button>
        </form>
        <a href="carrinho.php" class="btn-voltar">&#8592; Voltar ao Carrinho</a>
    </div>
</body>
</html>
