<?php
$msg = "";
$arquivo_user = "usuarios.txt";

// Criação de usuários
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar") {
    $nome = trim($_POST["nome"]);
    $id = trim($_POST["id"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $id_existe = false;
    $email_existe = false;
    
    if (file_exists($arquivo_user)) {
        $arq = fopen($arquivo_user, "r");
        fgets($arq);
        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $dados = explode(";", $linha);
                if (trim($dados[1]) == $id) {
                    $id_existe = true;
                }
                if (trim($dados[2]) == $email) {
                    $email_existe = true;
                }
            }
        }
        fclose($arq);
    }

    if (!$id_existe && !$email_existe) {
        if (!file_exists($arquivo_user)) {
            $cabecalho = "nome;id;email;senha\n";
            $arq = fopen($arquivo_user, "w");
            fwrite($arq, $cabecalho);
            fclose($arq);
        }

        $arq = fopen($arquivo_user, "a");
        $linha = $nome . ';' . $id . ';' . $email . ';' . $senha . "\n";
        fwrite($arq, $linha);
        fclose($arq);

        $msg = "Usuário salvo com sucesso!";
    } else {
        if ($id_existe) {
            $msg = "Erro: ID já existe!";
        } else {
            $msg = "Erro: E-mail já existe!";
        }
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
            if (trim($linha) != "") {
                $parte = explode(";", $linha);
                if (isset($parte[1]) && trim($parte[1]) == $id_get) {
                    $id_para_editar = $parte;
                    break;
                }
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
    $novo_email = trim($_POST['email']);
    $nova_senha = trim($_POST['senha']);
    $encontrou = false;
    $arqSaida = "";

    // Verificar se novo email já existe em outro usuário
    $email_existe = false;
    if (file_exists($arquivo_user)) {
        $arq = fopen($arquivo_user, "r");
        fgets($arq);
        while (!feof($arq)) {
            $linha = fgets($arq);
            if (trim($linha) != "") {
                $parte = explode(";", $linha);
                if (trim($parte[1]) != $id_original && trim($parte[2]) == $novo_email) {
                    $email_existe = true;
                    break;
                }
            }
        }
        fclose($arq);
    }

    if (!$email_existe) {
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
                        $linha_nova = $novo_nome . ';' . $novo_id . ';' . $novo_email . ';' . $nova_senha . "\n";
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
    } else {
        $msg = "Erro: E-mail já existe em outro usuário!";
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
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
        
        input[type="text"], input[type="password"] {
            width: 300px;
            padding: 8px;
            border: 1px solid #ccc;
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

        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $modo_edicao ? $id_para_editar[0] : ''; ?>" required>

        <label>ID:</label>
        <input type="text" name="id" value="<?php echo $modo_edicao ? $id_para_editar[1] : ''; ?>" required>

        <label>E-mail:</label>
        <input type="text" name="email" value="<?php echo $modo_edicao ? $id_para_editar[2] : ''; ?>" required>

        <label>Senha:</label>
        <input type="password" name="senha" value="<?php echo $modo_edicao ? $id_para_editar[3] : ''; ?>" required>

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
                    <th>E-mail</th>
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
                        echo "<td>" . htmlspecialchars($dados[2]) . "</td>";
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
        <a href="tela_admin.php">Voltar ao Início</a>
    <?php else: ?>
        <p>Nenhum usuário cadastrado ainda.</p>
    <?php endif; ?>
</body>
</html>