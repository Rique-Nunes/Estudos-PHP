<?php
$arquivo_user = "usuarios.txt";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'entrar') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    
    $usuario_existe = false;
    
    if (file_exists($arquivo_user)) {
        $arq = fopen($arquivo_user, "r");
        fgets($arq); 
        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $dados = explode(";", $linha);
                if (isset($dados[2]) && trim($dados[2]) == $email) {
                    $usuario_existe = true;
                    if (trim($dados[3]) == $senha) {
                        break;
                    } else {
                        $msg = "Senha incorreta!";
                        break;
                    }
                }
            }
        }
        fclose($arq);
    }
    
    if (!$usuario_existe) {
        if (!file_exists($arquivo_user)) {
            $cabecalho = "nome;id;email;senha\n";
            $arq = fopen($arquivo_user, "w");
            fwrite($arq, $cabecalho);
            fclose($arq);
        }
        
        $arq = fopen($arquivo_user, "a");
        $linha = $nome . ';' . $email . ';' . $email . ';' . $senha . "\n";
        fwrite($arq, $linha);
        fclose($arq);
    }
    
    if (empty($msg)) {
        header("Location: tela_usuario.php?usuario_email=" . urlencode($email));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        h1 {
            color: #333;
            text-align: center;
        }
        
        form {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        
        .msg {
            padding: 10px;
            margin: 10px 0;
            background: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Entrar como Usuário</h1>
    
    <?php if (!empty($msg)): ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="acao" value="entrar">
        
        <label>Nome:</label>
        <input type="text" name="nome" required>
        
        <label>Email:</label>
        <input type="text" name="email" required>
        
        <label>Senha:</label>
        <input type="password" name="senha" required>
        
        <input type="submit" value="Entrar / Registrar">
    </form>

    <div class="links">
        <a href="tela_login.php">Voltar</a> | 
    </div>
</body>
</html>