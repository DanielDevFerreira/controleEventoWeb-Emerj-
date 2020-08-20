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

if (empty($_POST["txtNome"])) {
    echo " ";
}
else{

    $txtNome = strtoupper(trim($_POST['txtNome']));


    $qsl_ConsultaDados = "SELECT codigo AS Codigo, nome AS Nome, cpf AS CPF,
		                             matriculaEMERJ AS  Matrícula_EMERJ,  matriculaTJ AS Matrícula_TJ, 
		                             email AS Email, senha
			                  FROM   participante 
			                  WHERE  nome like '%$txtNome%'   ";
    $sql_Result = mysqli_query($conn, $qsl_ConsultaDados) or die(mysqli_error($conn));
    $totalLinhas = mysqli_num_rows( $sql_Result);
}

if (empty($_POST["cpf"])) {
    echo " ";
}
else{

    $cpfParticipante = str_replace("-","",(str_replace(".","",$_POST["cpf"])));

    $sqlCPF = "SELECT Codigo , Nome, CPF,matriculaEMERJ AS  Matrícula_EMERJ, 
	                      matriculaTJ AS Matrícula_TJ, Email, Senha
    			   FROM participante 
    			   WHERE CPF = $cpfParticipante ";

    $sql_Result = mysqli_query($conn, $sqlCPF) or die (mysqli_error($conn));
    $totalLinhas = mysqli_num_rows( $sql_Result);
}


?>

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script type="text/javascript">

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
                "Confirma o envio do Email?",
            )
        }
    </script>
</head>
<body>
    <div id="title"><center><spam>CONSULTA CADASTRO DE PARTICIPANTE</spam></center></div>

        <br><br>

    <div class="container">

        <form id="form1" name="form1" method="post" action="">
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


               <div class="col-md-6 col-xs-10">
                   <input style="margin-top: 25px; margin-left: 0px;" class="btn btn-primary col-md-2" name="Enviar" type="submit" id="Enviar" value="Enviar" />

                   <input name="hdCodParticipante" type="hidden" id="hdCodParticipante" value="0" />
               </div>
               </div>
               </div>
            <br><br>

<?php

	    if(isset($totalLinhas) && $totalLinhas > 0):

	    	echo("<table border=1 class='table table-striped' style='border:1px solid black' >\n");

			// cabe�alho da tabela
			echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

		    echo '<tr style=\'background-color:#337AB7; color:white;\'>
		    		<th>Selecionar</th>
		            <th>Código Participante</th>
		            <th>Nome</th>
		            <th>CPF</th>
		            <th>Matrícula@EMERJ</th>
		            <th>Matrícula@TJ</th>
		            <th>Email</th>  
		           
		        </tr>';

	    	while($dadosParticipante = mysqli_fetch_row($sql_Result)):

 				 echo 
 				 "<tr>
                 
                    <td><input name='portas' type='radio' id='portas' onclick='selecionaValor(this.value);' value='" . $dadosParticipante[0] . "*"
                    													       . $dadosParticipante[1] . "*"
                    													       . $dadosParticipante[5] . "*"
                    													       . $dadosParticipante[6] . "'>
                    </td>  
                    <td>" . $dadosParticipante[0] . "</td>  
                    <td>" . $dadosParticipante[1] . "</td>  
                    <td>" . $dadosParticipante[2] . "</td>  
                    <td>" . $dadosParticipante[3] . "</td>  
                    <td>" . $dadosParticipante[4] . "</td>  
                    <td>" . $dadosParticipante[5] . "</td> 
                    
                    
                 </tr>";

	        endwhile;
	    	
	    		// rodap� da tabela
				echo("\t<tr>\n");
				echo("\t\t<td colspan=3><b>Total: " . $totalLinhas . "</b></td>\n");
				echo("\t\t<td colspan=5>"); /*. ($totalLinhas - 3));*/
				echo("<input style='margin-left: 160px; padding: 5px;' class='btn btn-success' name='btListaInscricoes' type='submit' id='btListaInscricoes' value='Listar Inscricoes' /><input style='margin-left: 25px; padding: 5px'  onclick='apagar()' class='btn btn-primary' name='btAlterarDados' type='button' id='btAlterarDados' value='Enviar Link' /></td>\n");
				echo("\t</tr>\n");
				echo("</table>");

           elseif(isset($totalLinhas) && $totalLinhas == 0):
			echo "<Strong><b><h3> Não existe dados para sua busca. </h3></b></strong>";
	    endif;

	if(isset($_POST['btListaInscricoes'])):

		$dadosPartInputRadio = $_POST['portas'];//codDataNome = input de qualquer opção selecionada
	    $dadosPartInputRadio = explode('*', trim($dadosPartInputRadio));



		$sqlParticipante = "SELECT p.nome AS NomeParticipante, e.nome AS NomeEvento, e.codigo AS CodigoEvento 
		                    FROM participante p, evento e, inscricoes i 
		                    WHERE i.codParticipante= '$dadosPartInputRadio[0]' AND p.codigo = i.codParticipante AND e.codigo = i.codEvento";
        
        $sqlResultParticipante = mysqli_query($conn, $sqlParticipante) or die (mysqli_error($conn));


        if(mysqli_num_rows($sqlResultParticipante) > 0):

        	echo ("<p style='font-size: 1.3em;'><b>Participante: </b>" . $dadosPartInputRadio[1] . "</p>");
        	echo ("<b>Evento(s) inscrito(s): </b><br>");

        	foreach ($sqlResultParticipante as $dados):	
				echo ("Código: " .  $dados['CodigoEvento'] . " - ");
				echo ($dados['NomeEvento'] . "<br>");
        	endforeach;
			
		else:
            echo ("<script>alert('O participante não se inscreveu em nenhum evento') </script>");
        endif;
    endif;

?>
</form>

        <form id="form2" name="form2" method="post" action="../Arquivos_envio_email/enviar_email.php" >
            <input type='hidden' name='valorselecionado' id='valorselecionado'>
        </form>

</div>
	  <br><br>

   <div id="footer" style="margin-bottom:0px;">
       <div align="center">
          <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
            Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-2682<br />
          </strong></span></div>
      </div>
   </div>

    </div>

<script type="text/javascript">

    function selecionaValor(valor) {
        $("#valorselecionado").val(valor);
    }
</script>
  </body>
</html>