<?php
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST["nome"];
    $sigla = $_POST["sigla"];
    $carga = $_POST["carga"];

    echo "nome: $nome sigla: $sigla carga: $carga";

    if(!file_exists("disciplinas.txt")){

        $arqDisc = fopen("disciplinas.txt","w") or die("erro crítico na criação do arquivo");
        $linha = "nome;sigla;carga\n";
        fwrite($arqDisc, $linha);

        fclose($arqDisc);
    }
    $arqDisc = fopen("disciplinas.txt","a") or die("erro critico na adição no arquivo");
    $linha = $nome . ";" . $sigla . ";" .$carga.";";
    fwrite($arqDisc, $linha);
    fclose($arqDisc);
    $msg = "Deu tudo certo";
    
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
        Sigla da disciplina: <input type="text" name="sigla"><br>
        Carga horária da disciplina: <input type="number" name="carga">
        <input type="submit" value="Enviar">
    </form>
    <?php
    // Exibe a mensagem de sucesso se a variável $msg não estiver vazia
    if (!empty($msg)) {
        echo "<h1>$msg</h1>";
    }
    ?>
</body>
</html>