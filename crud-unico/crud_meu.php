<?php
// --- LÓGICA PARA CRIAR (CREATE) com fopen ---
$arquivo = "disciplinas.txt";
$msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "salvar"){

    $nome = $_POST["nome"];
    $sigla = $_POST["sigla"];
    $carga = $_POST["carga"];

    if(!file_exists($arquivo)){
        $cabecalho = "nome;sigla;carga\n";
        $arqDisc = fopen($arquivo, "w") or die ("Erro não foi possível abrir o arquivo");
        fwrite($arqDisc, $cabecalho);
        fclose($arqDisc);
    }

    $linha = "$nome;$sigla;$carga\n";

    $arqDisc = fopen($arquivo, "a") or die ("Erro não foi possível abri o arquivo");
    fwrite($arqDisc, $linha);
    fclose($arqDisc);

    $msg = "Disciplina salva com sucesso";
}
// --- LÓGICA PARA EXCLUIR (DELETE) com fopen ---
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['sigla'])) {
    
    $sigla_para_deletar = $_GET['sigla'];
    $encontrou = false;
    $arqSaida = ""; 

    if (file_exists($arquivo)) {
        
        $arqDisc = fopen($arquivo, "r") or die("erro para abrir o arquivo");

        while (!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            

            if (trim($linha) != "") {
                $parte = explode(";", $linha);

                if (isset($parte[1]) && trim($parte[1]) != $sigla_para_deletar) {

                    $arqSaida = $arqSaida . $linha;
                }
                else{
                    $encontrou = true;
                    }
                }
            }
            fclose($arqDisc);
        }
        

        $arqDisc = fopen($arquivo, "w") or die("erro na abertura para escrita");
        fwrite($arqDisc, $arqSaida);
        fclose($arqDisc);
    
    if ($encontrou) {
        $msg = "Disciplina com a sigla '$sigla_para_deletar' foi deletada com sucesso!";
    } else {
        $msg = "Disciplina com a sigla '$sigla_para_deletar' não foi encontrada.";
    }
}

// Lógica de Editar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar_edicao') {
    
    $nome_editar = $_POST["nome"];
    $sigla_editar = $_POST["sigla"]; 
    $carga_editar = $_POST["carga"];
    $sigla_original = $_POST['sigla_original'];

    $encontrou = false;
    $arqSaida = ""; 

    if (file_exists($arquivo)) {
        $arqDisc = fopen($arquivo, "r") or die("erro para abrir o arquivo");

        while (!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            if (trim($linha) != "") {
                $parte = explode(";", $linha);

                if (isset($parte[1]) && trim($parte[1]) == $sigla_original) {
                    $encontrou = true;

                    $nova_linha =  $nome_editar . ";" . $sigla_editar . ";" . $carga_editar . "\n";

                    $arqSaida = $arqSaida . $nova_linha;
                } else {

                    $arqSaida = $arqSaida . $linha;
                }
            }
        }
        fclose($arqDisc);
    }
     $arqDisc = fopen($arquivo, "w") or die("erro na abertura para escrita");
     fwrite($arqDisc, $arqSaida);
     fclose($arqDisc);
    
    if ($encontrou) {
        $msg = "Disciplina com a sigla '$sigla_editar' foi alterada com sucesso!";
    }
}

// --- CÓDIGO PHP NECESSÁRIO PARA PREENCHER O FORMULÁRIO DE EDIÇÃO ---
$disciplina_para_editar = null;

if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['sigla'])) {
    
    $sigla_get = $_GET['sigla'];

    $arqDisc = fopen($arquivo, "r");

    while (!feof($arqDisc)) {
        $linha = fgets($arqDisc);
        $parte = explode(";", $linha);

        if (isset($parte[1]) && trim($parte[1]) == $sigla_get) {
            $disciplina_para_editar = $parte;
            break; 
        }
    }
    
    fclose($arqDisc);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
</head>
<body>

    <?php if ($disciplina_para_editar): ?>
        
        <h2>Editando Disciplina</h2>
        <form action="" method="POST">
            <input type="hidden" name="acao" value="salvar_edicao">
            <input type="hidden" name="sigla_original" value="<?php echo $disciplina_para_editar[1]; ?>">
            
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?php echo $disciplina_para_editar[0]; ?>" required><br><br>
            
            <label>Sigla:</label><br>
            <input type="text" name="sigla" value="<?php echo $disciplina_para_editar[1]; ?>" required><br><br>

            <label>Carga horária:</label><br>
            <input type="text" name="carga" value="<?php echo $disciplina_para_editar[2]; ?>" required><br><br>

            <input type="submit" value="Salvar Alterações">
            <a href="crud_meu.php">Cancelar</a>
        </form>

    <?php else: ?>

        <h2>Cadastro de Disciplinas</h2>
        <form action="" method="POST">
            <input type="hidden" name="acao" value="salvar">
            <label>Nome:</label><br><input type="text" name="nome" required><br><br>
            <label>Sigla:</label><br><input type="text" name="sigla" required><br><br>
            <label>Carga horária:</label><br><input type="text" name="carga" required><br><br>
            <input type="submit" value="Salvar">
        </form>

    <?php endif; ?>

    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
    <hr>
    
    <h2>Lista de Disciplinas</h2>
    <table>
        <thead>
            <tr><th>Nome</th><th>Sigla</th><th>Carga Horária</th><th>Ações</th></tr>
        </thead>
        <tbody>
            <?php
            // Lógica de Leitura...
            if (file_exists($arquivo)) {
                $arqDisc = fopen($arquivo, "r") or die("erro para abrir o arquivo");
                fgets($arqDisc);

               while (!feof($arqDisc)) {
                    $linha = fgets($arqDisc);
                    if (trim($linha) == "") continue;
                    $dados = explode(";", $linha);

                    echo "<tr>";
                    echo "<td>" . $dados[0] . "</td>";
                    echo "<td>" . $dados[1] . "</td>";
                    echo "<td>" . trim($dados[2]) . "</td>";
                    echo "<td>";
                    echo "<a href='?acao=editar&sigla=" . trim($dados[1]) . "'>Editar</a> | ";
                    echo "<a href='?acao=excluir&sigla=" . trim($dados[1]) . "'\">Excluir</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                fclose($arqDisc);
            }
            ?>
        </tbody>
    </table>
</body>
</html>