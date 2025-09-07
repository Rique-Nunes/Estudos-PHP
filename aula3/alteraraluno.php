<?php
$nome;
$matricula;
$data;
$cpf;
$email;
$encontrou = false; 

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $matricula_buscada = $_POST["matricula"];

    $arqDisc = fopen("aluno.txt","r") or die("ERRO: não consegui abrir o arquivo");
    
    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);
        
        if(isset($colunaDados[1]) && trim($colunaDados[1]) == $matricula_buscada){

            $nome = $colunaDados[0];
            $data = $colunaDados[1];
            $cpf = $colunaDados[2];
            $matricula = $colunaDados[3];
            $email = $colunaDados[4];
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
    <title>Alteração de cadastro do aluno</title>
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
    <form action="alteraraluno.php" method="POST">
        matricula do aluno que quer alterar: <br>
        <input type="number" name="matricula"><br>
        <input type="submit" value="Buscar aluno">
    </form>
    <?php
    if ($encontrou) {
            echo '
            <hr>
            <form action="salvaralteracaoaluno.php" method="POST">
                <h3>Editando o campo do Aluno:</h3>
                
                Nome do aluno: <br>
                <input type="text" name="nome" value="' . $nome . '"><br>
                
                Matricula do aluno (não pode ser alterada): <br>
                <input type="number" name="matricula" value="' . $matricula . '" readonly><br>
                
                CPF: <br>
                <input type="text" name="cpf" value="' . $cpf . '"><br>
                
                Data de ingresso: <br>
                <input type="text" name="data" value="' . $data . '"><br>

                Email do aluno: <br>
                <input type="text" name="email" value="' . $email . '"><br>
                
                <input type="submit" value="Salvar Alterações">
            </form>
            ';
        }
    ?>
</body>
</html>