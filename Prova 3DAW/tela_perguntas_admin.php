<?php
$arquivo_perguntas = "perguntas.txt";
$msg = "";
$modo_edicao = false;
$pergunta_edicao = null;

// Criação pergunta multipla escolha
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar_multipla") {
    $id = trim($_POST['id']);
    $pergunta = trim($_POST['pergunta']);
    $respostas = [
        trim($_POST['resposta1']),
        trim($_POST['resposta2']),
        trim($_POST['resposta3']),
        trim($_POST['resposta4'])
    ];
    $resposta_correta = $_POST['resposta_correta'];

    $id_existe = false;
    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        fgets($arq);
        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $dados = explode(";", $linha);
                if (trim($dados[1]) == $id) {
                    $id_existe = true;
                    break;
                }
            }
        }
        fclose($arq);
    }

    if (!$id_existe) {
        if (!file_exists($arquivo_perguntas)) {
            $cabecalho = "tipo;id;pergunta;resposta1;resposta2;resposta3;resposta4;resposta_correta\n";
            $arq = fopen($arquivo_perguntas, "w");
            fwrite($arq, $cabecalho);
            fclose($arq);
        }

        $linha = "multipla;" . $id . ";" . $pergunta . ";" .
            $respostas[0] . ";" . $respostas[1] . ";" .
            $respostas[2] . ";" . $respostas[3] . ";" .
            $resposta_correta . "\n";

        $arq = fopen($arquivo_perguntas, "a");
        fwrite($arq, $linha);
        fclose($arq);

        $msg = "Pergunta múltipla escolha criada com sucesso!";
    } else {
        $msg = "Erro: ID da pergunta já existe!";
    }
}

// Criação de pergunta discursivas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar_discursiva") {
    $id = trim($_POST['id']);
    $pergunta = trim($_POST['pergunta']);
    $resposta = trim($_POST['resposta']);

    $id_existe = false;
    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        fgets($arq);
        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $dados = explode(";", $linha);
                if (trim($dados[1]) == $id) {
                    $id_existe = true;
                    break;
                }
            }
        }
        fclose($arq);
    }

    if (!$id_existe) {
        if (!file_exists($arquivo_perguntas)) {
            $cabecalho = "tipo;id;pergunta;resposta1;resposta2;resposta3;resposta4;resposta_correta\n";
            $arq = fopen($arquivo_perguntas, "w");
            fwrite($arq, $cabecalho);
            fclose($arq);
        }

        $linha = "texto;" . $id . ";" . $pergunta . ";;;;;" . $resposta . "\n";

        $arq = fopen($arquivo_perguntas, "a");
        fwrite($arq, $linha);
        fclose($arq);

        $msg = "Pergunta discursiva criada com sucesso!";
    } else {
        $msg = "Erro: ID da pergunta já existe!";
    }
}

// Exclusão de perguntas
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['id'])) {
    $id_para_deletar = $_GET['id'];
    $encontrou = false;
    $arqSaida = "";

    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        $cabecalho = fgets($arq);
        $arqSaida = $cabecalho;

        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $parte = explode(";", $linha);
                if (trim($parte[1]) != $id_para_deletar) {
                    $arqSaida .= $linha;
                } else {
                    $encontrou = true;
                }
            }
        }
        fclose($arq);
    }

    $arq = fopen($arquivo_perguntas, "w");
    fwrite($arq, $arqSaida);
    fclose($arq);

    if ($encontrou) {
        $msg = "Pergunta com ID '$id_para_deletar' foi excluída com sucesso!";
    } else {
        $msg = "Pergunta com ID '$id_para_deletar' não foi encontrada.";
    }
}

// Edição de perguntas
if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {
    $id_get = $_GET['id'];
    $modo_edicao = true;

    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        fgets($arq);

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

// Salvar a edição
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar_edicao') {
    $id_original = $_POST['id_original'];
    $tipo = $_POST['tipo'];
    $id = trim($_POST['id']);
    $pergunta_texto = trim($_POST['pergunta']);

    $encontrou = false;
    $arqSaida = "";

    if (file_exists($arquivo_perguntas)) {
        $arq = fopen($arquivo_perguntas, "r");
        $cabecalho = fgets($arq);
        $arqSaida = $cabecalho;

        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $parte = explode(";", $linha);

                if (trim($parte[1]) == $id_original) {
                    $encontrou = true;

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

                    $arqSaida .= $linha_nova;
                } else {
                    $arqSaida .= $linha;
                }
            }
        }
        fclose($arq);

        $arq = fopen($arquivo_perguntas, "w");
        fwrite($arq, $arqSaida);
        fclose($arq);
    }

    if ($encontrou) {
        $msg = "Pergunta com ID '$id_original' foi alterada com sucesso!";
        $modo_edicao = false;
    } else {
        $msg = "Pergunta com ID '$id_original' não foi encontrada.";
    }
}

// Ver pergunta individualmente
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

// Variavel para verificar qual tipo de pergunta vai criar
$tipo_pergunta = isset($_POST['tipo_pergunta']) ? $_POST['tipo_pergunta'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Perguntas</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .msg {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .sucesso {
            background-color: #d4edda;
            color: #155724;
        }

        .erro {
            background-color: #f8d7da;
            color: #721c24;
        }

        .form-container {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <h1>Gerenciamento de Perguntas</h1>

    <?php if (!empty($msg)): ?>
        <div class="msg <?php echo strpos($msg, 'Erro') !== false ? 'erro' : 'sucesso'; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <!-- Ver pergunta individual -->
    <?php if ($pergunta_individual): ?>
        <div class="form-container">
            <h2>Visualizar Pergunta</h2>
            <p><strong>ID:</strong> <?php echo $pergunta_individual[1]; ?></p>
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

            <a href="tela_perguntas_admin.php">Voltar</a>
        </div>

        <!-- edição de pergunta -->
    <?php elseif ($modo_edicao && $pergunta_edicao): ?>
        <div class="form-container">
            <h2>Editar Pergunta</h2>
            <form method="post">
                <input type="hidden" name="acao" value="salvar_edicao">
                <input type="hidden" name="id_original" value="<?php echo $pergunta_edicao[1]; ?>">
                <input type="hidden" name="tipo" value="<?php echo $pergunta_edicao[0]; ?>">

                <label>ID da Pergunta:</label><br>
                <input type="text" name="id" value="<?php echo $pergunta_edicao[1]; ?>" required><br><br>

                <label>Pergunta:</label><br>
                <textarea name="pergunta" rows="3" cols="50" required><?php echo $pergunta_edicao[2]; ?></textarea><br><br>

                <?php if ($pergunta_edicao[0] == 'multipla'): ?>
                    <label>Resposta 1:</label><br>
                    <input type="text" name="resposta1" value="<?php echo $pergunta_edicao[3]; ?>" required><br><br>

                    <label>Resposta 2:</label><br>
                    <input type="text" name="resposta2" value="<?php echo $pergunta_edicao[4]; ?>" required><br><br>

                    <label>Resposta 3:</label><br>
                    <input type="text" name="resposta3" value="<?php echo $pergunta_edicao[5]; ?>" required><br><br>

                    <label>Resposta 4:</label><br>
                    <input type="text" name="resposta4" value="<?php echo $pergunta_edicao[6]; ?>" required><br><br>

                    <label>Resposta Correta (Selecione apenas UMA):</label><br>
                    <input type="radio" name="resposta_correta" value="1" <?php echo $pergunta_edicao[7] == '1' ? 'checked' : ''; ?> required> Resposta 1<br>
                    <input type="radio" name="resposta_correta" value="2" <?php echo $pergunta_edicao[7] == '2' ? 'checked' : ''; ?> required> Resposta 2<br>
                    <input type="radio" name="resposta_correta" value="3" <?php echo $pergunta_edicao[7] == '3' ? 'checked' : ''; ?> required> Resposta 3<br>
                    <input type="radio" name="resposta_correta" value="4" <?php echo $pergunta_edicao[7] == '4' ? 'checked' : ''; ?> required> Resposta 4<br><br>
                <?php else: ?>
                    <label>Resposta Esperada:</label><br>
                    <textarea name="resposta" rows="3" cols="50" required><?php echo $pergunta_edicao[7]; ?></textarea><br><br>
                <?php endif; ?>

                <input type="submit" value="Salvar Alterações">
                <a href="tela_perguntas_admin.php">Cancelar</a>
            </form>
        </div>

        <!-- seleção do tipo de pergunta -->
    <?php elseif (!$tipo_pergunta && !$modo_edicao): ?>
        <div class="form-container">
            <h2>Selecionar Tipo de Pergunta</h2>
            <form method="post">
                <label>Qual tipo de pergunta deseja criar?</label><br>
                <select name="tipo_pergunta" required>
                    <option value="">Selecione...</option>
                    <option value="multipla">Múltipla Escolha</option>
                    <option value="discursiva">Discursiva</option>
                </select><br><br>
                <input type="submit" value="Continuar">
            </form>
        </div>

        <!-- Criação de pergunta multipla escolhga -->
    <?php elseif ($tipo_pergunta == 'multipla' && !$modo_edicao): ?>
        <div class="form-container">
            <h2>Criar Pergunta de Múltipla Escolha</h2>
            <form method="post">
                <input type="hidden" name="acao" value="criar_multipla">

                <label>ID da Pergunta:</label><br>
                <input type="text" name="id" required><br><br>

                <label>Pergunta:</label><br>
                <textarea name="pergunta" rows="3" cols="50" required></textarea><br><br>

                <label>Resposta 1:</label><br>
                <input type="text" name="resposta1" required><br><br>

                <label>Resposta 2:</label><br>
                <input type="text" name="resposta2" required><br><br>

                <label>Resposta 3:</label><br>
                <input type="text" name="resposta3" required><br><br>

                <label>Resposta 4:</label><br>
                <input type="text" name="resposta4" required><br><br>

                <label>Resposta Correta (Selecione apenas UMA):</label><br>
                <input type="radio" name="resposta_correta" value="1" required> Resposta 1<br>
                <input type="radio" name="resposta_correta" value="2" required> Resposta 2<br>
                <input type="radio" name="resposta_correta" value="3" required> Resposta 3<br>
                <input type="radio" name="resposta_correta" value="4" required> Resposta 4<br><br>

                <input type="submit" value="Criar Pergunta">
                <a href="tela_perguntas_admin.php">Cancelar</a>
            </form>
        </div>

        <!-- Criação de pergunta discursiva -->
    <?php elseif ($tipo_pergunta == 'discursiva' && !$modo_edicao): ?>
        <div class="form-container">
            <h2>Criar Pergunta Discursiva</h2>
            <form method="post">
                <input type="hidden" name="acao" value="criar_discursiva">

                <label>ID da Pergunta:</label><br>
                <input type="text" name="id" required><br><br>

                <label>Pergunta:</label><br>
                <textarea name="pergunta" rows="3" cols="50" required></textarea><br><br>

                <label>Resposta Esperada:</label><br>
                <textarea name="resposta" rows="3" cols="50" required></textarea><br><br>

                <input type="submit" value="Criar Pergunta">
                <a href="tela_perguntas_admin.php">Cancelar</a>
            </form>
        </div>
    <?php endif; ?>

    <!-- Listagem de perguntas -->
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
                    fgets($arq);

                    while (!feof($arq)) {
                        $linha = fgets($arq);
                        if (trim($linha) != "") {
                            $dados = explode(";", $linha);
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($dados[1]) . "</td>";
                            echo "<td>" . ($dados[0] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva') . "</td>";
                            echo "<td>" . htmlspecialchars(substr($dados[2], 0, 50)) . "...</td>";
                            echo "<td>";
                            echo "<a href='?acao=ver&id=" . $dados[1] . "'>Ver</a> | ";
                            echo "<a href='?acao=editar&id=" . $dados[1] . "'>Editar</a> | ";
                            echo "<a href='?acao=excluir&id=" . $dados[1] . "' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>";
                            echo "</td>";
                            echo "</tr>";
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

    <br>
    <a href="tela_perguntas_admin.php">Voltar ao Início</a>
</body>

</html>