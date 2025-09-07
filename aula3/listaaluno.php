<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listando alunos</title>
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
    <h1>Lista Disciplina</h1>
    <table>
    <?php
    $arqDisc = fopen("aluno.txt", "r") or die("erro ao abrir");
    
    fgets($arqDisc);

    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);

    echo "<tr><td>".$colunaDados[0]."</td>".
    "<td>".$colunaDados[1]."</td>".
    "<td>".$colunaDados[2]."</td>". 
    "<td>".$colunaDados[3]."</td>".
    "<td>".$colunaDados[4]."</td>"."</tr>";
    }
    fclose($arqDisc);
    $msg = "deu certo";
    ?>
    </table>
</body>
</html>