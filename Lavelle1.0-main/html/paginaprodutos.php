<!DOCTYPE html>
<html lang="pt-BR">
<head>
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Essence - Nossos Produtos</title>
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box;}
        body {font-family: 'Georgia', serif; line-height: 1.6; color: #333; background-color: #fefefe;}
        .container {max-width: 1200px; margin: 0 auto; padding: 0 20px;}
        /* Header */
        header {background-color: #4a0e03 ; padding: 1rem 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000;}
        nav {display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;}
        .logo-container {display: flex; align-items: center;}
        .logo-img {
            display: block;
            height: 60px; /* ajuste conforme necessário */
            width: auto;
            margin-right: 1.5rem;
        }
        .nav-links {display: flex; list-style: none; gap: 2rem; flex-wrap: wrap;}
        .nav-links a {text-decoration: none; color: #ffffff; font-weight: 500; transition: color 0.3s ease;}
        .nav-links a:hover {color: #C9A646;}
        .search-cart {display: flex; align-items: center; gap: 1rem;}
        .search-box {position: relative;}
        .search-box input {padding: 0.5rem 1rem; border: 2px solid #C9A646; border-radius: 25px; outline: none; width: 200px; transition: border-color 0.3s ease;}
        .search-box input:focus {border-color: #C9A646;}
        .cart-icon {position: relative; background: #C9A646; color: white; padding: 0.5rem 1rem; border: none; border-radius: 25px; cursor: pointer; transition: background 0.3s ease; font-weight: 500;}
        .cart-icon:hover {background: #8b4b8c;}
        .cart-count {position: absolute; top: -8px; right: -8px; background: #ff6b6b; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.8rem; display: flex; align-items: center; justify-content: center;}
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #f8f4f0 0%, #e8ddd4 100%);
            padding: 2rem 0;
            text-align: center;
        }
        .page-header h1 {
            font-size: 2.5rem;
            color: #C9A646;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            font-size: 1.1rem;
            color: #666;
        }
        /* Products Section */
        .products {
            padding: 4rem 0;
            background: #fafafa;
        }
        .filters {display: flex; justify-content: center; gap: 1rem; margin-bottom: 3rem; flex-wrap: wrap;}
        .filter-btn {padding: 0.5rem 1.5rem; border: 2px solid #C9A646; background: white; color: #C9A646; border-radius: 25px; cursor: pointer; transition: all 0.3s ease;}
        .filter-btn.active, .filter-btn:hover {background: #C9A646; color: white;}
        .product-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;}
        .product-card {background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease; position: relative;}
        .product-card:hover {transform: translateY(-5px);}
        .product-image {width: 100%; height: auto; background: none; border-radius: 0; display: block; margin-bottom: 1rem; cursor: pointer;}
        .favorite-btn {position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #ccc; transition: color 0.3s ease;}
        .favorite-btn.active {color: #ff6b6b;}
        .product-info h3 {font-size: 1.3rem; color: #333; margin-bottom: 0.5rem; cursor: pointer;}
        .product-info h3:hover {color: #C9A646;}
        .product-info p {color: #666; margin-bottom: 1rem; font-size: 0.9rem;}
        .product-price {font-size: 1.5rem; font-weight: bold; color: #C9A646; margin-bottom: 1rem;}
        .rating {display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;}
        .stars {color: #ffd700;}
        .add-to-cart {width: 100%; background: #C9A646; color: white; padding: 0.8rem; border: none; border-radius: 25px; cursor: pointer; transition: background 0.3s ease; margin-bottom: 0.5rem;}
        .add-to-cart:hover {background: #8b4b8c;}
        .view-details {width: 100%; background: transparent; color: #C9A646; border: 2px solid #C9A646; padding: 0.8rem; border-radius: 25px; cursor: pointer; transition: all 0.3s ease;}
        .view-details:hover {background: #C9A646; color: white;}
        /* Modal Styles */
        .modal {display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);}
        .modal-content {background-color: white; margin: 2% auto; padding: 2rem; border-radius: 15px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;}
        .close {color: #C9A646; position: absolute; top: 18px; right: 28px; font-size: 32px; font-weight: bold; cursor: pointer; z-index: 10; background: none; border: none; outline: none; transition: color 0.2s;}
        .close:hover {color: #ff6b6b;}
        /* Footer */
        footer {
            background: #4a0e03;
            color: #fff;
            padding: 3rem 0 1rem;
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .footer-section h3 {
            color: #C9A646;
            margin-bottom: 1rem;
        }
        .footer-section a {
            color: #fafafa;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        .footer-section a:hover {
            color: #C9A646;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #C9A646;
            color: #C9A646;
        }
        @media (max-width: 768px) {
            .nav-links {display: none;}
            .search-box input {width: 150px;}
            .product-grid {grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));}
            .product-details {grid-template-columns: 1fr;}
        }
        .cta-button {position: relative; background: #C9A646; color: white; padding: 0.5rem 1rem; border: none; border-radius: 25px; cursor: pointer; transition: background 0.3s ease; font-weight: 500; text-decoration: none;}
        .notification {position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem 2rem; border-radius: 5px; z-index: 3000; animation: slideIn 0.3s ease;}
        @keyframes slideIn {from { transform: translateX(100%); } to { transform: translateX(0); }}
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <div class="logo-container">
                <img src="../logo nav.png" alt="Logo Lavelle" class="logo-img">
            </div>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="#" style="color: #C9A646;">Nossos Produtos</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contato.php">Contato</a></li>
            </ul>
            <div class="search-cart">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar perfumes...">
                </div>
                <button class="cart-icon" onclick="window.location.href='carrinho.php'">Carrinho</button>
                <?php if(isset($_SESSION['id'])): ?>
                <?php else: ?>
                    <a class="cta-button" href="../conexao/login.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Nossos Produtos</h1>
            <p>Descubra nossa coleção exclusiva de fragrâncias premium</p>
        </div>
    </section>

    <!-- Products -->
    <section class="products">
        <div class="container">
            <div class="filters"></div>
<?php
include('../conexao/conexao.php');
function escape($str) { return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }
$result = $mysqli->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = [];
while($prod = $result->fetch_assoc()) {
    $prod['imagens'] = array_map('trim', explode(',', $prod['imagem']));
    $produtos[] = $prod;
}
?>
<div class="product-grid" id="productGrid">
<?php foreach($produtos as $prod): ?>
    <div class="product-card" data-id="<?= escape($prod['id']) ?>">
        <div class="product-images" style="position:relative;">
            <?php foreach($prod['imagens'] as $i => $img): ?>
                <img src="<?= escape($img) ?>" alt="<?= escape($prod['nome']) ?>" style="width:100%;max-width:180px;border-radius:12px;<?= $i > 0 ? 'display:none;' : '' ?>">
            <?php endforeach; ?>
            <?php if(count($prod['imagens']) > 1): ?>
                <button onclick="prevImage(<?= escape($prod['id']) ?>)" style="position:absolute;left:0;top:50%;transform:translateY(-50%);">&#8592;</button>
                <button onclick="nextImage(<?= escape($prod['id']) ?>)" style="position:absolute;right:0;top:50%;transform:translateY(-50%);">&#8594;</button>
            <?php endif; ?>
        </div>
        <h3 onclick="showProductDetails(<?= escape($prod['id']) ?>)"><?= escape($prod['nome']) ?></h3>
        <p><?= escape($prod['descricao']) ?></p>
        <div class="product-price">R$ <?= number_format($prod['preco'], 2, ',', '.') ?></div>
        <form method="post" action="carrinho.php" style="margin-bottom:8px;">
            <input type="hidden" name="produto_id" value="<?= escape($prod['id']) ?>">
            <input type="hidden" name="add" value="1">
            <input type="hidden" name="qtd" value="1">
            <button class="add-to-cart" type="submit">Adicionar ao Carrinho</button>
        </form>
        <button class="view-details" onclick="showProductDetails(<?= escape($prod['id']) ?>)">Ver Detalhes</button>
    </div>
<?php endforeach; ?>
</div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Contato</h3>
                    <a href="#">Telefone: (12) 9953-2672</a>
                    <a href="#">E-mail: lavelle@gmail.com</a>
                    <a href="#">Endereço Av. Monsenhor Theodomiro Lobo, 100 - Parque Res. Maria Elmira, Caçapava - SP,</a>
                </div>
                <div class="footer-section">
                    <h3>Redes Sociais</h3>
                    <a href="https://www.facebook.com/?locale=pt_BR">Facebook</a>
                    <a href="https://www.instagram.com/?next=%2F">Instagram</a>
                    <a href="https://x.com/">Twitter</a>
                </div>
                <div class="footer-section">
                    <h3>Políticas</h3>
                    <a href="#">Política de Privacidade</a>
                    <a href="#">Termos de Uso</a>
                    <a href="#">Trocas e Devoluções</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Lavelle Perfumaria. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    <script>
        // Navegação das imagens dos produtos
        let currentImages = {};
        function prevImage(id) {
            const imgs = document.querySelectorAll('.product-card[data-id="'+id+'"] .product-images img');
            if (!currentImages[id]) currentImages[id] = 0;
            currentImages[id] = (currentImages[id] - 1 + imgs.length) % imgs.length;
            imgs.forEach((img, i) => img.style.display = (i === currentImages[id]) ? 'block' : 'none');
        }
        function nextImage(id) {
            const imgs = document.querySelectorAll('.product-card[data-id="'+id+'"] .product-images img');
            if (!currentImages[id]) currentImages[id] = 0;
            currentImages[id] = (currentImages[id] + 1) % imgs.length;
            imgs.forEach((img, i) => img.style.display = (i === currentImages[id]) ? 'block' : 'none');
        }
        // Modal de detalhes
        function showProductDetails(id) {
            // Busca os dados do produto via PHP embutido
            const modal = document.getElementById('productModal');
            const details = document.getElementById('productDetailsContent');
            <?php
            $result = $mysqli->query("SELECT * FROM produtos ORDER BY id DESC");
            $produtos = [];
            while($p = $result->fetch_assoc()) {
                $p['imagens'] = array_map('trim', explode(',', $p['imagem']));
                $produtos[] = $p;
            }
            ?>
            const produtos = <?= json_encode($produtos) ?>;
            const prod = produtos.find(p => p.id == id);
            if (!prod) return;
            let imgsHtml = '';
            prod.imagens.forEach((img, i) => {
                imgsHtml += `<img src="${img}" style="width:100%;max-width:300px;border-radius:12px;display:${i===0?'block':'none'};margin-bottom:8px;">`;
            });
            details.innerHTML = `
                <div style='text-align:center;'>${imgsHtml}</div>
                <h2>${prod.nome}</h2>
                <p>${prod.descricao}</p>
                <div class='product-price'>R$ ${parseFloat(prod.preco).toFixed(2).replace('.', ',')}</div>
                <button class='add-to-cart' onclick='addToCart({id:${prod.id}, nome:"${prod.nome}", preco:${prod.preco}})'>Adicionar ao Carrinho</button>
            `;
            modal.style.display = 'block';
        }
        function closeProductDetails() {
            document.getElementById('productModal').style.display = 'none';
        }
    </script>
    <!-- Modal de detalhes do produto -->
    <div id="productModal" class="modal">
        <div class="modal-content" id="productModalContent" style="position:relative;">
            <span class="close" onclick="closeProductDetails()">&times;</span>
            <div id="productDetailsContent"></div>
        </div>
    </div>
</body>
</html>