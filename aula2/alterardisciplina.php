<?php

$nome_encontrado = "";
$sigla_encontrada = "";
$carga_encontrada = "";
$encontrou = false; 


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sigla_buscada = $_POST["sigla"];

    $arqDisc = fopen("disciplinas.txt","r") or die("ERRO: não consegui abrir o arquivo");
    
    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);
        
        if($colunaDados[1] == $sigla_buscada){
            
            $nome_encontrado = $colunaDados[0];
            $sigla_encontrada = $colunaDados[1];
            $carga_encontrada = $colunaDados[2];
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
    <form action="alterardisciplina.php" method="POST" name="incluirdisciplina">
        Sigla da disciplina que quer alterar: <input type="text" name="sigla"><br>
        <input type="submit" value="Enviar">
    </form>
    <form action="alterardisciplina.php" method="POST" name="incluirdisciplina">
        Sigla da disciplina: <input type="text" name="sigla"><br>
        Carga horária da disciplina: <input type="number" name="carga"><br>
        Nome da disciplina: <input type="nome" name="text">
        <input type="submit" value="Enviar">
    </form>
    <?php
    if (!empty($msg)) {
        echo "<h1>$msg</h1>";
    }
    ?>
</body>
</html>