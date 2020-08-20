<?php

require_once ('../conexao.php');

$editFormAction = $_SERVER["PHP_SELF"];

session_start();

if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){
    unset(

        $_SESSION['usuarioId'],
        $_SESSION['usuarioNome'],
        $_SESSION['usuarioNiveisAcessoId'],
        $_SESSION['usuarioEmail']
    );

    //redirecionar o usuario para a p�gina de login

    header("Location: ../index.php");
}

header("Content-Type: text/html; charset=utf8", true);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta http-equiv="X-UA-Compatible" content="IE=11">

    <link rel="stylesheet" href="../css/alterar_evento.css">

    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link href="https://fonts.googleapis.com/css?family=Baloo&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        function alterarDados() {
            document.form2.submit();
        }

        function confirmar(texto){
            var confirmacao = confirm(texto);
            if(confirmacao == true){
                return alterarDados()
            }
            else {
                return false;
            }
        }
        function apagar(){
            confirmar(
                "Confirma Apuração do Participante?",
            )
        }

        function validaCampos(){

            var d = document.form1;
            var cod = document.getElementById('codInscricao').value;

            if (cod == ""){
                alert("O nº de inscrição deve ser preenchido!");
            }

            else {
                document.form1.submit();
            }

        }


    </script>
</head>
<body>


<div id="title">

    <center>

        <spam>APURA FREQUENCIA INDIVIDUAL DO PARTICIPANTE</spam>

    </center>

</div>

<br><br>

<div class="container">
    <form method="post" id="form1" name="form1" action="<?php echo $editFormAction; ?>">
        <div class="row">
            <div class="form-group">
                <div class="col-md-4 col-xs-6">
                    <label for="codInscricao">Código Inscrição</label>
                    <input type="text" id="codInscricao" name="codInscricao" class="form-control" placeholder="Informe código de inscrição">
                </div>

                <br>

                <div style="margin-top: 5px;" class="col-md-4 col-xs-6">
                    <input class="btn btn-primary col-md-4" type="submit" value="Buscar" onclick="validaCampos()">
                </div>
            </div>
        </div>
    </form>
</div>

<br><br>

<?php

if (empty($_POST["codInscricao"])) {
    echo " ";
}
else if (strlen($_POST["codInscricao"]) < 12) {
    echo("<script>alert('Nº de inscrição inválido, verifique se contém 14 digitos!');</script>");
}
else {

    $numeroInscricao = $_POST["codInscricao"];

    $tamanhoNumeroInscricao = strlen($numeroInscricao);

    $codEvento = substr($numeroInscricao, 2, 4);

    if ($tamanhoNumeroInscricao == 12) {

        /* se o código com 12 dígitos */

        $codParticipante = substr($numeroInscricao, -6, 7);

    } else if ($tamanhoNumeroInscricao == 14) {

        /* se o código com 14 dígitos */

        $codParticipante = substr($numeroInscricao, -7, 8);

    }

    $sql = "SELECT e.nome, p.nome, p.codigo, p.cpf, e.codigo from participante p, evento e, inscricoes i 
            WHERE p.codigo = '" . $codParticipante . "' AND e.codigo = '" . $codEvento . "' AND e.codigo = i.codEvento AND p.codigo = i.codParticipante ";

    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    $resultRow = mysqli_fetch_row($result);

    echo "<div class = 'container'>
        <table border=1 class='table table-striped'>
            <thead>
                <tr style='background-color:#337AB7; color:white;'>
                    <th></th>
                    <th>Nome do Evento</th>
                    <th>Nome Particiapante</th>
                    <th>Código Participante</th>
                    <th>CPF Participante</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type='radio' id='valor' name='valor' onclick='selecionaValor(this.value);' value='" . $resultRow[4] . "*" . $resultRow[2] . "'></td>
                    <td>  $resultRow[0] </td>
                    <td>  $resultRow[1] </td>
                    <td>  $resultRow[2] </td>
                    <td>  $resultRow[3] </td>
                </tr>
            </tbody>

        </table>
        
        <br>
        
        ";

        echo "<div class='row'>
                <div class='form-group'>
                    <div class='col-md-4 col-xs-6'>
                        <input type='submit' value='Apurar' class='btn btn-primary col-md-4' onclick='apagar()';
        }'>
                    </div>
                </div>
              </div>
              </div>";

        }
?>

<form id="form2" name="form2" method="post" action="ChamarProc.php">

    <input type='hidden' name='valorselecionado' id='valorselecionado'>

</form>

<script>
    function selecionaValor(valor) {
        //alert(valor);
        $("#valorselecionado").val(valor);
    }
</script>

</body>
</html>