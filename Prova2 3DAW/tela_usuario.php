<?php
$arquivo_perguntas = "perguntas.txt";
$arquivo_respostas = "respostas.txt";
$arquivo_usuarios = "usuarios.txt";
$msg = "";

$usuario_email = isset($_GET['usuario_email']) ? $_GET['usuario_email'] : '';
$usuario_nome = '';

if (!empty($usuario_email) && file_exists($arquivo_usuarios)) {
    $arq = fopen($arquivo_usuarios, "r");
    fgets($arq);
    while (!feof($arq)) {
        $linha = fgets($arq);
        if (trim($linha) != "") {
            $dados = explode(";", $linha);
            if (isset($dados[2]) && trim($dados[2]) == $usuario_email) {
                $usuario_nome = trim($dados[0]);
                break;
            }
        }
    }
    fclose($arq);
}

if (empty($usuario_nome)) {
    header("Location: tela_login_usuario.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'responder') {
    
    if (!file_exists($arquivo_respostas)) {
        $cabecalho = "usuario_nome;usuario_email;id_pergunta;resposta\n";
        $arq = fopen($arquivo_respostas, "w");
        fwrite($arq, $cabecalho);
        fclose($arq);
    }
    
    $arq = fopen($arquivo_respostas, "a");
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'resposta_') === 0) {
            $id_pergunta = substr($key, 9);
            if (!empty(trim($value))) {
                $linha = $usuario_nome . ';' . $usuario_email . ';' . $id_pergunta . ';' . $value . "\n";
                fwrite($arq, $linha);
            }
        }
    }
    
    fclose($arq);
    $msg = "Respostas enviadas com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        h1 {
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }
        
        .usuario-info {
            background: #e6f7ff;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #007bff;
        }
        
        .pergunta {
            background: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        
        .opcoes {
            margin: 10px 0;
        }
        
        .opcao {
            margin: 5px 0;
        }
        
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            margin: 5px 0;
            box-sizing: border-box;
            height: 80px;
        }
        
        input[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 20px 0;
        }
        
        .msg {
            padding: 10px;
            margin: 10px 0;
            background: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
    </style>
</head>
<body>
    <h1>Área do Usuário</h1>
    
    <div class="usuario-info">
        <strong>Usuário:</strong> <?php echo $usuario_nome; ?> | 
        <strong>Email:</strong> <?php echo $usuario_email; ?> | 
        <a href="tela_login_usuario.php">Sair</a>
    </div>
    
    <?php if (!empty($msg)): ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if (file_exists($arquivo_perguntas)): ?>
        <form method="POST">
            <input type="hidden" name="acao" value="responder">
            <input type="hidden" name="usuario_email" value="<?php echo $usuario_email; ?>">
            
            <?php
            $arq = fopen($arquivo_perguntas, "r");
            fgets($arq); // Pular cabeçalho
            
            while (!feof($arq)) {
                $linha = fgets($arq);
                if (trim($linha) != "") {
                    $dados = explode(";", $linha);
                    echo "<div class='pergunta'>";
                    echo "<h3>" . $dados[2] . " (" . ($dados[0] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva') . ")</h3>";
                    
                    if ($dados[0] == 'multipla') {
                        echo "<div class='opcoes'>";
                        echo "<div class='opcao'><input type='radio' name='resposta_" . $dados[1] . "' value='1' required> " . $dados[3] . "</div>";
                        echo "<div class='opcao'><input type='radio' name='resposta_" . $dados[1] . "' value='2'> " . $dados[4] . "</div>";
                        echo "<div class='opcao'><input type='radio' name='resposta_" . $dados[1] . "' value='3'> " . $dados[5] . "</div>";
                        echo "<div class='opcao'><input type='radio' name='resposta_" . $dados[1] . "' value='4'> " . $dados[6] . "</div>";
                        echo "</div>";
                    } else {
                        echo "<textarea name='resposta_" . $dados[1] . "' placeholder='Digite sua resposta...' required></textarea>";
                    }
                    
                    echo "</div>";
                }
            }
            fclose($arq);
            ?>
            
            <input type="submit" value="Enviar Respostas">
        </form>
    <?php else: ?>
        <p>Nenhuma pergunta disponível.</p>
    <?php endif; ?>
</body>
</html>