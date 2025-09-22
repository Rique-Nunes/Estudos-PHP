<?php
$msg = "";
$arquivo_user = "usuarios.txt";

//criação dos funcionários

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar") {

    $nome = $_POST["nome"];
    $id = $_POST["id"];

    if (!file_exists($arquivo_user)) {
        $cabecalho = "nome;id\n";
        $arqDisc = fopen($arquivo_user, "w") or die("Erro não foi possível abrir o arquivo");
        fwrite($arqDisc, $cabecalho);
        fclose($arqDisc);
    }
    $arqDisc = fopen($arquivo_user, "a") or die("Erro não foi possível abrir o arquivo");
    $linha = $nome . ';' . $id . "\n";
    fwrite($arqDisc, $linha);
    fclose($arqDisc);

    $msg = "Usuario salvo com sucesso!";
}

//deletar um funcionário
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['id'])) {

    $id_para_deletar = $_GET['id'];
    $encontrou = false;
    $arqSaida = "";

    if (file_exists($arquivo_user)) {

        $arqDisc = fopen($arquivo_user, "r") or die("erro para abrir o arquivo");

        while (!feof($arqDisc)) {
            $linha = fgets($arqDisc);


            if (trim($linha) != "") {
                $parte = explode(";", $linha);

                if (trim($parte[1]) != $id_para_deletar) {

                    $arqSaida = $arqSaida . $linha;
                } else {
                    $encontrou = true;
                }
            }
        }
        fclose($arqDisc);
    }


    $arqDisc = fopen($arquivo_user, "w") or die("erro na abertura para escrita");
    fwrite($arqDisc, $arqSaida);
    fclose($arqDisc);

    if ($encontrou) {
        $msg = "Usuário com o id '$id_para_deletar' foi deletado com sucesso!";
    } else {
        $msg = "Usuário com o id '$id_para_deletar' não foi encontrado.";
    }
}

//abrir opção de alteração
$id_para_editar = null;

if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {
    $id_get = $_GET['id'];

    $arqDisc = fopen($arquivo_user, "r") or die ("erro na abertura do arquivo");

    while (!feof($arqDisc)) {
        $linha = fgets($arqDisc);
        $parte = explode(";", $linha);

        if (isset($parte[1]) && trim($parte[1]) == $id_get) {
            $id_para_editar = $parte;
            break; 
        }
    }
    
    fclose($arqDisc);
}

//salvar alteração de um funcionario
if (isset($_POST['acao']) && $_POST['acao'] == 'salvar' && isset($_POST['id_original'])) {

    $id_original = $_POST['id_original'];
    $novo_nome = $_POST['nome'];
    $novo_id = $_POST['id'];
    $encontrou = false;
    $arqSaida = "";


    if (file_exists($arquivo_user)) {

        $arqDisc = fopen($arquivo_user, "r") or die("erro para abrir o arquivo");

        while (!feof($arqDisc)) {
            $linha = fgets($arqDisc);

            if (trim($linha) != "") {
                $parte = explode(";", $linha);

                if (trim($parte[1]) == $id_original) {
                    $encontrou = true;
                    $linha_nova = $novo_nome . ';' . $novo_id . "\n";

                    $arqSaida = $arqSaida . $linha_nova;

                } else {
                    $arqSaida = $arqSaida . $linha;
                }
            }
        }
        fclose($arqDisc);

    $arqDisc = fopen($arquivo_user, "w") or die("erro na abertura para escrita");
    fwrite($arqDisc, $arqSaida);
    fclose($arqDisc);
    }

    if ($encontrou) {
        $msg = "Usuário com o id '$id_original' foi alterado com sucesso!";
    } else {
        $msg = "Usuário com o id '$id_original' não foi encontrado.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criação de usuário</title>
</head>

<body>
    <?php if ($id_para_editar != null): ?>
        <h2>Editando Disciplina</h2>
        <form action="" method="POST">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id_original" value="<?php echo $id_para_editar[1]; ?>">
            
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?php echo $id_para_editar[0]; ?>" required><br><br>
            
            <label>id:</label><br>
            <input type="text" name="id" value="<?php echo $id_para_editar[1]; ?>" required><br><br>

            <input type="submit" value="Salvar Alterações">
            <a href="tela_usuarios_admin.php">Cancelar</a>

        </form>
    <?php else: ?>
        <h2>Cadastro de usuario</h2>
        <form action="" method="POST">
            <input type="hidden" name="acao" value="criar">
            <label>Nome:</label><br><input type="text" name="nome" required><br><br>
            <label>id:</label><br><input type="text" name="id" required><br><br>
            <input type="submit" value="criar">
        </form>
        <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
    <?php endif; ?>


        <!-- Listagem de usuário -->
        <h2>Lista de usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Sigla</th>
                    <th>Carga Horária</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (file_exists($arquivo_user)) {
                    $arqDisc = fopen($arquivo_user, "r") or die("erro para abrir o arquivo");
                    fgets($arqDisc);

                    while (!feof($arqDisc)) {
                        $linha = fgets($arqDisc);

                        if (trim($linha) != "") {
                            $dados = explode(";", $linha);

                            echo "<tr>";
                            echo "<td>" . $dados[0] . "</td>";
                            echo "<td>" . $dados[1] . "</td>";
                            echo "<td>";
                            echo "<a href='?acao=editar&id=" . $dados[1] . "'>Editar</a> | ";
                            echo "<a href='?acao=excluir&id=" . $dados[1] . "'\">Excluir</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    fclose($arqDisc);
                }

                ?>
            </tbody>
        </table>
</body>
</html>