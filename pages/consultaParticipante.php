<?php

require_once('../conexao.php');

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

$editFormAction = $_SERVER["PHP_SELF"];
if (isset($_SERVER["QUERY_STRING"])) {
  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
}

if (empty($_POST["txtNome"])) {
    echo " ";
}
else{
    mysqli_select_db($conn, "emerjco_eventos");
	$query = "SELECT codigo AS Codigo, nome AS Nome, cpf AS CPF,matriculaEMERJ AS Matrícula_EMERJ, matriculaTJ AS Matrícula_TJ, email AS Email, senha AS Senha 
    FROM participante WHERE nome LIKE '%" . strtoupper(trim(stripslashes($_POST["txtNome"]))) . "%'"; //CASO NECESSITE DE MAIS CAMPOS BASTA ACRESCENTAR AO SELECT
	$rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);
	$totalFields_rsBusca = mysqli_num_fields($rsBusca);
}
if (empty($_POST["cpf"])) {
            echo " ";
    }

else{
    $cpfParticipante = str_replace("-","",(str_replace(".","",$_POST["cpf"])));
    mysqli_select_db($conn, "emerjco_eventos");
	$query = "SELECT codigo AS Codigo, nome AS Nome, cpf AS CPF,matriculaEMERJ AS Matrícula_EMERJ, matriculaTJ AS Matrícula_TJ, email AS Email, senha AS Senha 
    FROM participante WHERE cpf = '" . $cpfParticipante . "' ";
	$rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);
	$totalFields_rsBusca = mysqli_num_fields($rsBusca);
}

?>
<script language="javascript">

    // MASCARA PARA O CAMPO CPF

    function fMasc(objeto,mascara) {

        obj=objeto

        masc=mascara

        setTimeout("fMascEx()",1)

    }

    function fMascEx() {

        obj.value=masc(obj.value)

    }

    function mCPF(cpf){

        cpf=cpf.replace(/\D/g,"")

        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")

        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")

        cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")

        return cpf

    }

    function listaInscricoes() {

        document.form2.submit();
    }

</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR"><!-- InstanceBegin template="/Templates/pagina.dwt" codeOutsideHTMLIsLocked="false" -->

<!--
Design by Fernanda Santos
-->

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />

    <link rel="stylesheet" href="../css/cadastrar_eventos.css">

    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div id="title"><center><spam>CONSULTA CADASTRO DE PARTICIPANTE</spam></center></div>

        <br><br>

    <div class="container">

        <form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
           <div class="row">
             <div class="form-group">
               <div class="col-md-4 col-xs-10">
                   <label>Nome do Participante:</label>
                   <input class="form-control" name="txtNome" type="text" id="txtNome" value="" size="50" />
               </div>

               <div class="col-md-4 col-xs-10">
                   <label>CPF do Participante:</label>
                   <input class="form-control" maxlength="14"  name="cpf" type="text" id="cpf" value="" size="50" />
               </div>

               <div class="col-md-4 col-xs-10">
                   <input style="margin-top: 25px" class="btn btn-primary col-md-4" name="Enviar" type="submit" id="Enviar" value="Enviar" />
                   <input type='hidden' name='valorselecionado' id='valorselecionado'>


               </div>
               </div>
               </div>
            <br><br>
<?php
if (isset($totalRows_rsBusca) && ($totalRows_rsBusca>0)) {


	echo("<table border=1 class='table table-striped' style='border:1px solid black' >\n");
	// cabe�alho da tabela
	echo("<tr style='background-color:#337AB7; color:white;'>
            <th>Código Participante</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Matrícula@EMERJ</th>
            <th>Matrícula@TJ</th>
            <th>Email</th>
        </tr>
");

    while ($row_rsBusca = mysqli_fetch_row($rsBusca)) {

        echo "<tr>
                  <td><input name='rdCodParticipante' type='radio' id='portas' onclick='selecionaValor(this.value);' value='" . $row_rsBusca[0] . "." . $row_rsBusca[1] . "." . $row_rsBusca[5] . "." . $row_rsBusca[6] . "' /> " . $row_rsBusca[0] . "</td>
                  <td>" . $row_rsBusca[1] . "</td>
                  <td>" . $row_rsBusca[2] . "</td>
                  <td>" . $row_rsBusca[3] . "</td>
                  <td>" . $row_rsBusca[4] . "</td>
                  <td>" . $row_rsBusca[5] . "</td>
             </tr>";

    }

	// rodap� da tabela
	echo("\t<tr>\n");
	echo("\t\t<td colspan=4><b>Total: " . $totalRows_rsBusca . "</b></td>\n");
	echo("\t\t<td colspan=3><input type='submit' value='Enviar Link' onClick='listaInscricoes();' class='btn btn-primary'></td>\n");
	echo("\t</tr>\n");
	echo("</table>");

    echo "";
	echo"</form>";

}

?>

            <form id="form2" name="form2" method="post" action="<?php echo $editFormAction; ?>">

                <input type='hidden' name='valorselecionado' id='valorselecionado'>

            </form>

  <div id="font" style="font-size: 1.2em">

<?php


if(isset($_POST['valorselecionado']) ){

    $valor_query = $_POST['valorselecionado'];
    $valorReal = explode(".",$valor_query);

    echo $valorReal[0] . "<br>";
    echo $valorReal[1] . "<br>";
    echo $valorReal[2] . "<br>";
    echo $valorReal[3] . "<br>";



}
?>

  </div>

</form>           

	  <br><br>

   <div id="footer" style="margin-bottom:0px;">
       <div align="center">
          <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
            Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-2682<br />
          </strong></span></div>
      </div>
   </div>

    </div>


<script>

    function selecionaValor(valor) {

        alert(valor);
        $("#valorselecionado").val(valor);

    }

</script>
  </body>
</html>