<?php
    require_once('../../conexao.php');

    session_start();

    $pergunta=$_POST["pergunta"];
    $codEvento=$_POST["codEvento"];

    if(!$conn) {
    echo "No conectado";
    exit;
    }

    if (isset($_POST["codEvento"]))
        $_SESSION["codEvento"] = $_POST["codEvento"];

    if (isset($_SESSION["codEvento"])) {

    $codEvento = $_SESSION["codEvento"];
    $sql = sprintf("INSERT INTO pergunta (pergunta, codEvento) VALUES ('%s', %s)",
                           $pergunta,
                           $codEvento);					   
    $resultado = mysqli_query($conn, $sql);
    if($resultado == 1){
    $insertGoTo = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"])
                                            . "/cadastrosucesso.php";
            header(sprintf("Location: %s", $insertGoTo));
            }
            else {
            echo "inserção falhou!";
            }

    }
?>

