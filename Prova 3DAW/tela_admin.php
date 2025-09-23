<?php
$arquivo_perguntas = "perguntas.txt";
$arquivo_respostas = "respostas.txt";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        h1, h2 {
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }
        
        .pergunta {
            background: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #ff6b6b;
        }
        
        .resposta {
            background: #f0f8ff;
            padding: 10px;
            margin: 5px 0;
            border-left: 3px solid #4ecdc4;
        }
        
        .usuario-info {
            font-weight: bold;
            color: #0066cc;
        }
    </style>
</head>
<body>
    <h1>Painel do Administrador</h1>
    
    <div>
        <a href="tela_login.php">Voltar</a>
        <a href="tela_perguntas_admin.php">Gerenciar Perguntas</a>
        <a href="tela_usuarios_crud.php">Gerenciar Usuários</a>
    </div>

    <h2>Perguntas e Respostas</h2>
    
    <?php if (file_exists($arquivo_perguntas) && file_exists($arquivo_respostas)): ?>
        <?php
        $arq = fopen($arquivo_perguntas, "r");
        fgets($arq); // Pular cabeçalho
        
        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $dados = explode(";", $linha);
                echo "<div class='pergunta'>";
                echo "<strong>ID: " . $dados[1] . "</strong> - " . $dados[2];
                echo " (" . ($dados[0] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva') . ")";
                echo "</div>";
                
                // Buscar respostas para esta pergunta
                $arq_resp = fopen($arquivo_respostas, "r");
                fgets($arq_resp); // Pular cabeçalho
                
                $encontrou_respostas = false;
                while (!feof($arq_resp)) {
                    $linha_resp = fgets($arq_resp);
                    if (trim($linha_resp) != "") {
                        $parts = explode(";", $linha_resp);
                        if (isset($parts[2]) && trim($parts[2]) == trim($dados[1])) {
                            if (!$encontrou_respostas) {
                                echo "<h4>Respostas:</h4>";
                                $encontrou_respostas = true;
                            }
                            echo "<div class='resposta'>";
                            echo "<span class='usuario-info'>" . $parts[0] . " (" . $parts[1] . ")</span>: " . $parts[3];
                            echo "</div>";
                        }
                    }
                }
                fclose($arq_resp);
                
                if (!$encontrou_respostas) {
                    echo "<p>Nenhuma resposta ainda.</p>";
                }
            }
        }
        fclose($arq);
        ?>
    <?php else: ?>
        <p>Nenhuma pergunta ou resposta cadastrada.</p>
    <?php endif; ?>
</body>
</html>