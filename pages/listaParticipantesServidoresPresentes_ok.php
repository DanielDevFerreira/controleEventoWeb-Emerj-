<?php

    $vet = null;

	require_once('../conexao.php');

    session_start();

set_time_limit(0);
ignore_user_abort(1);

    if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){

	unset(

		$_SESSION['usuarioId'],

		$_SESSION['usuarioNome'],

		$_SESSION['usuarioNiveisAcessoId'],

		$_SESSION['usuarioEmail']

		

	);

	//redirecionar o usuario para a página de login

	header("Location: ../index.php");

}



$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];



header("Content-Type: text/html; charset=uft8", true);



$editFormAction = $_SERVER["PHP_SELF"];





if (!empty($_POST["codEvento"])) {
/** 
   @Geraldo trocado isste pelo empty
**/


$query_rsBusca = "SELECT distinct p.nome AS Nome, p.cpf as CPF, p.matriculaTJ AS 'Matrícula TJ', p.email AS Email FROM participante p, frequencia2 f WHERE f.codevento=" . $_POST["codEvento"] . " AND	p.matriculaTJ is not null AND p.codigo=f.codparticipante ORDER BY p.nome";

	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));	



}



if (!empty($_POST["codEvento"])) {
/** 
   @Geraldo trocado isste pelo empty
**/


	$query_rsBuscaNome = "SELECT distinct e.nome, e.codigo, po.data FROM evento e, porta po WHERE e.codigo=" . $_POST["codEvento"] . " and po.codevento=e.codigo";



	$rsBuscaNome = mysqli_query($conn, $query_rsBuscaNome) or die(mysqli_error($conn));	



}



?>

    <!DOCTYPE html>

<html lang="pt-BR">

    <head>

        

        <link rel="stylesheet" href="../css/cadastrar_eventos.css">

        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!--CHAMANDO CSS QUE OCULTA ÍCONE IMPRESSORA-->

        <link rel="stylesheet" type="text/css" href="../css/print.css" media="print"/>

        <meta http-equiv="Content-Type" content="text/html; charset=utf8">

        <style>

        

            #prox{

                color: red;

                font-size: 1.5em;

                font-family: cursive;

            }

            

            #border{

                 border-bottom: 1px solid teal

            }







            @media print {

                #buscar, #codEvento, legend, fieldset, label{

                    display: none;

                }

            }





        

        </style>

    </head>

    

    <body>

    

    <div id="title"><center><spam>LISTA PARTICIPANTE/SERVIDOR PRESENTE NO EVENTO</spam></center></div>



    <form id="cadastrar_eventos" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

         <div class="container">

             <fieldset>

                   <legend></legend>

                

          <div class="row">

              <div class="form-group">
                      <strong style="text-align: center;"><h4><b>
                        Escolha uma opção para busca
                      </b></h4></strong>
                      

                  <div class="col-md-4 col-xs-9">

                      <label for="codevento">Código do Evento:</label>

                      <input class="form-control" type="text" id="codEvento" name="codEvento" placeholder="Código do evento">

                  </div>
                  
                  <br><br><br><br>

                  <div class="col-md-4 col-xs-9">

                      <label for="codevento">Nome do Evento:</label>

                      <input class="form-control" type="text" id="nomeEvento" name="nomeEvento" placeholder="Nome do evento">

                  </div>
                  <br><br><br><br>
                        <!--
                           @Geraldo Inclusao do campo data evento
                        -->
                  <div class="col-md-4 col-xs-9">

                      <label for="codevento">Data do Evento:</label>

                      <input class="form-control" onkeydown="javascript:maskData('dataEvento')" type="text" id="dataEvento" name="dataEvento" placeholder="data do evento" maxlength="10">

                  </div>
                  <br><br><br>




                  <div class="col-md-2 col-xs-4">

                      <input style="margin-top: 25px;" class="form-control btn btn-primary" type="submit" id="buscar" name="buscar" value="Listar Evento">

                  </div>

              </div>

          </div>               

    

        <br><br>

                          

     <div id="border"></div>  

                    

        <br><br>               

                               

            </fieldset>

        </form>


<!--@Geraldo -->
<script type="text/javascript">
  /** 
   @Geraldo Função para preenchimento da mascara da data 00/00/0000 no campo data do form
**/

  function maskData(data){
    obj = document.getElementById(data);
    vl = obj.value;
    l = vl.toString().length;
    
    switch(l){
      case 2:
      obj.value = vl+"/";
      break;
      case 5:
      obj.value = vl+"/";
      break;
    }
  }

</script>










        <?php if (!empty($_POST["codEvento"])) { ?>

  <!-- 
   @Geraldo alterado do isset para !empty
-->

		<table class="TDtable1 table table-striped" align="center" border=1 cellpadding="1" cellspacing="1" style="width:'810px';">

            <tr class="TDdata">



				<?php  while ($vet=mysqli_fetch_row($rsBuscaNome)) { 



					$vet[2] = implode("/",array_reverse(explode("-",$vet[2])));



					echo "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";

					echo "<td colspan='5' class='TDtable1' align='center'>Nome do Evento: $vet[0] <br/> Data: $vet[2] - Código do Evento: $vet[1]</td>";

					echo "</tr>";



					}



			?>



			</tr>			         



			<tr class="TDdata">



				<td style width="3%">Num.</td>

                <td style = "width:80%" align="center">Nome</td>
                
                <td style = "width:25%" align="center">CPF</td>

				<td style = "width:25%" align="center">Matrícula</td>

				<td style = "width:47%" align="center">E-mail</td>



			</tr>



			<?php  $i = 1;



				while ($vet=mysqli_fetch_row($rsBusca)) { 



					echo "<tr class='TDtable1'>";

					echo "<td class='TDtable1' align='center'>" . $i . "</td>"; 

					echo "<td class='TDtable1' align='left'>$vet[0]</td>";

					echo "<td class='TDtable1' align='center'>$vet[1]&nbsp;</td>";

					echo "<td class='TDtable1' align='left'>$vet[2]&nbsp;</td>";
					
					echo "<td class='TDtable1' align='left'>$vet[3]</td>";

					echo "</tr>"; 



					$i++;

				}



			?>

			

			<tr>

			<td colspan="4"></td>

			</tr>

			

			<tr>

			<td colspan="4"></td>

			</tr>

			

			<tr>

				<td colspan="5">

					<table width="100%">

						<tr>

                        <td id="btn-print" width="50%" align="center"><a href="javascript:print();"><img src="../images/impressora.png" width="36" height="35" /></a></td>

					  </tr>

					</table>

				</td>

			</tr>

		</table>

        

        		<br/><br/>



<?php


}



  /** 
  ======================================================================================
   @GERALDO Implementação da nova funcionalidade e validação por data
  ======================================================================================
**/
  elseif (/*!empty($_POST["nomeEvento"]) and*/ !empty($_POST["dataEvento"]) ){

      $dataEvento = $_POST["dataEvento"];                   
      $anoInvertida = substr($dataEvento, -4);//Função pegar ano
      $mesInvertido = substr($dataEvento, 3, 2);//Função pegar mês
      $diaInvertido = substr($dataEvento, 0, 2 );////Função pegar dia
      $dataInvertida = $anoInvertida . "-". $mesInvertido ."-". $diaInvertido;
      
      /**@Geraldo query para buscar do cabeçalho da pagina*/
      $sqlDataEvento = "SELECT DISTINCT e.nome, e.codigo, po.data 
                                  FROM  evento e, porta po
                                  WHERE  po.data = '$dataInvertida' and  e.codigo = po.codevento  
                                  ORDER BY e.nome";
      $sqlResultDataEvento = mysqli_query($conn, $sqlDataEvento) or die(mysqli_error($conn));
      

      switch (mysqli_num_rows($sqlResultDataEvento)) {
        case 0:
          echo "<strong><b><h3>Não existe resultado para sua busca </h3></b></strong>";
          break;
        
        default:
            
            echo "<table class='TDtable1 table table-striped' align='center' border=1 cellpadding='1' cellspacing='1'>";
                echo '<form method="POST" action="">';
                

            echo "<tr class='TDdata'>";
            echo    "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";
            echo        "<td colspan='4' class='TDtable1' align='center' ><h3>Selecionar Evento: </h3></td>";
            echo    "</tr>";
            echo "</tr>";

                echo "<tr class='TDdata' style='background-color:#337AB7; color:white;' >";
                  echo "  <td style = 'width:3%' align='center'>Selecionar</td>";
                  echo "  <td style=  'width:'3%' align='center'>Cod.Evento</td>";
                  echo "  <td style = 'width:14%' align='center'>Data</td>";
                  echo "  <td style = 'width:80%' align='center'>Nome</td>";
                echo "</tr>";

            
            while ($data = mysqli_fetch_array($sqlResultDataEvento)) {  
              $data[2] = implode("/",array_reverse(explode("-",$data[2])));

              echo "<tr class='TDtable1'>";
                  echo "<td class='TDtable1' align='center'>";
                  echo "<input type='radio' name='dataOpcao' value='  " . $data[0] . "." 
                                                                        . $data[1] . "." 
                                                                        . $data[2] . " ' > </td>"; 
                  /* Valores concatnados e dpois quando envadio o post dataOpcao(radio selecionado) com  delimitador (.)
                      Posição[0] nome do evento
                      Posição[1] codigo do evento
                      Posição[2] porta do evento
                  */
                  echo "<td class='TDtable1' align='center'>$data[1]</td>";
                  echo "<td class='TDtable1' align='center'>$data[2]</td>";
                  echo "<td class='TDtable1' align='left'>$data[0]</td>"; 

                
            }

                
                echo "</tr>"; 
            echo "</table>";

            echo "<table>";
                echo "<tr>";
                  echo "<td >";
                    echo "<input style='margin-top: 25px;' class='form-control btn btn-primary' type='submit'  value='Listar Servidores' name='buscaRadioData' id='buscaRadioData' />"; 
                  echo "</td>";
                echo "</tr>";

              echo "</form>";
            echo "</table>";

            
        
      break;
      }


	} 




/** 
  ======================================================================================
   @GERALDO Implementação da nova funcionalidade e validação por nome
  ======================================================================================
**/
  elseif (!empty($_POST["nomeEvento"]) ){
      $nomeEvento = $_POST["nomeEvento"];
      
      
      /**@Geraldo query para buscar do cabeçalho da pagina*/
      $sqlNomeEvento = "SELECT DISTINCT e.nome, e.codigo, po.data 
                                  FROM  evento e, porta po
                                  WHERE  e.nome like '%$nomeEvento%' and  e.codigo = po.codevento  
                                  ORDER BY e.nome";
      $sqlResultNomeEvento = mysqli_query($conn, $sqlNomeEvento) or die(mysqli_error($conn));
      

      switch (mysqli_num_rows($sqlResultNomeEvento)) {
        case 0:
          echo "<strong><b><h3>Não existe resultado para sua busca </h3></b></strong>";
          break;
        
        default:
              
            echo "<table class='TDtable1 table table-striped' align='center' border=1 cellpadding='1' cellspacing='1'>";
                echo '<form method="POST" action="">';
                

            echo "<tr class='TDdata'>";
            echo "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";
            echo "<td colspan='4' class='TDtable1' align='center' ><h3>Selecionar Evento: </h3></td>";
            echo "</tr>";
            echo "</tr>";

                echo "<tr class='TDdata' style='background-color:#337AB7; color:white;' >";
                  echo "  <td style = 'width:3%' align='center'>Selecionar</td>";
                  echo "  <td style= width='3%' text-align='center'>Cod.Evento</td>";
                  echo "  <td style = 'width:14%' align='center'>Data</td>";
                  echo "  <td style = 'width:80%' align='center'>Nome</td>";
                echo "</tr>";

            
            while ($nome = mysqli_fetch_array($sqlResultNomeEvento)) {  

              $nome[2] = implode("/",array_reverse(explode("-",$nome[2])));
              echo "<tr class='TDtable1'>";
                  echo "<td class='TDtable1' align='center'>";
                  echo "<input type='radio' name='nomeOpcao' value='  " . $nome[0] . "." 
                                                                        . $nome[1] . "." 
                                                                        . $nome[2] . " ' > </td>"; 
                  /* Valores concatnados e dpois quando envadio o post dataOpcao(radio selecionado) com  delimitador (.)
                      Posição[0] nome do evento
                      Posição[1] codigo do evento
                      Posição[2] porta do evento
                  */
                  echo "<td class='TDtable1' align='center'>$nome[1]</td>";
                  echo "<td class='TDtable1' align='center'>$nome[2]</td>";
                  echo "<td class='TDtable1' align='left'>$nome[0]</td>"; 

                
            }
              echo "</tr>"; 
              echo "</table>";



              echo "<table>";
                echo "<tr>";
                  echo "<td >";
                    echo "<input style='margin-top: 25px;' class='form-control btn btn-primary' type='submit'  value='Listar Servidores' name='buscaRadioData' id='buscaRadioData' />"; 
                  echo "</td>";
                echo "</tr>";

              echo "</form>";
              echo "</table>";            
        
      break;
      }


  } 



/** 
  ======================================================================================
   @GERALDO lista de servidores depois de selecionar o input radio na buscar por nome 
  ======================================================================================
  **/
  
  $valorInput = null;
  if (isset($_POST['nomeOpcao'])){

    $valorInput = $_POST['nomeOpcao'];
    $valorInput = explode('.', trim($valorInput));
    $valorInput[2] = implode("-",array_reverse(explode("/",$valorInput[2])));
    
    $query_buscarServidor = "SELECT distinct p.nome AS Nome, p.cpf as CPF, p.matriculaTJ AS 'Matrícula TJ', p.email AS                                       Email
                                FROM participante p, frequencia2 f, evento e, porta po
                                WHERE po.data = '$valorInput[2]' AND 
                                      f.dataPorta = '$valorInput[2]' AND
                                      p.codigo= f.codparticipante AND 
                                      f.codEvento = '$valorInput[1]' AND 
                                      p.matriculaTJ is not null 
                                ORDER BY p.nome";

    $query_rsBuscarServidor = mysqli_query($conn, $query_buscarServidor) or die(mysqli_error($conn));

          $rowServidor = mysqli_num_rows($query_rsBuscarServidor);
          if($rowServidor>0){
            $valorInput[2] = implode("/",array_reverse(explode("-",$valorInput[2])));

                echo "<table class='TDtable1 table table-striped' align='center' border=1 cellpadding='1' cellspacing='1'>";
                    echo "<tr class='TDdata'>";
                        echo "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";
                            echo "<td colspan='5' class='TDtable1' align='center'>Nome do Evento: $valorInput[0] <br/> Data: $valorInput[2] - Código do Evento: $valorInput[1]</td>";
                        echo "</tr>";
                    echo "</tr>";              
                    echo " <tr class='TDdata'>";
                        echo "  <td style width='3%''>Num.</td>";
                        echo "  <td style = 'width:80%' align='center'>Nome</td>";
                        echo "  <td style = 'width:25%' align='center'>CPF</td>";
                        echo "  <td style = 'width:25%' align='center'>Matrícula</td>";
                        echo " <td style = 'width:47%' align='center'>E-mail</td>";
                    echo "</tr>";
            
            $indice=1;          
            while ($variavel = mysqli_fetch_row($query_rsBuscarServidor)){
                    echo "<tr class='TDtable1'>";
                        echo "<td class='TDtable1' align='center'>$indice</td>"; 
                        echo "<td class='TDtable1' align='left'>$variavel[0]</td>";
                        echo "<td class='TDtable1' align='center'>$variavel[1]</td>";
                        echo "<td class='TDtable1' align='left'>$variavel[2]</td>";
                        echo "<td class='TDtable1' align='left'>$variavel[3]</td>";
                    echo "</tr>"; 
                $indice++;
            }

                echo"<tr>
                        <td colspan='5'></td>
                    </tr>
                    <tr>
                       <td colspan='5'></td>
                    </tr>
                    <tr>
                      <td colspan='5'>
                        <table width='100%'>
                          <tr>
                             <td id='btn-print' width='50%' align='center'><a href='javascript:print();'><img src='../images/impressora.png' width='36' height='35' /></a></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
              <br/><br/>";
          }else{
            echo "<strong> <b> <h2>" . "Não existe dados para sua buscar" . "</h2></b> </strong>";
          }

  }

     

  /** 
  ======================================================================================
   @GERALDO lista de servidres depois de selecionar o input radio na buscar por data 
  ======================================================================================
  **/
  
  $valorInput = null;
  if (isset($_POST['dataOpcao'])){

    $valorInput = $_POST['dataOpcao'];
    $valorInput = explode('.', trim($valorInput)); //Pego do post e retirado espaco e quebrado pelo delimitador .

    
    $valorInput[2] = implode("-",array_reverse(explode("/",$valorInput[2])));//Invertidado para comparar no anco

    $query_buscarServidor = "SELECT distinct p.nome AS Nome, p.cpf as CPF, p.matriculaTJ AS 'Matrícula TJ', p.email AS                                       Email
                                FROM participante p, frequencia2 f, evento e, porta po
                                WHERE po.data = '$valorInput[2]' AND 
                                      f.dataPorta = '$valorInput[2]' AND
                                      p.codigo= f.codparticipante AND 
                                      f.codEvento = '$valorInput[1]' AND 
                                      p.matriculaTJ is not null 
                                ORDER BY p.nome";
    
    $query_rsBuscarServidor = mysqli_query($conn, $query_buscarServidor) or die(mysqli_error($conn));

          $rowServidor = mysqli_num_rows($query_rsBuscarServidor);
          if($rowServidor>0){
                $valorInput[2] = implode("/",array_reverse(explode("-",$valorInput[2]))); //retornado para o normal data
                echo "<table class='TDtable1 table table-striped' align='center' border=1 cellpadding='1' cellspacing='1'>";
                    echo "<tr class='TDdata'>";
                        echo "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";
                            echo "<td colspan='5' class='TDtable1' align='center'>Nome do Evento: $valorInput[0] <br/> Data: $valorInput[2] - Código do Evento: $valorInput[1]</td>";
                        echo "</tr>";
                    echo "</tr>";              
                    echo " <tr class='TDdata'>";
                        echo "  <td style width='3%''>Num.</td>";
                        echo "  <td style = 'width:80%' align='center'>Nome</td>";
                        echo "  <td style = 'width:25%' align='center'>CPF</td>";
                        echo "  <td style = 'width:25%' align='center'>Matrícula</td>";
                        echo " <td style = 'width:47%' align='center'>E-mail</td>";
                    echo "</tr>";
            
            $indice=1;          
            while ($variavel = mysqli_fetch_row($query_rsBuscarServidor)){
                    echo "<tr class='TDtable1'>";
                        echo "<td class='TDtable1' align='center'>$indice</td>"; 
                        echo "<td class='TDtable1' align='left'>$variavel[0]</td>";
                        echo "<td class='TDtable1' align='center'>$variavel[1]</td>";
                        echo "<td class='TDtable1' align='left'>$variavel[2]</td>";
                        echo "<td class='TDtable1' align='left'>$variavel[3]</td>";
                    echo "</tr>"; 
                $indice++;
            }

                echo"<tr>
                        <td colspan='5'></td>
                    </tr>
                    <tr>
                       <td colspan='5'></td>
                    </tr>
                    <tr>
                      <td colspan='5'>
                        <table width='100%'>
                          <tr>
                             <td id='btn-print' width='50%' align='center'><a href='javascript:print();'><img src='../images/impressora.png' width='36' height='35' /></a></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
              <br/><br/>";
          }else{
            echo "<strong> <b> <h2>" . "Não existe dados para sua buscar" . "</h2></b> </strong>";
          }

  }

?>     
  <!--** 
  ======================================================================================
   @GERALDO Fim da Implementação da nova funcionalidade e validação por data e nome
  ======================================================================================
  **/ -->
            </div>

        

        <div id="footer" style="margin-bottom:0px;">

<br>

            <div align="center">

              <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />

                Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />

              </strong></span></div>

            </div>

        </div> 

          

    </body>

</html>