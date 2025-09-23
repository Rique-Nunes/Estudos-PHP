<?php
$msg = "";
$arquivo_user = "usuarios.txt";

// Criação de usuários
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar") {
    $nome = trim($_POST["nome"]);
    $id = trim($_POST["id"]);

    $id_existe = false;
    if (file_exists($arquivo_user)) {
        $arq = fopen($arquivo_user, "r");
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
        if (!file_exists($arquivo_user)) {
            $cabecalho = "nome;id\n";
            $arq = fopen($arquivo_user, "w");
            fwrite($arq, $cabecalho);
            fclose($arq);
        }

        $arq = fopen($arquivo_user, "a");
        $linha = $nome . ';' . $id . "\n";
        fwrite($arq, $linha);
        fclose($arq);

        $msg = "Usuário salvo com sucesso!";
    } else {
        $msg = "Erro: ID já existe!";
    }
}

//Exclusão de usuários
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['id'])) {
    $id_para_deletar = $_GET['id'];
    $encontrou = false;
    $arqSaida = "";

    if (file_exists($arquivo_user)) {
        $arq = fopen($arquivo_user, "r");
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

    $arq = fopen($arquivo_user, "w");
    fwrite($arq, $arqSaida);
    fclose($arq);

    if ($encontrou) {
        $msg = "Usuário com ID '$id_para_deletar' foi deletado com sucesso!";
    } else {
        $msg = "Usuário com ID '$id_para_deletar' não foi encontrado.";
    }
}

//Edição de usuários
$id_para_editar = null;
$modo_edicao = false;

// Abrir opção de alteração
if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {
    $id_get = $_GET['id'];
    $modo_edicao = true;

    if (file_exists($arquivo_user)) {
        $arq = fopen($arquivo_user, "r");
        fgets($arq);

        while (!feof($arq)) {
            $linha = fgets($arq);
            $parte = explode(";", $linha);

            if (isset($parte[1]) && trim($parte[1]) == $id_get) {
                $id_para_editar = $parte;
                break;
            }
        }
        fclose($arq);
    }
}

// Salvar alteração
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar' && isset($_POST['id_original'])) {
    $id_original = $_POST['id_original'];
    $novo_nome = trim($_POST['nome']);
    $novo_id = trim($_POST['id']);
    $encontrou = false;
    $arqSaida = "";

    if (file_exists($arquivo_user)) {
        $arq = fopen($arquivo_user, "r");
        $cabecalho = fgets($arq);
        $arqSaida = $cabecalho;

        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $parte = explode(";", $linha);

                if (trim($parte[1]) == $id_original) {
                    $encontrou = true;
                    $linha_nova = $novo_nome . ';' . $novo_id . "\n";
                    $arqSaida .= $linha_nova;
                } else {
                    $arqSaida .= $linha;
                }
            }
        }
        fclose($arq);

        $arq = fopen($arquivo_user, "w");
        fwrite($arq, $arqSaida);
        fclose($arq);
    }

    if ($encontrou) {
        $msg = "Usuário com ID '$id_original' foi alterado com sucesso!";
        $modo_edicao = false;
    } else {
        $msg = "Usuário com ID '$id_original' não foi encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuários</title>
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
    </style>
</head>

<body>
    <h1>Gerenciamento de Usuários</h1>

    <?php if (!empty($msg)): ?>
        <div class="msg <?php echo strpos($msg, 'Erro') !== false ? 'erro' : 'sucesso'; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <!-- form de criar/editar -->
    <h2><?php echo $modo_edicao ? 'Editar Usuário' : 'Cadastrar Novo Usuário'; ?></h2>
    <form action="" method="POST">
        <?php if ($modo_edicao): ?>
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id_original" value="<?php echo $id_para_editar[1]; ?>">
        <?php else: ?>
            <input type="hidden" name="acao" value="criar">
        <?php endif; ?>

        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?php echo $modo_edicao ? $id_para_editar[0] : ''; ?>" required><br><br>

        <label>ID:</label><br>
        <input type="text" name="id" value="<?php echo $modo_edicao ? $id_para_editar[1] : ''; ?>" required><br><br>

        <input type="submit" value="<?php echo $modo_edicao ? 'Salvar Alterações' : 'Criar Usuário'; ?>">
        <?php if ($modo_edicao): ?>
            <a href="tela_usuarios_crud.php">Cancelar</a>
        <?php endif; ?>
    </form>

    <!-- listar usuários  -->
    <h2>Lista de Usuários Cadastrados</h2>
    <?php if (file_exists($arquivo_user)): ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>ID</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $arq = fopen($arquivo_user, "r");
                fgets($arq);

                while (!feof($arq)) {
                    $linha = fgets($arq);
                    if (trim($linha) != "") {
                        $dados = explode(";", $linha);
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($dados[0]) . "</td>";
                        echo "<td>" . htmlspecialchars($dados[1]) . "</td>";
                        echo "<td>";
                        echo "<a href='?acao=editar&id=" . $dados[1] . "'>Editar</a> | ";
                        echo "<a href='?acao=excluir&id=" . $dados[1] . "' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                fclose($arq);
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum usuário cadastrado ainda.</p>
    <?php endif; ?>
</body>

</html>