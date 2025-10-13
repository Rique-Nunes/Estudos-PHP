<?php
$arquivo_perguntas = "perguntas.txt";
$msg = "";
$modo_edicao = false;
$pergunta_edicao = null;

// Debug: Verificar se o script está sendo executado
error_log("Script tela_perguntas_crud.php iniciado");

if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {
    $id_get = $_GET['id'];
    $modo_edicao = true;

    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        fgets($arq); // Pula cabeçalho

        while (!feof($arq)) {
            $linha = fgets($arq);
            $parte = explode(";", $linha);

            if (isset($parte[1]) && trim($parte[1]) == $id_get) {
                $pergunta_edicao = $parte;
                break;
            }
        }
        fclose($arq);
    }
}

// Debug: Verificar se está recebendo POST
error_log("Método de requisição: " . $_SERVER['REQUEST_METHOD']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("POST recebido: " . print_r($_POST, true));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar_edicao') {
    error_log("Iniciando processo de salvamento da edição");
    
    $id_original = trim($_POST['id_original']);
    $tipo = $_POST['tipo'];
    $id = trim($_POST['id']);
    $pergunta_texto = trim($_POST['pergunta']);

    $encontrou = false;
    $conteudo_atualizado = "";

    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        
        // Manter o cabeçalho
        $cabecalho = fgets($arq);
        $conteudo_atualizado = $cabecalho;

        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $parte = explode(";", $linha);
                
                if (isset($parte[1]) && trim($parte[1]) == $id_original) {
                    $encontrou = true;
                    error_log("Encontrou pergunta para editar: " . $id_original);

                    // Construir nova linha
                    if ($tipo == 'multipla') {
                        $respostas = [
                            trim($_POST['resposta1']),
                            trim($_POST['resposta2']),
                            trim($_POST['resposta3']),
                            trim($_POST['resposta4'])
                        ];
                        $resposta_correta = $_POST['resposta_correta'];
                        $linha_nova = "multipla;" . $id . ";" . $pergunta_texto . ";" .
                            $respostas[0] . ";" . $respostas[1] . ";" .
                            $respostas[2] . ";" . $respostas[3] . ";" .
                            $resposta_correta . "\n";
                    } else {
                        $resposta = trim($_POST['resposta']);
                        $linha_nova = "texto;" . $id . ";" . $pergunta_texto . ";;;;;" . $resposta . "\n";
                    }
                    
                    $conteudo_atualizado .= $linha_nova;
                    error_log("Nova linha: " . $linha_nova);
                } else {
                    $conteudo_atualizado .= $linha;
                }
            }
        }
        fclose($arq);

        // Escrever de volta no arquivo
        if ($encontrou) {
            $arq = fopen($arquivo_perguntas, "w");
            fwrite($arq, $conteudo_atualizado);
            fclose($arq);
            
            $msg = "Pergunta com ID '$id_original' foi alterada com sucesso!";
            $modo_edicao = false;
            error_log("Pergunta salva com sucesso");
        } else {
            $msg = "Erro: Pergunta com ID '$id_original' não foi encontrada.";
            error_log("Pergunta não encontrada para ID: " . $id_original);
        }
    } else {
        $msg = "Erro: Arquivo de perguntas não encontrado.";
        error_log("Arquivo de perguntas não existe: " . $arquivo_perguntas);
    }
    
    // Redirecionar para evitar reenvio do formulário
    header("Location: tela_perguntas_crud.php?msg=" . urlencode($msg));
    exit();
}

// Processar mensagem do GET (após redirecionamento)
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

$pergunta_individual = null;
if (isset($_GET['acao']) && $_GET['acao'] == 'ver' && isset($_GET['id'])) {
    $id_get = $_GET['id'];

    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        fgets($arq);

        while (!feof($arq)) {
            $linha = fgets($arq);
            $parte = explode(";", $linha);

            if (isset($parte[1]) && trim($parte[1]) == $id_get) {
                $pergunta_individual = $parte;
                break;
            }
        }
        fclose($arq);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Perguntas</title>
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        form {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            margin: 20px 0;
        }
        
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            margin: 5px 0;
            box-sizing: border-box;
        }
        
        textarea {
            height: 80px;
        }
        
        input[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        
        a {
            color: #0066cc;
            text-decoration: none;
            margin-left: 10px;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        .msg {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .sucesso {
            background: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        .erro {
            background: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        
        .radio-group {
            margin: 10px 0;
        }
        
        .radio-group input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }
        
        .radio-group label {
            display: inline;
            margin-right: 15px;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <h1>Gerenciamento de Perguntas</h1>

    <?php if (!empty($msg)): ?>
        <div class="msg <?php echo (strpos($msg, 'sucesso') !== false || strpos($msg, 'alterada') !== false) ? 'sucesso' : 'erro'; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <?php if ($pergunta_individual): ?>
        <div>
            <h2>Visualizar Pergunta</h2>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($pergunta_individual[1]); ?></p>
            <p><strong>Tipo:</strong> <?php echo $pergunta_individual[0] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva'; ?></p>
            <p><strong>Pergunta:</strong> <?php echo htmlspecialchars($pergunta_individual[2]); ?></p>

            <?php if ($pergunta_individual[0] == 'multipla'): ?>
                <p><strong>Respostas:</strong></p>
                <ol>
                    <li><?php echo htmlspecialchars($pergunta_individual[3]); ?></li>
                    <li><?php echo htmlspecialchars($pergunta_individual[4]); ?></li>
                    <li><?php echo htmlspecialchars($pergunta_individual[5]); ?></li>
                    <li><?php echo htmlspecialchars($pergunta_individual[6]); ?></li>
                </ol>
                <p><strong>Resposta Correta:</strong> <?php echo $pergunta_individual[7]; ?></p>
            <?php else: ?>
                <p><strong>Resposta Esperada:</strong> <?php echo htmlspecialchars($pergunta_individual[7]); ?></p>
            <?php endif; ?>

            <a href="tela_perguntas_crud.php">Voltar</a>
        </div>

    <?php elseif ($modo_edicao && $pergunta_edicao): ?>
        <div>
            <h2>Editar Pergunta</h2>
            <form method="post" action="">
                <input type="hidden" name="acao" value="salvar_edicao">
                <input type="hidden" name="id_original" value="<?php echo htmlspecialchars($pergunta_edicao[1]); ?>">
                <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($pergunta_edicao[0]); ?>">

                <label>ID da Pergunta:</label>
                <input type="text" name="id" value="<?php echo htmlspecialchars($pergunta_edicao[1]); ?>" required>

                <label>Pergunta:</label>
                <textarea name="pergunta" required><?php echo htmlspecialchars($pergunta_edicao[2]); ?></textarea>

                <?php if ($pergunta_edicao[0] == 'multipla'): ?>
                    <label>Resposta 1:</label>
                    <input type="text" name="resposta1" value="<?php echo htmlspecialchars($pergunta_edicao[3]); ?>" required>

                    <label>Resposta 2:</label>
                    <input type="text" name="resposta2" value="<?php echo htmlspecialchars($pergunta_edicao[4]); ?>" required>

                    <label>Resposta 3:</label>
                    <input type="text" name="resposta3" value="<?php echo htmlspecialchars($pergunta_edicao[5]); ?>" required>

                    <label>Resposta 4:</label>
                    <input type="text" name="resposta4" value="<?php echo htmlspecialchars($pergunta_edicao[6]); ?>" required>

                    <label>Resposta Correta (Selecione apenas UMA):</label>
                    <div class="radio-group">
                        <input type="radio" name="resposta_correta" value="1" <?php echo (trim($pergunta_edicao[7]) == '1') ? 'checked' : ''; ?> required> 
                        <label>Resposta 1</label>
                        
                        <input type="radio" name="resposta_correta" value="2" <?php echo (trim($pergunta_edicao[7]) == '2') ? 'checked' : ''; ?> required> 
                        <label>Resposta 2</label>
                        
                        <input type="radio" name="resposta_correta" value="3" <?php echo (trim($pergunta_edicao[7]) == '3') ? 'checked' : ''; ?> required> 
                        <label>Resposta 3</label>
                        
                        <input type="radio" name="resposta_correta" value="4" <?php echo (trim($pergunta_edicao[7]) == '4') ? 'checked' : ''; ?> required> 
                        <label>Resposta 4</label>
                    </div>
                <?php else: ?>
                    <label>Resposta Esperada:</label>
                    <textarea name="resposta" required><?php echo htmlspecialchars($pergunta_edicao[7]); ?></textarea>
                <?php endif; ?>

                <input type="submit" value="Salvar Alterações">
                <a href="perguntas.html">Cancelar</a>
            </form>
        </div>

    <?php else: ?>
        <h2>Lista de Perguntas Cadastradas</h2>
        <?php if (file_exists($arquivo_perguntas)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Pergunta</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (file_exists($arquivo_perguntas)) {
                        $arq = fopen($arquivo_perguntas, "r");
                        $cabecalho = fgets($arq); // Pula o cabeçalho

                        while (!feof($arq)) {
                            $linha = fgets($arq);
                            if (trim($linha) != "") {
                                $dados = explode(";", $linha);
                                if (count($dados) >= 3) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars(trim($dados[1])) . "</td>";
                                    echo "<td>" . ($dados[0] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva') . "</td>";
                                    echo "<td>" . htmlspecialchars(substr(trim($dados[2]), 0, 50)) . "...</td>";
                                    echo "<td>";
                                    echo "<a href='?acao=ver&id=" . urlencode(trim($dados[1])) . "'>Ver</a> | ";
                                    echo "<a href='?acao=editar&id=" . urlencode(trim($dados[1])) . "'>Editar</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                        fclose($arq);
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma pergunta cadastrada ainda.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="perguntas.html">Voltar ao Início</a>
</body>
</html>