<?php
$pergunta = null;
$arquivo_pergunta = "perguntas.txt";

//Para perguntas multipla escolha
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar_multipla"){
    $pergunta = $_POST['pergunta'];
    $respostas = [
        $_POST['resposta1'],
        $_POST['resposta2'],
        $_POST['resposta3'],
        $_POST['resposta4']
    ];

    echo "<p>Pergunta multipla adicionada com sucesso!</p>";
}
//Para perguntas discursivas
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar_multipla"){
    $pergunta = $_POST['pergunta'];
    $respostas = $_POST['resposta'];

    echo "<p>Pergunta discursa adicionada com sucesso!</p>";
}

if (isset($_POST['acao']) && $_POST['acao'] == 'escolher') {
    
    $opcao = $_POST['opcao'];
    $pergunta = $opcao;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Pergunta</title>
</head>

<body>

 <!--Criar pergunta multipla-->
    <?php if ($pergunta == 1): ?>
        <h1>Criar Pergunta de Múltipla Escolha</h1>
        <form method="post">
            <input type="hidden" name="acao" value="criar_multipla">
            <label for="pergunta">Pergunta:</label><br>
            <input type="text" name="pergunta" required><br><br>

            <label for="resposta1">Resposta 1:</label><br>
            <input type="text" name="resposta1" required><br><br>

            <label for="resposta2">Resposta 2:</label><br>
            <input type="text" name="resposta2" required><br><br>

            <label for="resposta3">Resposta 3:</label><br>
            <input type="text" name="resposta3" required><br><br>

            <label for="resposta4">Resposta 4:</label><br>
            <input type="text" name="resposta4" required><br><br>

            <label for="resposta4">Resposta 5:</label><br>
            <input type="text" name="resposta5" required><br><br>

            <input type="submit" value="Adicionar Pergunta">
        </form>
        <a href="" class="menu">Voltar ao Menu Principal</a>
    <?php endif; ?>

<!--Criar pergunta discursiva-->
    <?php if ($pergunta == 2): ?>
        <h1>Criar Pergunta discursiva: </h1>
        <form method="post">
            <input type="hidden" name="acao" value="criar_discursiva">
            <label for="pergunta">Pergunta:</label><br>
            <input type="text" name="pergunta" required><br><br>

            <label for="resposta">Resposta discursiva:</label><br>
            <input type="text" name="resposta" required><br><br>
            <a href="" class="menu">Voltar ao Menu Principal</a>
        </form>
    <?php endif; ?>


 <!--Qual pergunta vai querer-->
    <?php if (!$pergunta): ?>
    <h2>Qual tipo de pergunta cadastrar?</h2>
    <form action="" method="POST">
        <input type="hidden" name="acao" value="escolher">
        <select name="opcao">
            <option value=1>
                Multipla
            </option>
            <option value=2>
                Discursiva
            </option>
        </select>
        <input type="submit" value="Salvar">
    </form>
    <a href="" class="menu">Voltar ao Menu Principal</a>
    <?php endif; ?>
</body>
</html>