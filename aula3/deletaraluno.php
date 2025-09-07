<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $matricula_para_deletar = $_POST["matricula"]; 

    $arqDisc = fopen("aluno.txt", "r") or die("erro ao abrir original");
    $arqTemp = fopen("aluno_temp.txt", "w") or die("erro ao abrir temp");

    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);

        if(isset($colunaDados[3]) && trim($colunaDados[3]) != $matricula_para_deletar){

            if(trim($linha) != "") {
                fwrite($arqTemp, $linha);
            }
        }
    }

    fclose($arqDisc);
    fclose($arqTemp);
    unlink("aluno.txt");
    rename("aluno_temp.txt", "aluno.txt");
    $msg = "aluno '" . $matricula_para_deletar . "' foi deletada com sucesso!;";

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deletando...</title>
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
    <form action="deletaraluno.php" method="POST">
        Matricula do aluno que quer deletar: <br>
        <input type="number" name="matricula"><br>
        <input type="submit" value="Buscar aluno">
    </form>
    <h1><?php echo $msg; ?></h1>
</body>
</html>