<?php
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "3DawPerguntas"; 
$tabela = "Alunos";       

$resposta = [
    'sucesso' => false,
    'msg' => '',
    'dados' => null
];

function getConexao() {
    global $servidor, $username, $senha, $database;
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        return null;
    }
    return $conn;
}

$acao = $_REQUEST['acao'] ?? ''; 

switch ($acao) {
    case 'criar_aluno':
        criarAluno();
        break;

    case 'listar':
        listarAlunos();
        break;

    case 'excluir':
        excluirAluno();
        break;

    case 'buscar_um':
        buscarUmAluno();
        break;

    case 'salvar_edicao':
        salvarEdicao();
        break;

    default:
        $resposta['msg'] = 'Ação desconhecida.';
        echo json_encode($resposta);
}


//CRIAR
function criarAluno() {
    global $tabela, $resposta;
    $conn = getConexao();
    if (!$conn) {
        $resposta['msg'] = "Falha na conexão com o DB.";
        echo json_encode($resposta);
        return;
    }

    $matricula = trim($_POST['matricula']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("INSERT INTO $tabela (matricula, nome, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $matricula, $nome, $email);

    if ($stmt->execute()) {
        $resposta['sucesso'] = true;
        $resposta['msg'] = "Aluno cadastrado com sucesso!";
    } else {
        $resposta['msg'] = "Erro ao cadastrar o aluno: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    echo json_encode($resposta);
}

//LISTAR
function listarAlunos() {
    global $tabela, $resposta;
    $conn = getConexao();
    if (!$conn) {
        $resposta['msg'] = "Falha na conexão com o DB.";
        echo json_encode($resposta);
        return;
    }

    $sql = "SELECT matricula, nome, email FROM $tabela ORDER BY nome";
    $result = $conn->query($sql);
    
    $alunos = [];
    if ($result && $result->num_rows > 0) {
        while ($dados = $result->fetch_assoc()) {
            $alunos[] = $dados;
        }
    }
    
    $resposta['sucesso'] = true;
    $resposta['dados'] = $alunos;

    $conn->close();
    echo json_encode($resposta);
}

//EXCLUIR
function excluirAluno() {
    global $tabela, $resposta;
    $conn = getConexao();
    if (!$conn) {
        $resposta['msg'] = "Falha na conexão com o DB.";
        echo json_encode($resposta);
        return;
    }

    $matricula = $_GET['matricula'] ?? '';

    $stmt = $conn->prepare("DELETE FROM $tabela WHERE matricula = ?");
    $stmt->bind_param("s", $matricula);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $resposta['sucesso'] = true;
            $resposta['msg'] = "Aluno excluído com sucesso!";
        } else {
            $resposta['msg'] = "Nenhum aluno encontrado com essa matrícula.";
        }
    } else {
        $resposta['msg'] = "Erro ao excluir: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    echo json_encode($resposta);
}

//BUSCAR UM
function buscarUmAluno() {
    global $tabela, $resposta;
    $conn = getConexao();
    if (!$conn) {
        $resposta['msg'] = "Falha na conexão com o DB.";
        echo json_encode($resposta);
        return;
    }

    $matricula = $_GET['matricula'] ?? '';

    $stmt = $conn->prepare("SELECT matricula, nome, email FROM $tabela WHERE matricula = ?");
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $resposta['sucesso'] = true;
        $resposta['dados'] = $result->fetch_assoc();
    } else {
        $resposta['msg'] = "Aluno não encontrado.";
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($resposta);
}

//SALVAR EDIÇÃO
function salvarEdicao() {
    global $tabela, $resposta;
    $conn = getConexao();
    if (!$conn) {
        $resposta['msg'] = "Falha na conexão com o DB.";
        echo json_encode($resposta);
        return;
    }

    $matricula_original = $_POST['matricula_original'];
    $matricula = trim($_POST['matricula']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE $tabela SET matricula = ?, nome = ?, email = ? WHERE matricula = ?");
    $stmt->bind_param("ssss", $matricula, $nome, $email, $matricula_original);

    if ($stmt->execute()) {
        $resposta['sucesso'] = true;
        $resposta['msg'] = "Aluno alterado com sucesso!";
    } else {
        $resposta['msg'] = "Erro ao salvar alterações: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    echo json_encode($resposta);
}

?>