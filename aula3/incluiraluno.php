<!--Matricula, data ingresso, nome, cpf, e-mail-->
<?php
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST["nome"];
    $data = $_POST["data"];
    $cpf = $_POST["cpf"];
    $matricula = $_POST["matricula"];
    $email = $_POST["email"];


    echo "nome: $nome data: $data cpf: $cpf matricula: $matricula email: $email";

    if(!file_exists("aluno.txt")){

        $arqDisc = fopen("aluno.txt","w") or die("erro crítico na criação do arquivo");
        $linha = "nome;data;cpf;matricula;email\n";
        fwrite($arqDisc, $linha);

        fclose($arqDisc);
    }
    $arqDisc = fopen("aluno.txt","a") or die("erro critico na adição no arquivo");
    $linha = $nome . ";" . $data . ";" . $cpf . ";" . $matricula . ";" . $email . "\n";
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
    <title>Inclusão de alunos</title>
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
    <!--Matricula, data ingresso, nome, cpf, e-mail-->
    <form action="incluiraluno.php" method="POST" name="incluiraluno">
        Matricula: <input type="number" name="matricula"><br>
        Nome: <input type="text" name="nome"><br>
        data de ingresso: <input type="text" name="data"><br>
        CPF: <input type="text" name="cpf"><br>
        email: <input type="text" name="email"><br>
        <input type="submit" value="Enviar">
    </form>
    <?php

    if (!empty($msg)) {
        echo "<h1>$msg</h1>";
    }
    ?>
</body>
</html>