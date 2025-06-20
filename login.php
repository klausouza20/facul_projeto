<?php
    // 1. Pegar os valores do formulário

    $email = $_POST["email"];
    $pwd = md5($_POST["pwd"]);
    // print_r($_POST);
    // 2. conexão com banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=facul_bd", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<span style='color:green;''>Conectado</span><br><br>";
        //query
        $stmt = $conn->prepare("SELECT codigo, nome FROM usuario WHERE email =:email AND senha=:senha");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $pwd);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result){
            echo "Login válido! Bem vindo, " . $result['nome'];
        }
        else{
            echo "Email ou senha inválidos.";
        }
        // foreach(new TableRows(new
        // RecursiveArrayIterator($stmt->fetchAll()))as $k->$v){
        //     echo $v;
        // 
    
    }

    catch(PDOException $e)
    {
        echo "Falha de conexão " . $e->getMessage();
    }

    $conn = null;
?>