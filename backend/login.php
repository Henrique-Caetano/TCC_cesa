<?php

include ('conexao.php');

function validateInput($input) {
    $input = trim($input);
    $input = filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    return $input;
}


function authenticateUser($mysqli, $etec, $senha) {
    $stmt = $mysqli->prepare("SELECT tb01_num_etec, tb01_senha FROM tb01_login WHERE tb01_num_etec =? AND tb01_senha =?");
    $stmt->bind_param("ss", $etec, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}


if (isset($_POST['etec-number']) && isset($_POST['senha'])) {
    $etec = validateInput($_POST['etec-number']);
    $senha = validateInput($_POST['senha']);

    if (empty($etec) || empty($senha)) {
        echo "Preencha seu e-mail e senha";
    } else {
        $result = authenticateUser($mysqli, $etec, $senha);
        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();
            $_SESSION['tb01_id'] = $usuario['tb01_id'];
            $_SESSION['tb01_num_etec'] = $usuario['tb01_num_etec'];
            header("Location: ../pages/principal.html");
            exit;
        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}