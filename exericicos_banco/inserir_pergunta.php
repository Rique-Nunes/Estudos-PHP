<?php

$msg = ""; 
$msg_tipo = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $servidor = "localhost";
    $username = "root";
    $senha = "";
    $database = "3DawPerguntas";

    $conn = new mysqli($servidor, $username, $senha, $database);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $tipo = $_POST["tipo"];
    $id = $_POST["id"];
    $pergunta = $_POST["pergunta"];
    $resposta1 = $_POST["resposta1"];
    $resposta2 = $_POST["resposta2"];
    $resposta3 = $_POST["resposta3"];
    $resposta4 = $_POST["resposta4"];
    $resposta_correta = $_POST["resposta_correta"]; 

    $comandoSQL = "INSERT INTO Perguntas (tipo, id, pergunta, resposta1, resposta2, resposta3, resposta4, resposta_correta) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($comandoSQL);

    if ($stmt === false) {
        die("Erro ao preparar o comando: " . $conn->error);
    }

    $stmt->bind_param("ssssssss", 
        $tipo, 
        $id, 
        $pergunta, 
        $resposta1, 
        $resposta2, 
        $resposta3, 
        $resposta4, 
        $resposta_correta
    );

    if ($stmt->execute()) {
        $msg = "Pergunta incluída com sucesso!";
        $msg_tipo = "sucesso";
    } else {
        $msg = "Erro ao incluir a pergunta: " . $stmt->error;
        $msg_tipo = "erro";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Inserir Nova Pergunta</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        form input[type="text"],
        form textarea,
        form select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover {
            background-color: #0056b3;
        }
        .radio-group div {
            margin-top: 5px;
        }
        .mensagem {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Cadastrar Nova Pergunta (Múltipla Escolha)</h1>

        <?php 
        if (!empty($msg)): 
        ?>
            <div class="mensagem <?php echo $msg_tipo; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="multipla">Múltipla Escolha</option>
                <option value="texto">Discursiva</option>
            </select>

            <label for="id">ID (Ex: P1, H05, etc):</label>
            <input type="text" id="id" name="id" required>

            <label for="pergunta">Pergunta:</label>
            <textarea id="pergunta" name="pergunta" rows="4" required></textarea>
            
            <label for="resposta1">Resposta 1:</label>
            <input type="text" id="resposta1" name="resposta1" required>

            <label for="resposta2">Resposta 2:</label>
            <input type="text" id="resposta2" name="resposta2" required>

            <label for="resposta3">Resposta 3:</label>
            <input type="text" id="resposta3" name="resposta3" required>

            <label for="resposta4">Resposta 4:</label>
            <input type="text" id="resposta4" name="resposta4" required>

            <label>Resposta Correta:</label>
            <div class="radio-group">
                <div><input type="radio" id="correta1" name="resposta_correta" value="1" required> <label for="correta1">Resposta 1</label></div>
                <div><input type="radio" id="correta2" name="resposta_correta" value="2"> <label for="correta2">Resposta 2</label></div>
                <div><input type="radio" id="correta3" name="resposta_correta" value="3"> <label for="correta3">Resposta 3</label></div>
                <div><input type="radio" id="correta4" name="resposta_correta" value="4"> <label for="correta4">Resposta 4</label></div>
            </div>
            
            <button type="submit">Inserir Pergunta</button>
        </form>
    </div>

</body>
</html>