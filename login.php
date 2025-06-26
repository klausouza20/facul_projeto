<?php
    // 1. Pegar os valores do formulário

    $email = $_POST["email"];
    $pwd = md5($_POST["pwd"]);
    // print_r($_POST);
    // 2. conexão com banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";

    $recaptcha = $_POST['g-recaptcha-response'];

    if (empty($recaptcha)) {
    die("Por favor, confirme que você não é um robô.");
    }

    $secret = getenv('RECAPTCHA_SECRET_KEY');
    $ip = $_SERVER['REMOTE_ADDR'];

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptcha&remoteip=$ip");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
    die("Falha na verificação do reCAPTCHA.");
    }
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=facul_bd", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //query
        $stmt = $conn->prepare("SELECT codigo, nome FROM usuario WHERE email =:email AND senha=:senha");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $pwd);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result){
            echo "<span style='color:green;''>Conectado</span><br><br>";
            echo "Login válido! Bem vindo, " . $result['nome'];
            
        }
        else{
            echo "<span style='color:red;''>Desconectado</span><br><br>";
            echo "Email ou senha inválidos.";
        }
    
    }

    catch(PDOException $e)
    {
        echo "Falha de conexão " . $e->getMessage();
    }

    $conn = null;
?>