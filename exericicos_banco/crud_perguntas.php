<?php
$msg = "";
$msg_tipo = "";
$pergunta_edicao = null;
$pergunta_individual = null;

$servidor = "localhost";
$username = "root";
$senha = "";
$database = "3DawPerguntas";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar_multipla") {

    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $id = trim($_POST['id']);
    $pergunta = trim($_POST['pergunta']);
    $respostas = [
        trim($_POST['resposta1']),
        trim($_POST['resposta2']),
        trim($_POST['resposta3']),
        trim($_POST['resposta4'])
    ];
    $resposta_correta = $_POST['resposta_correta'];
    $tipo = "multipla";

    $comandoSQL = "INSERT INTO Perguntas (tipo, id, pergunta, resposta1, resposta2, resposta3, resposta4, resposta_correta) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($comandoSQL);

    $stmt->bind_param(
        "ssssssss",
        $tipo,
        $id,
        $pergunta,
        $respostas[0],
        $respostas[1],
        $respostas[2],
        $respostas[3],
        $resposta_correta
    );

    if ($stmt->execute()) {
        $msg = "Pergunta múltipla escolha criada com sucesso!";
        $msg_tipo = "sucesso";
    } else {
        $msg = "Erro ao incluir a pergunta: " . $stmt->error;
        $msg_tipo = "erro";
    }
    $stmt->close();


    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == "criar_discursiva") {

    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $id = trim($_POST['id']);
    $pergunta = trim($_POST['pergunta']);
    $resposta = trim($_POST['resposta']);
    $tipo = "texto";
    $resposta_vazia = "";

    $comandoSQL = "INSERT INTO Perguntas (tipo, id, pergunta, resposta1, resposta2, resposta3, resposta4, resposta_correta) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($comandoSQL);

    $stmt->bind_param(
        "ssssssss",
        $tipo,
        $id,
        $pergunta,
        $resposta_vazia,
        $resposta_vazia,
        $resposta_vazia,
        $resposta_vazia,
        $resposta
    );

    if ($stmt->execute()) {
        $msg = "Pergunta discursiva criada com sucesso!";
        $msg_tipo = "sucesso";
    } else {
        $msg = "Erro ao incluir a pergunta: " . $stmt->error;
        $msg_tipo = "erro";
    }
    $stmt->close();
    $conn->close();
}

if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['id'])) {

    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $id_para_deletar = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Perguntas WHERE id = ?");
    $stmt->bind_param("s", $id_para_deletar);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $msg = "Pergunta com ID '$id_para_deletar' foi excluída com sucesso!";
            $msg_tipo = "sucesso";
        } else {
            $msg = "Pergunta com ID '$id_para_deletar' não foi encontrada.";
            $msg_tipo = "erro";
        }
    } else {
        $msg = "Erro ao excluir: " . $stmt->error;
        $msg_tipo = "erro";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {

    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $id_get = $_GET['id'];
    $modo_edicao = true;

    $stmt = $conn->prepare("SELECT * FROM Perguntas WHERE id = ?");
    $stmt->bind_param("s", $id_get);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pergunta_edicao = $result->fetch_assoc();
    } else {
        $msg = "Pergunta não encontrada para edição.";
        $msg_tipo = "erro";
        $modo_edicao = false;
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar_edicao') {

    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $id_original = $_POST['id_original'];
    $tipo = $_POST['tipo'];
    $id = trim($_POST['id']);
    $pergunta_texto = trim($_POST['pergunta']);

    if ($tipo == 'multipla') {
        $r1 = trim($_POST['resposta1']);
        $r2 = trim($_POST['resposta2']);
        $r3 = trim($_POST['resposta3']);
        $r4 = trim($_POST['resposta4']);
        $r_correta = $_POST['resposta_correta'];

        $stmt = $conn->prepare("UPDATE Perguntas SET id = ?, pergunta = ?, resposta1 = ?, resposta2 = ?, resposta3 = ?, resposta4 = ?, resposta_correta = ? WHERE id = ?");
        $stmt->bind_param("ssssssss", $id, $pergunta_texto, $r1, $r2, $r3, $r4, $r_correta, $id_original);
    } else {
        $resposta = trim($_POST['resposta']);
        $r_vazia = "";

        $stmt = $conn->prepare("UPDATE Perguntas SET id = ?, pergunta = ?, resposta1 = ?, resposta2 = ?, resposta3 = ?, resposta4 = ?, resposta_correta = ? WHERE id = ?");
        $stmt->bind_param("ssssssss", $id, $pergunta_texto, $r_vazia, $r_vazia, $r_vazia, $r_vazia, $resposta, $id_original);
    }

    if ($stmt->execute()) {
        $msg = "Pergunta com ID '$id_original' foi alterada com sucesso!";
        $msg_tipo = "sucesso";
    } else {
        $msg = "Erro ao salvar alterações: " . $stmt->error;
        $msg_tipo = "erro";
    }

    $stmt->close();
    $conn->close();
    $modo_edicao = false;
}

if (isset($_GET['acao']) && $_GET['acao'] == 'ver' && isset($_GET['id'])) {

    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $id_get = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM Perguntas WHERE id = ?");
    $stmt->bind_param("s", $id_get);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pergunta_individual = $result->fetch_assoc();
    } else {
        $msg = "Pergunta não encontrada.";
        $msg_tipo = "erro";
    }

    $stmt->close();
    $conn->close();
}

$tipo_pergunta = isset($_POST['tipo_pergunta']) ? $_POST['tipo_pergunta'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Perguntas (Banco de Dados)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
        }

        h1,
        h2 {
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            margin: 20px 0;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        select {
            padding: 8px;
            border: 1px solid #ccc;
            margin: 5px 0;
            box-sizing: border-box;
            width: 100%;
        }

        input[type="radio"] {
            width: auto;
        }

        textarea {
            width: 100%;
            height: 80px;
        }

        input[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            width: auto;
        }

        a {
            color: #0066cc;
            text-decoration: none;
            margin-left: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .msg {
            padding: 10px;
            margin: 10px 0;
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
    <h1>Gerenciamento de Perguntas (Banco de Dados)</h1>

    <?php if (!empty($msg)) : ?>
        <div class="msg <?php echo $msg_tipo; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <?php if ($pergunta_individual) : ?>
        <div>
            <h2>Visualizar Pergunta</h2>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($pergunta_individual['id']); ?></p>
            <p><strong>Tipo:</strong> <?php echo $pergunta_individual['tipo'] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva'; ?></p>
            <p><strong>Pergunta:</strong> <?php echo htmlspecialchars($pergunta_individual['pergunta']); ?></p>

            <?php if ($pergunta_individual['tipo'] == 'multipla') : ?>
                <p><strong>Respostas:</strong></p>
                <ol>
                    <li><?php echo htmlspecialchars($pergunta_individual['resposta1']); ?></li>
                    <li><?php echo htmlspecialchars($pergunta_individual['resposta2']); ?></li>
                    <li><?php echo htmlspecialchars($pergunta_individual['resposta3']); ?></li>
                    <li><?php echo htmlspecialchars($pergunta_individual['resposta4']); ?></li>
                </ol>
                <p><strong>Resposta Correta:</strong> <?php echo htmlspecialchars($pergunta_individual['resposta_correta']); ?></p>
            <?php else : ?>
                <p><strong>Resposta Esperada:</strong> <?php echo htmlspecialchars($pergunta_individual['resposta_correta']); ?></p>
            <?php endif; ?>

            <a href="crud_perguntas.php">Voltar</a>
        </div>

    <?php elseif ($modo_edicao && $pergunta_edicao) : ?>
        <div>
            <h2>Editar Pergunta</h2>
            <form method="post" action="crud_perguntas.php">
                <input type="hidden" name="acao" value="salvar_edicao">
                <input type="hidden" name="id_original" value="<?php echo htmlspecialchars($pergunta_edicao['id']); ?>">
                <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($pergunta_edicao['tipo']); ?>">

                <label>ID da Pergunta:</label>
                <input type="text" name="id" value="<?php echo htmlspecialchars($pergunta_edicao['id']); ?>" required>

                <label>Pergunta:</label>
                <textarea name="pergunta" required><?php echo htmlspecialchars($pergunta_edicao['pergunta']); ?></textarea>

                <?php if ($pergunta_edicao['tipo'] == 'multipla') : ?>
                    <label>Resposta 1:</label>
                    <input type="text" name="resposta1" value="<?php echo htmlspecialchars($pergunta_edicao['resposta1']); ?>" required>

                    <label>Resposta 2:</label>
                    <input type="text" name="resposta2" value="<?php echo htmlspecialchars($pergunta_edicao['resposta2']); ?>" required>

                    <label>Resposta 3:</label>
                    <input type="text" name="resposta3" value="<?php echo htmlspecialchars($pergunta_edicao['resposta3']); ?>" required>

                    <label>Resposta 4:</label>
                    <input type="text" name="resposta4" value="<?php echo htmlspecialchars($pergunta_edicao['resposta4']); ?>" required>

                    <label>Resposta Correta (Selecione apenas UMA):</label><br>
                    <input type="radio" name="resposta_correta" value="1" <?php echo $pergunta_edicao['resposta_correta'] == '1' ? 'checked' : ''; ?> required> Resposta 1
                    <input type="radio" name="resposta_correta" value="2" <?php echo $pergunta_edicao['resposta_correta'] == '2' ? 'checked' : ''; ?> required> Resposta 2
                    <input type="radio" name="resposta_correta" value="3" <?php echo $pergunta_edicao['resposta_correta'] == '3' ? 'checked' : ''; ?> required> Resposta 3
                    <input type="radio" name="resposta_correta" value="4" <?php echo $pergunta_edicao['resposta_correta'] == '4' ? 'checked' : ''; ?> required> Resposta 4
                <?php else : ?>
                    <label>Resposta Esperada:</label>
                    <textarea name="resposta" required><?php echo htmlspecialchars($pergunta_edicao['resposta_correta']); ?></textarea>
                <?php endif; ?>

                <input type="submit" value="Salvar Alterações">
                <a href="crud_perguntas.php">Cancelar</a>
            </form>
        </div>

    <?php elseif (!$tipo_pergunta && !$modo_edicao) : ?>
        <div>
            <h2>Selecionar Tipo de Pergunta</h2>
            <form method="post" action="crud_perguntas.php">
                <label>Qual tipo de pergunta deseja criar?</label>
                <select name="tipo_pergunta" required>
                    <option value="">Selecione...</option>
                    <option value="multipla">Múltipla Escolha</option>
                    <option value="discursiva">Discursiva</option>
                </select>
                <input type="submit" value="Continuar">
            </form>
        </div>

    <?php elseif ($tipo_pergunta == 'multipla' && !$modo_edicao) : ?>
        <div>
            <h2>Criar Pergunta de Múltipla Escolha</h2>
            <form method="post" action="crud_perguntas.php">
                <input type="hidden" name="acao" value="criar_multipla">

                <label>ID da Pergunta:</label>
                <input type="text" name="id" required>

                <label>Pergunta:</label>
                <textarea name="pergunta" required></textarea>

                <label>Resposta 1:</label>
                <input type="text" name="resposta1" required>

                <label>Resposta 2:</label>
                <input type="text" name="resposta2" required>

                <label>Resposta 3:</label>
                <input type="text" name="resposta3" required>

                <label>Resposta 4:</label>
                <input type="text" name="resposta4" required>

                <label>Resposta Correta (Selecione apenas UMA):</label><br>
                <input type="radio" name="resposta_correta" value="1" required> Resposta 1
                <input type="radio" name="resposta_correta" value="2" required> Resposta 2
                <input type="radio" name="resposta_correta" value="3" required> Resposta 3
                <input type="radio" name="resposta_correta" value="4" required> Resposta 4

                <input type="submit" value="Criar Pergunta">
                <a href="crud_perguntas.php">Cancelar</a>
            </form>
        </div>

    <?php elseif ($tipo_pergunta == 'discursiva' && !$modo_edicao) : ?>
        <div>
            <h2>Criar Pergunta Discursiva</h2>
            <form method="post" action="crud_perguntas.php">
                <input type="hidden" name="acao" value="criar_discursiva">

                <label>ID da Pergunta:</label>
                <input type="text" name="id" required>

                <label>Pergunta:</label>
                <textarea name="pergunta" required></textarea>

                <label>Resposta Esperada:</label>
                <textarea name="resposta" required></textarea>

                <input type="submit" value="Criar Pergunta">
                <a href="crud_perguntas.php">Cancelar</a>
            </form>
        </div>
    <?php endif; ?>

    <h2>Lista de Perguntas Cadastradas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Pergunta</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn_list = new mysqli($servidor, $username, $senha, $database);
            if ($conn_list->connect_error) {
                echo "<tr><td colspan='4'>Erro de conexão: " . $conn_list->connect_error . "</td></tr>";
            } else {
                $sql_list = "SELECT id, tipo, pergunta FROM Perguntas ORDER BY id";
                $result_list = $conn_list->query($sql_list);

                if ($result_list && $result_list->num_rows > 0) {
                    while ($dados = $result_list->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($dados['id']) . "</td>";
                        echo "<td>" . ($dados['tipo'] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva') . "</td>";
                        echo "<td>" . htmlspecialchars(substr($dados['pergunta'], 0, 50)) . "...</td>";
                        echo "<td>";
                        echo "<a href='?acao=ver&id=" . htmlspecialchars($dados['id']) . "'>Ver</a> | ";
                        echo "<a href='?acao=editar&id=" . htmlspecialchars($dados['id']) . "'>Editar</a> | ";
                        echo "<a href='?acao=excluir&id=" . htmlspecialchars($dados['id']) . "' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhuma pergunta cadastrada ainda.</td></tr>";
                }
                $conn_list->close();
            }
            ?>
        </tbody>
    </table>

    <br>
</body>

</html>