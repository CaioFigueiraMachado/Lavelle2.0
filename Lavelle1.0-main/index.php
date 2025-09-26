<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if(isset($_SESSION['id'])) {
    // Buscar dados do usuário do banco
    include('./conexao/conexao.php');
    $id = $_SESSION['id'];
    // Upload de foto (deve ser antes de qualquer saída HTML)
    if(isset($_POST['alterar_foto']) && isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $fotos_dir = __DIR__ . '/conexao/fotos';
        if (!is_dir($fotos_dir)) {
            mkdir($fotos_dir, 0777, true);
        }
        // Apagar foto anterior se existir
        $sql_old = "SELECT foto FROM usuarios WHERE id = '$id'";
        $result_old = $mysqli->query($sql_old);
        if($result_old && $result_old->num_rows > 0) {
            $old = $result_old->fetch_assoc();
            if(isset($old['foto']) && $old['foto'] && file_exists($fotos_dir . '/' . basename($old['foto']))) {
                unlink($fotos_dir . '/' . basename($old['foto']));
            }
        }
        // Salvar nova foto com b nome único por usuário
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nome = 'foto_' . $id . '.' . $ext;
        if(move_uploaded_file($_FILES['foto']['tmp_name'], $fotos_dir . '/' . $foto_nome)) {
            $sql = "UPDATE usuarios SET foto = 'conexao/fotos/$foto_nome' WHERE id = '$id'";
            $mysqli->query($sql);
            header("Location: ./index.php");
            exit();
        } else {
            echo '<script>alert("Erro ao salvar foto.");</script>';
        }
    }
    $sql = "SELECT nome, email, foto FROM usuarios WHERE id = '$id'";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavelle - Perfumaria Premium - Home</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Banner topo - imagem fullwidth acima do carrossel */
        .top-banner {
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            z-index: 1;
            display: block;
            background: #ffffffff;
        }
        .top-banner img {
            width: 100vw;
            max-width: 100%;
            height: auto;
            display: block;
        }
        @media (max-width: 900px) {
            .top-banner img { height: auto; }
        }
        /* Carrossel */
        .banner-carousel {
            margin: 0 auto 3rem auto;
            background: #fff;
            padding-bottom: 2rem;
        }
        .carousel-wrapper {
            position: relative;
            max-width: 900px;
            margin: 2rem auto 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .carousel {
            width: 100%;
            overflow: hidden;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fafafa;
            border-radius: 20px;
        }
        .carousel-inner {
            display: flex;
            transition: transform 0.5s;
            width: 100%;
        }
        .carousel-item {
            min-width: 100%;
            max-width: 100%;
            display: block;
            border-radius: 20px;
        }
        .carousel-indicators {
            text-align: center;
            margin-top: 1rem;
        }
        .indicator {
            display: inline-block;
            margin: 0 6px;
            width: 14px;
            height: 14px;
            background: #e8ddd4;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s;
        }
        .indicator.active {
            background: #4a0e03;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="container">
    <?php if(isset($_SESSION['id'])): ?>
    <?php endif; ?>
    <!-- Modal de Perfil -->
    <?php if(isset($_SESSION['id'])): ?>
    <div id="profileModal" class="modal" style="display:none;">
        <div class="container" style="max-width:400px; margin:5rem auto; background:#fff; padding:2rem; border-radius:15px; box-shadow:0 2px 10px rgba(0,0,0,0.08);">
            <h2 style="color:#8b4b8c; text-align:center;">Meu Perfil</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div style="text-align:center; margin-bottom:1rem;">
                    <img src="<?php echo isset($user['foto']) && $user['foto'] ? $user['foto'] : 'https://via.placeholder.com/100x100?text=Foto'; ?>" alt="Foto de perfil" style="width:100px; height:100px; border-radius:50%; object-fit:cover;">
                </div>
                <label for="foto">Alterar foto:</label>
                <input type="file" name="foto" id="foto" accept="image/*" style="margin-bottom:1rem;">
                <button type="submit" name="alterar_foto" class="cta-button">Salvar Foto</button>
            </form>
            <p><strong>Nome:</strong> <?php echo isset($user['nome']) ? htmlspecialchars($user['nome']) : 'Não disponível'; ?></p>
            <p><strong>Email:</strong> <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'Não disponível'; ?></p>
            <form action="./conexao/logout.php" method="POST">
                <button type="submit" class="cta-button" style="background:#ff6b6b;">Deslogar</button>
            </form>
            <button onclick="closeProfile()" class="back-button" style="margin-top:1rem;">Fechar</button>
        </div>
    </div>
    <?php endif; ?>
    <script>
        function openProfile() {
            document.getElementById('profileModal').style.display = 'block';
        }
        function closeProfile() {
            document.getElementById('profileModal').style.display = 'none';
        }
    </script>
            <div class="logo-container">
  <img src="logo nav.png" alt="Logo Lavelle" class="logo-img">

</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="./html/paginaprodutos.php">Nossos Produtos</a></li>
                <li><a href="./html/sobre.php">Sobre</a></li>
                <li><a href="./html/contato.php">Contato</a></li>
                <?php if(isset($_SESSION['email']) && $_SESSION['email'] === 'adm@gmail.com'): ?>
                    <li class="nav-admin">
                        <a href="./html/admin.php" style="color:#FFFFFF; font-weight:500;">Admin</a>
                        <ul style="list-style:none; margin:0; padding:0; position:absolute; background:#efdacb; border-radius:8px; box-shadow:0 2px 8px #e2acac; min-width:140px; display:none; z-index:999;">
                            <li><a href="./html/produtos_admin.php" style="color:#50382b; font-weight:500; display:block; padding:8px 16px;">GEN PRODUTOS</a></li>
                        </ul>
                    </li>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var navAdmin = document.querySelector('.nav-admin');
                        var submenu = navAdmin.querySelector('ul');
                        navAdmin.addEventListener('mouseenter', function() {
                            submenu.style.display = 'block';
                        });
                        navAdmin.addEventListener('mouseleave', function() {
                            submenu.style.display = 'none';
                        });
                    });
                    </script>
                <?php endif; ?>
            </ul>
            <div class="search-cart">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar perfumes...">
                </div>
                
                <?php if(isset($_SESSION['id'])): ?>
                    <button class="cart-icon" onclick="openProfile()" style="margin-right:0.5rem;">Perfil</button>
                <?php else: ?>
                    <a class="cta-button" href="./conexao/login.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    <section class="hero" id="home">
        <div class="container">
            <h1>Desperte Seus Sentidos</h1>
            <p>Descubra fragrâncias únicas que contam sua história</p>
            <button class="cta-button" onclick="goToProducts()">Explorar Coleção</button>
        </div>
    </section>
    
    <!-- Banner topo - imagem fullwidth acima do carrossel -->
    <div class="top-banner">
        <br><img src="banner1.jpg" alt="Banner Topo Lavelle">
    </div>

    <!-- Carrossel de imagens/banner -->
    <section class="banner-carousel">
    <div class="container">
        <div class="carousel-wrapper">
            <div class="carousel">
                <div class="carousel-inner">
                    <img src="banner1.jpg" class="carousel-item active" alt="Banner Promoção 1">
                    <img src="banner1.jpg" class="carousel-item" alt="Banner Promoção 2">
                    <img src="banner1.jpg" class="carousel-item" alt="Banner Promoção 3">
                </div>
            </div>
        </div>
        <div class="carousel-indicators">
            <!-- As bolinhas serão geradas via JavaScript, então pode deixar vazio ou com placeholders se preferir -->
        </div>
    </div>
</section>

    <!-- Categories -->
    
    <!-- Call to Action for Products -->
 
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

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLogin()">&times;</span>
            <h2>Login / Registro</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label>Nome Completo:</label>
                    <input type="text" id="userName" required>
                </div>
                <div class="form-group">
                    <label>E-mail:</label>
                    <input type="email" id="userEmail" required>
                </div>
                <div class="form-group">
                    <label>Senha:</label>
                    <input type="password" id="userPassword" required>
                </div>
                <button type="submit" class="submit-btn">Entrar / Registrar</button>
            </form>
        </div>
    </div>

    <!-- Cart Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCart()">&times;</span>
            <h2>Carrinho</h2>
            <p style="text-align: center; padding: 2rem;">
                O carrinho completo está disponível na página de produtos!<br>
                <button class="cta-button" onclick="goToProducts(); closeCart();" style="margin-top: 1rem;">
                    Ir para Produtos
                </button>
            </p>
        </div>
    </div>

    <script>
        // Carrinho persistente via localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let currentUser = null;

        function saveCart() {
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }

        function addToCart(product) {
            const idx = cart.findIndex(item => item.id === product.id);
            if (idx > -1) {
                cart[idx].quantidade += product.quantidade || 1;
            } else {
                cart.push({...product, quantidade: product.quantidade || 1});
            }
            saveCart();
            showNotification('Produto adicionado ao carrinho!');
        }

        function updateCartCount() {
            const count = cart.reduce((acc, item) => acc + item.quantidade, 0);
            const el = document.getElementById('cartCount');
            if (el) el.textContent = count;
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupForms();
            updateCartCount();
        });

        document.addEventListener('DOMContentLoaded', function() {
            setupForms();
        });

        function goToProducts(category = '') {
            window.location.href = './html/paginaprodutos.php';
        }

        function openLogin() {
            document.getElementById('loginModal').style.display = 'block';
        }
        function closeLogin() {
            document.getElementById('loginModal').style.display = 'none';
        }
        function openCart() {
            showNotification('Carrinho disponível na página de produtos!');
        }
        function closeCart() {}
        function openCheckout() {
            showNotification('Checkout disponível na página de produtos!');
        }
        function closeCheckout() {}
        function setupForms() {
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const name = document.getElementById('userName').value;
                const email = document.getElementById('userEmail').value;
                currentUser = { name, email };
                showNotification(`Bem-vindo(a), ${name}!`);
                closeLogin();
            });
        }
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => {notification.remove();}, 3000);
        }
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Carrossel de banners
        document.addEventListener('DOMContentLoaded', () => {
    const carouselInner = document.querySelector('.carousel-inner');
    const carouselItems = document.querySelectorAll('.carousel-item');
    // As setas ainda estão fora do HTML, então não precisamos buscar prevBtn/nextBtn
    const indicatorsContainer = document.querySelector('.carousel-indicators'); // Reativar esta linha

    let currentIndex = 0;

    // Remover qualquer indicador existente antes de criar novos, caso o HTML já tenha
    indicatorsContainer.innerHTML = ''; 

    // Criar indicadores dinamicamente
    carouselItems.forEach((_, index) => {
        const indicator = document.createElement('span');
        indicator.classList.add('indicator');
        if (index === 0) indicator.classList.add('active'); // Ativa o primeiro por padrão
        indicator.addEventListener('click', () => {
            goToSlide(index); // Ao clicar, vai para o slide correspondente
        });
        indicatorsContainer.appendChild(indicator);
    });

    // É importante pegar os indicadores novamente APÓS criá-los
    const indicators = document.querySelectorAll('.indicator');

    function updateCarousel() {
        const offset = -currentIndex * 100;
        carouselInner.style.transform = `translateX(${offset}%)`;

        // Atualizar indicadores para mostrar qual slide está ativo
        indicators.forEach((indicator, index) => {
            if (index === currentIndex) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
    }

    // Função para ir para um slide específico
    window.goToSlide = (index) => { // Torna global para ser acessível pelos indicadores
        currentIndex = index;
        updateCarousel();
    };

    // Funções de navegação para o auto-slide (se as setas forem reintroduzidas, elas também usariam)
    window.carouselPrev = () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : carouselItems.length - 1;
        updateCarousel();
    };

    window.carouselNext = () => {
        currentIndex = (currentIndex < carouselItems.length - 1) ? currentIndex + 1 : 0;
        updateCarousel();
    };

    // Inicializar o carrossel na primeira imagem e atualizar indicadores
    updateCarousel();

    // Auto-slide
    let autoSlideInterval = setInterval(() => {
        window.carouselNext(); // Usa a função de avançar
    }, 5000); // Muda a cada 5 segundos

    // Pausar/Retomar auto-slide ao passar o mouse
    document.querySelector('.banner-carousel').addEventListener('mouseenter', () => {
        clearInterval(autoSlideInterval);
    });
    document.querySelector('.banner-carousel').addEventListener('mouseleave', () => {
        autoSlideInterval = setInterval(() => {
            window.carouselNext();
        }, 5000);
    });
});
    </script>
</body>
</html>