<?php
include('conexao.php');

if(isset($_POST['email']) || isset($_POST['senha'])) {
    if(strlen($_POST['email']) == 0) {
        $login_error = "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        $login_error = "Preencha sua senha";
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);
        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
        $quantidade = $sql_query->num_rows;
        if($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();
            if(!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email']; // <-- Adiciona email na sessão
            header("Location: ../index.php");
            exit();
        } else {
            $login_error = "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}
// Registro de novo usuário
if(isset($_POST['registro_nome']) && isset($_POST['registro_email']) && isset($_POST['registro_senha'])) {
    $nome = $mysqli->real_escape_string($_POST['registro_nome']);
    $email = $mysqli->real_escape_string($_POST['registro_email']);
    $senha = $mysqli->real_escape_string($_POST['registro_senha']);
    if(strlen($nome) == 0 || strlen($email) == 0 || strlen($senha) == 0) {
        $registro_error = "Preencha todos os campos do registro.";
    } else {
        $sql_check = "SELECT id FROM usuarios WHERE email = '$email'";
        $query_check = $mysqli->query($sql_check);
        if($query_check->num_rows > 0) {
            $registro_error = "E-mail já cadastrado.";
        } else {
            // Salva a senha em texto puro
            $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
            if($mysqli->query($sql_insert)) {
                $registro_success = "Usuário registrado com sucesso!";
            } else {
                $registro_error = "Erro ao registrar: " . $mysqli->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavelle - Login & Cadastro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: #333;
            background-color: #fefefe;
        }
        .container {
            max-width: 400px;
            margin: 4rem auto;
            background: #fff;
            padding: 2rem 2.5rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        h1 {
            text-align: center;
            color: #8b4b8c;
            margin-bottom: 2rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.5rem 1rem;
            margin-bottom: 1.2rem;
            border: 2px solid #e8ddd4;
            border-radius: 25px;
            outline: none;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #8b4b8c;
        }
        .cta-button {
            background: #C9A646;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 1rem;
        }
        .cta-button:hover {
            background: #ddf843ff;
            transform: translateY(-2px);
        }
        .back-button {
            background: #6c757d;
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        .toggle-link {
            color: #5C2A33;
            text-decoration: underline;
            cursor: pointer;
            display: block;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
    <script>
        function showForm(formId) {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registroForm').style.display = 'none';
            document.getElementById(formId).style.display = 'block';
        }
        window.onload = function() {
            showForm('loginForm');
        }
    </script>
</head>
<body>
    <div class="container">
        <div id="loginForm">
            <h1>Login</h1>
            <form action="" method="POST">
                <label for="email">E-mail</label>
                <input type="text" name="email" id="email">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha">
                <button type="submit" class="cta-button">Entrar</button>
            </form>
            <span class="toggle-link" onclick="showForm('registroForm')">Não tem conta? Cadastre-se</span>
            <a href="../index.php" class="back-button">Voltar para página inicial</a>
        </div>
        <div id="registroForm" style="display:none;">
            <h1>Cadastro</h1>
            <form action="" method="POST">
                <label for="registro_nome">Nome</label>
                <input type="text" name="registro_nome" id="registro_nome">
                <label for="registro_email">E-mail</label>
                <input type="text" name="registro_email" id="registro_email">
                <label for="registro_senha">Senha</label>
                <input type="password" name="registro_senha" id="registro_senha">
                <button type="submit" class="cta-button">Cadastrar</button>
            </form>
            <span class="toggle-link" onclick="showForm('loginForm')">Já tem conta? Entrar</span>
            <a href="../html/index.php" class="back-button">Voltar para página inicial</a>
        </div>
    </div>
</body>
</html>
<?php
include('conexao.php');

if(isset($_POST['email']) || isset($_POST['senha'])) {

    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email']; // <-- Adiciona email na sessão

            header("Location: painel.php");

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }

    }

}
// Registro de novo usuário
if(isset($_POST['registro_nome']) && isset($_POST['registro_email']) && isset($_POST['registro_senha'])) {
    $nome = $mysqli->real_escape_string($_POST['registro_nome']);
    $email = $mysqli->real_escape_string($_POST['registro_email']);
    $senha = $mysqli->real_escape_string($_POST['registro_senha']);

    if(strlen($nome) == 0 || strlen($email) == 0 || strlen($senha) == 0) {
        echo "Preencha todos os campos do registro.";
    } else {
        // Verifica se o email já existe
        $sql_check = "SELECT id FROM usuarios WHERE email = '$email'";
        $query_check = $mysqli->query($sql_check);
        if($query_check->num_rows > 0) {
            echo "E-mail já cadastrado.";
        } else {
            // Salva a senha em texto puro
            $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
            if($mysqli->query($sql_insert)) {
                echo "Usuário registrado com sucesso!";
            } else {
                echo "Erro ao registrar: " . $mysqli->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f2f2f2;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            .container {
                background: #fff;
                padding: 30px 40px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                margin-bottom: 20px;
                width: 350px;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            label {
                display: block;
                margin-bottom: 5px;
                color: #555;
            }
            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 8px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            button {
                background: #007bff;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
            }
            button:hover {
                background: #0056b3;
            }
            #registroForm, #loginContainer {
                display: none;
            }
            .show {
                display: block !important;
            }
            .hide {
                display: none !important;
            }
        </style>
        <script>
            function toggleRegistro() {
                document.getElementById('registroForm').classList.add('show');
                document.getElementById('loginContainer').classList.remove('show');
            }
            function toggleLogin() {
                document.getElementById('loginContainer').classList.add('show');
                document.getElementById('registroForm').classList.remove('show');
            }
            window.onload = function() {
                document.getElementById('registroForm').classList.remove('show');
                document.getElementById('loginContainer').classList.remove('show');
            }
        </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);  
            margin-bottom: 20px;        
            width: 350px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        #registroForm {
            display: none;
        }
        .show {
            display: block !important;
        }
    </style>
    <script>
        function toggleRegistro() {
            var form = document.getElementById('registroForm');
            form.classList.toggle('show');
        }
    </script>
</head>
<body>
    
    <div class="container" id="registroForm">
        <form action="" method="POST">
            <h1>Registrar</h1>
            <label>Nome</label>
            <input type="text" name="registro_nome">
            <label>E-mail</label>
            <input type="text" name="registro_email">
            <label>Senha</label>
            <input type="password" name="registro_senha">
            <button type="submit">Registrar</button>
            <button type="button" onclick="toggleLogin()" style="margin-top:10px;background:#6c757d;">Voltar para login</button>
        </form>
    </div>
    <div class="container" id="loginContainer">
        <h1>Acesse sua conta</h1>
        <form action="" method="POST">
            <p>
                <label>E-mail</label>
                <input type="text" name="email">
            </p>
            <p>
                <label>Senha</label>
                <input type="password" name="senha">
            </p>
            <p>
                <button type="submit">Entrar</button>
            </p>
        </form>
    </div>
</body>
</html>