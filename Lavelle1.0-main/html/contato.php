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
    <title>Lavelle - Contato</title>
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
        .cart-icon {text-decoration: none; position: relative; background: #C9A646; color: white; padding: 0.5rem 1rem; border: none; border-radius: 25px; cursor: pointer; transition: background 0.3s ease; font-weight: 500;}
        .cart-icon:hover {background: #8b4b8c;}
        .cart-count {position: absolute; top: -8px; right: -8px; background: #ff6b6b; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.8rem; display: flex; align-items: center; justify-content: center;}
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #f8f4f0 0%, #e8ddd4 100%);
            padding: 3rem 0;
            text-align: center;
        }
        .page-header h1 {
            font-size: 3rem;
            color: #C9A646;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }
        .page-header p {
            font-size: 1.2rem;
            color: #666;
            animation: fadeInUp 1s ease 0.2s both;
        }
        /* Contact Section */
        .contact-section {padding: 4rem 0; background: white;}
        .contact-grid {display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-bottom: 4rem;}
        .contact-form {background: #f8f4f0; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);}
        .contact-form h2 {color: #C9A646; margin-bottom: 1.5rem; font-size: 2rem;}
        .form-group {margin-bottom: 1.5rem;}
        .form-group label {display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;}
        .form-group input, .form-group textarea, .form-group select {
            width: 100%; padding: 1rem; border: 2px solid #e8ddd4; border-radius: 10px;
            outline: none; transition: border-color 0.3s ease; font-family: inherit;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: #C9A646;
        }
        .form-group textarea {resize: vertical; min-height: 120px;}
        .submit-btn {
            width: 100%; background: #C9A646; color: white; padding: 1rem 2rem;
            border: none; border-radius: 25px; cursor: pointer; font-size: 1.1rem; transition: all 0.3s ease;
        }
        .submit-btn:hover {background: #8b4b8c; transform: translateY(-2px);}
        .contact-info {display: flex; flex-direction: column; gap: 2rem;}
        .info-card {background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;}
        .info-card:hover {transform: translateY(-5px);}
        .info-card h3 {color: #C9A646; margin-bottom: 1rem; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;}
        .info-card p {color: #666; margin-bottom: 0.5rem;}
        .info-card a {color: #C9A646; text-decoration: none; transition: color 0.3s ease;}
        .info-card a:hover {color: #8b4b8c;}
        /* Map Section */
        .map-section {padding: 4rem 0; background: #fafafa;}
        .map-section h2 {text-align: center; font-size: 2.5rem; color: #C9A646; margin-bottom: 2rem;}
        .map-container {background: linear-gradient(135deg, #f8f4f0 0%, #e8ddd4 100%); min-height: 350px; border-radius: 18px; display: flex; align-items: stretch; justify-content: stretch; padding: 0; box-shadow: 0 5px 18px rgba(139,75,140,0.08); margin: 0 auto 2rem auto; max-width: 700px; width: 100%; overflow: hidden;}
        .map-container iframe {border-radius: 18px; box-shadow: none; width: 100%; height: 100%; min-height: 350px; min-width: 100%; display: block;}
        /* FAQ Section */
        .faq-section {padding: 4rem 0; background: white;}
        .faq-section h2 {text-align: center; font-size: 2.5rem; color: #C9A646; margin-bottom: 3rem;}
        .faq-item {background: #f8f4f0; margin-bottom: 1rem; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);}
        .faq-question {background: #C9A646; color: white; padding: 1.5rem; cursor: pointer; transition: background 0.3s ease; display: flex; justify-content: space-between; align-items: center;}
        .faq-question:hover {background: #8b4b8c;}
        .faq-answer {padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: all 0.3s ease;}
        .faq-answer.active {padding: 1.5rem; max-height: 200px;}
        .faq-toggle {font-size: 1.5rem; transition: transform 0.3s ease;}
        .faq-toggle.active {transform: rotate(45deg);}
        /* Social Media */
        .social-section {padding: 4rem 0; background: #fafafa; text-align: center;}
        .social-section h2 {font-size: 2.5rem; color: #C9A646; margin-bottom: 2rem;}
        .social-links {display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;}
        .social-link {background: #C9A646; color: white; padding: 1rem 2rem; border-radius: 25px; text-decoration: none; transition: all 0.3s ease; font-weight: 500;}
        .social-link:hover {background: #8b4b8c; transform: translateY(-2px);}
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
        /* Modal Styles */
        .modal {display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);}
        .modal-content {background-color: white; margin: 5% auto; padding: 2rem; border-radius: 15px; width: 90%; max-width: 500px; max-height: 80vh; overflow-y: auto;}
        .close {color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;}
        .close:hover {color: #000;}
        .error {border-color: #ff6b6b !important;}
        .error-message {color: #ff6b6b; font-size: 0.9rem; margin-top: 0.5rem;}
        @keyframes fadeInUp {from {opacity: 0; transform: translateY(30px);} to {opacity: 1; transform: translateY(0);}}
        @keyframes slideIn {from { transform: translateX(100%); } to { transform: translateX(0); }}
        .notification {position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem 2rem; border-radius: 5px; z-index: 3000; animation: slideIn 0.3s ease;}
        @media (max-width: 768px) {
            .nav-links {display: none;}
            .search-box input {width: 150px;}
            .contact-grid {grid-template-columns: 1fr; gap: 2rem;}
            .social-links {flex-direction: column; align-items: center;}
        }
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
                <li><a href="paginaprodutos.php">Nossos Produtos</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="#" style="color: #C9A646;">Contato</a></li>
            </ul>
            <div class="search-cart">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar perfumes...">
                </div>
                <?php if(isset($_SESSION['id'])): ?>
                <?php else: ?>
                    <a class="cart-icon" href="../login com database/index.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <!-- Modal Carrinho -->
    <div id="cartModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:9999;">
        <div style="background:#fff;max-width:400px;margin:10vh auto;padding:2rem;border-radius:10px;position:relative;">
            <h2>Seu Carrinho</h2>
            <p>Aqui aparecerão os itens do carrinho.</p>
            <button onclick="closeCart()" style="position:absolute;top:10px;right:10px;font-size:1.2rem;">&times;</button>
        </div>
    </div>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Entre em Contato</h1>
            <p>Estamos aqui para ajudar você a encontrar a fragrância perfeita</p>
        </div>
    </section>
    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-form">
                    <h2>Envie sua Mensagem</h2>
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Nome Completo *</label>
                            <input type="text" id="name" name="name" required>
                            <div class="error-message" id="nameError"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail *</label>
                            <input type="email" id="email" name="email" required>
                            <div class="error-message" id="emailError"></div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <input type="tel" id="phone" name="phone">
                            <div class="error-message" id="phoneError"></div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Assunto *</label>
                            <select id="subject" name="subject" required>
                                <option value="">Selecione um assunto</option>
                                <option value="duvida">Dúvida sobre produtos</option>
                                <option value="pedido">Informações sobre pedido</option>
                                <option value="troca">Trocas e devoluções</option>
                                <option value="sugestao">Sugestões</option>
                                <option value="outro">Outro</option>
                            </select>
                            <div class="error-message" id="subjectError"></div>
                        </div>
                        <div class="form-group">
                            <label for="message">Mensagem *</label>
                            <textarea id="message" name="message" placeholder="Conte-nos como podemos ajudar..." required></textarea>
                            <div class="error-message" id="messageError"></div>
                        </div>
                        <button type="submit" class="submit-btn">Enviar Mensagem</button>
                    </form>
                </div>
                <div class="contact-info">
                    <div class="info-card">
                        <h3>Endereço</h3>
                        <p>Av. Monsenhor Theodomiro Lobo, 100</p>
                        <p>Parque Res. Maria Elmira, Caçapava - SP</p>
                        <p>CEP: 12285-050</p>
                    </div>
                    <div class="info-card">
                        <h3>Telefones</h3>
                        <p><a href="tel:+5512998516345">(12) 99851-6345</a> - WhatsApp</p>
                        <p>Atendimento: Segunda a Sexta, 9h às 18h</p>
                    </div>
                    <div class="info-card">
                        <h3>E-mail</h3>
                        <p><a href="mailto:contato@essence.com.br">contato@essence.com.br</a></p>
                        <p><a href="mailto:vendas@essence.com.br">vendas@essence.com.br</a></p>
                        <p>Resposta em até 56 horas</p>
                    </div>
                    <div class="info-card">
                        <h3>Horário de Funcionamento</h3>
                        <p><strong>Segunda a Sexta:</strong> 9h às 18h</p>
                        <p><strong>Sábado:</strong> Fechado</p>
                        <p><strong>Domingo:</strong> Fechado</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2>Nossa Localização</h2>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3669.5394011259195!2d-45.70961732485665!3d-23.11395107910983!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cc53fe5d561195%3A0xf8b1e6391017595b!2sSesi%20Ca%C3%A7apava!5e0!3m2!1spt-BR!2sbr!4v1757698776755!5m2!1spt-BR!2sbr" style="border:0;width:100%;height:100%;display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2>Perguntas Frequentes</h2>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(0)">
                    <span>Como posso saber se um perfume é original?</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Todos os nossos perfumes são 100% originais e importados diretamente dos fabricantes. Fornecemos certificado de autenticidade e nota fiscal em todas as compras.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(1)">
                    <span>Qual o prazo de entrega?</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Para São Paulo capital: 1-2 dias úteis. Para outras regiões: 3-7 dias úteis. Oferecemos frete grátis para compras acima de R$ 200,00.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(2)">
                    <span>Posso trocar se não gostar do perfume?</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Sim! Você tem 30 dias para trocar produtos lacrados. Para produtos abertos, oferecemos troca apenas em caso de defeito de fabricação.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(3)">
                    <span>Vocês têm loja física?</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Sim! Nossa loja física fica na Rua das Fragrâncias, 123 - São Paulo. Você pode visitar para conhecer pessoalmente nossos produtos e receber consultoria especializada.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(4)">
                    <span>Como escolher o perfume ideal?</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Recomendamos considerar a ocasião de uso, sua personalidade e preferências olfativas. Nossa equipe está disponível para consultoria gratuita via WhatsApp ou na loja física.</p>
                </div>
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
    <script>
        function closeCart() {
            document.getElementById('cartModal').style.display = 'none';
        }
        let currentUser = null;
        document.addEventListener('DOMContentLoaded', function() {
            setupForms();
        });
        function goToProducts() {
            window.location.href = 'produtos.html';
        }
        function validateForm() {
            let isValid = true;
            document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            const name = document.getElementById('name');
            if (name.value.trim().length < 2) {
                showFieldError('name', 'Nome deve ter pelo menos 2 caracteres');
                isValid = false;
            }
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showFieldError('email', 'E-mail inválido');
                isValid = false;
            }
            const phone = document.getElementById('phone');
            if (phone.value && phone.value.length < 10) {
                showFieldError('phone', 'Telefone deve ter pelo menos 10 dígitos');
                isValid = false;
            }
            const subject = document.getElementById('subject');
            if (!subject.value) {
                showFieldError('subject', 'Selecione um assunto');
                isValid = false;
            }
            const message = document.getElementById('message');
            if (message.value.trim().length < 10) {
                showFieldError('message', 'Mensagem deve ter pelo menos 10 caracteres');
                isValid = false;
            }
            return isValid;
        }
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById(fieldId + 'Error');
            field.classList.add('error');
            errorDiv.textContent = message;
        }
        function setupForms() {
            document.getElementById('contactForm').addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    showNotification('Mensagem enviada com sucesso! Responderemos em breve.');
                    this.reset();
                }
            });
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const name = document.getElementById('userName').value;
                const email = document.getElementById('userEmail').value;
                currentUser = { name, email };
                showNotification(`Bem-vindo(a), ${name}!`);
                closeLogin();
            });
            document.getElementById('name').addEventListener('blur', function() {
                if (this.value.trim().length < 2 && this.value.length > 0) {
                    showFieldError('name', 'Nome deve ter pelo menos 2 caracteres');
                }
            });
            document.getElementById('email').addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    showFieldError('email', 'E-mail inválido');
                }
            });
            document.getElementById('message').addEventListener('blur', function() {
                if (this.value.trim().length < 10 && this.value.length > 0) {
                    showFieldError('message', 'Mensagem deve ter pelo menos 10 caracteres');
                }
            });
        }
        function toggleFAQ(index) {
            const faqItems = document.querySelectorAll('.faq-item');
            const currentItem = faqItems[index];
            const answer = currentItem.querySelector('.faq-answer');
            const toggle = currentItem.querySelector('.faq-toggle');
            faqItems.forEach((item, i) => {
                if (i !== index) {
                    item.querySelector('.faq-answer').classList.remove('active');
                    item.querySelector('.faq-toggle').classList.remove('active');
                }
            });
            answer.classList.toggle('active');
            toggle.classList.toggle('active');
        }
        function openLogin() {
            document.getElementById('loginModal').style.display = 'block';
        }
        function closeLogin() {
            document.getElementById('loginModal').style.display = 'none';
        }
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                window.location.href = 'produtos.html';
            }
        });
    </script>
</body>
</html>