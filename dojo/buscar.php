<?php
    $id_aluno;
    $nome;
    $date;
    $cpf;
    $email;
    $turno;
    

if ( $_SERVER['REQUEST_METHOD'] == "POST") {
    $id_aluno = $_POST["id_aluno"];

    $i = 0;
    $arqDisc = fopen("alun.txt", "r") or die ("erro de abertura do arquivo de entrada");
    
    while (!feof($arqDisc)) {

        $line = fgets($arqDisc);
        
        $parts = explode(";", $line);

        $mat = $parts[0]; 
        $nome = $parts[1];
        $date = $parts[2];
        $cpf = $parts[3];
        $email = $parts[4];
        $turno = $parts[5];


        if ($mat == $id_aluno){
            echo "Achei!!";
            echo $id_aluno;
            echo $nome;
            echo $date;
            echo $cpf;
            echo $email;
            echo $turno;
            break;
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dojo - Projeto Compartilhado</title>
</head>
<body>
    <form action="alterar.php" method="POST"> 
        <label>Aluno:</label>
        mat: <input type="text" id="id_aluno" name="idaluno" value="<?php echo $id_aluno ?>"><br>
        nome: <input type="text" id="nome" name="nome" value="<?php echo $nome ?>"><br>
        Data: <input type="text"  name="date" value="<?php echo $date ?>"><br>
        cpf: <input type="text"  name="cpf" value="<?php echo $cpf?>"> <br>
        email: <input type="text"  name="email" value="<?php echo $email ?>"><br>
        Turno: <input type="text"  name="turno" value="<?php echo $turno ?>"><br>
        <button type="submit">ENVIAR</button>
    </form>
</body>
</html>
