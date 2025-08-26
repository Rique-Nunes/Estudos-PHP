<?php

$nome;
$sigla;
$carga;
$encontrou = false; 


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sigla_buscada = $_POST["sigla"];

    $arqDisc = fopen("disciplinas.txt","r") or die("ERRO: não consegui abrir o arquivo");
    
    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);
        
        if(isset($colunaDados[1]) && trim($colunaDados[1]) == $sigla_buscada){
            
            $nome = $colunaDados[0];
            $sigla = $colunaDados[1];
            $carga = $colunaDados[2];
            $encontrou = true;
            
            break; 
        }
    }
    fclose($arqDisc); 
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inclusão de disciplina</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
        }
        input {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="alterardisciplina.php" method="POST">
        Sigla da disciplina que quer alterar: <br>
        <input type="text" name="sigla"><br>
        <input type="submit" value="Buscar Disciplina">
    </form>
    <?php
    if ($encontrou) {
            
            echo '
            <hr>
            <form action="salvaralteracao.php" method="POST">
                <h3>Editando a Disciplina:</h3>
                
                Nome da disciplina: <br>
                <input type="text" name="nome" value="' . $nome . '"><br>
                
                Sigla da disciplina (não pode ser alterada): <br>
                <input type="text" name="sigla" value="' . $sigla . '" readonly><br>
                
                Carga horária da disciplina: <br>
                <input type="text" name="carga" value="' . trim($carga) . '"><br>
                
                <input type="submit" value="Salvar Alterações">
            </form>
            ';
        }
    ?>
</body>
</html>