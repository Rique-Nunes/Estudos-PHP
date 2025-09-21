<?php
// --- LÓGICA PARA CRIAR (CREATE) com fopen ---
$arquivo = "disciplinas.txt";
$msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao']){

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
        }
        fclose($arqDisc);

        $arqDisc = fopen($arquivo, "w") or die("erro na abertura para escrita");
        fwrite($arqDisc, $arqSaida);
        fclose($arqDisc);
    
    if ($encontrou) {
        $msg = "Disciplina com a sigla '$sigla_para_deletar' foi deletada com sucesso!";
    } else {
        $msg = "Disciplina com a sigla '$sigla_para_deletar' não foi encontrada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD Final</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        form, hr { margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Cadastro de Disciplinas</h2>
    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <form action="crud_meu.php" method="POST">
        <input type="hidden" name="acao" value="salvar">
        <label>Nome:</label><br>
        <input type="text" name="nome"><br><br>
        <label>Sigla:</label><br>
        <input type="text" name="sigla"><br><br>
        <label>Carga horária:</label><br>
        <input type="number" name="carga"><br><br>
        <input type="submit" value="Salvar">
    </form>
    <hr>
    
    <h2>Lista de Disciplinas</h2>
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
            <?php

            // LÓGICA DE LEITURA (READ) INTEGRADA COM O HTML
            
            if (file_exists($arquivo)) {
                
                $arqDisc = fopen($arquivo, "r") or die("Erro ao abrir arquivo");

                fgets($arqDisc);

                while (!feof($arqDisc)) {
                    $linha = fgets($arqDisc); 

                    if (trim($linha) == "") {
                        continue;
                    }

                    $colunaDados = explode(";", $linha);

                    $nome = $colunaDados[0];
                    $sigla = $colunaDados[1];
                    $carga = $colunaDados[2];

                    echo "<tr>";
                    echo "    <td>" . $nome . "</td>";
                    echo "    <td>" . $sigla . "</td>";
                    echo "    <td>" . $carga . "</td>";
                    echo "    <td>";
                    echo "<a href='?acao=excluir&sigla=" . $sigla . "'>Excluir</a>";
                    echo "    </td>";
                    echo "</tr>";
                }

                fclose($arqDisc);
            } else {
                echo "<tr><td>Nenhuma disciplina cadastrada ainda.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>