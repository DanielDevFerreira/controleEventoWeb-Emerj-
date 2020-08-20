<?php

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

            @media print {

                #buscar, #codEvento, legend, fieldset, label{

                    display: none;

                }

            }

        </style>

    </head>

    <body>

    <div id="title"><center><spam>RELATORIO WEBINAR - PARTICIPANTES COM DADOS INVÁLIDOS</spam></center></div>

    <form id="cadastrar_eventos" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

    <div class="container">

        <fieldset>

          <div class="row">
            <div class="form-group">
                <legend>Informe algum dado abaixo:</legend>
                      

                  <div class="col-md-3 col-xs-8">
                      <label for="codevento">Código do Evento:</label>
                      <input class="form-control" type="text" id="codEvento" name="codEvento" placeholder="Código do evento">
                  </div>
                  
                  <br><br><br><br>

                  <div class="col-md-3 col-xs-8">
                      <label for="codevento">Data de cadastro:</label>
                      <input class="form-control" onkeydown="javascript:maskData('dataEvento')" type="text" id="dataEvento" name="dataEvento" placeholder="data" maxlength="10">
                  </div>
                  
                  <br><br><br>

                  <div class="col-md-2 col-xs-3">
                      <input style="margin-top: 25px;" class="form-control btn btn-primary" type="submit" id="buscar" name="buscar" value="Listar">
                  </div>
              </div>
          </div>               
    </div>

        <br>

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

    <div class="container">

<?php


  /** 
  ======================================================================================
   @GERALDO Implementação da nova funcionalidade e validação por codigo
  ======================================================================================
**/
  if (!empty($_POST["codEvento"]) ){

      $codEvento = $_POST["codEvento"];                   
      
      /**@Geraldo query para buscar do cabeçalho da pagina*/
      $sqlCodEvento = "SELECT codEvento, nome, cpf, email, obs, insert_data
                                  FROM  webinar_invalidos
                                  WHERE  codEvento = '$codEvento'
                                  ORDER BY nome";
      $sqlResultCodEvento = mysqli_query($conn, $sqlCodEvento) or die(mysqli_error($conn));
      

      if (mysqli_num_rows($sqlResultCodEvento) == 0) {
          echo "<strong><b><h3>Não existe resultado para sua busca </h3></b></strong>";
      }else{

        echo "<table class='TDtable1 table table-striped' align='center' border=1 cellpadding='1' cellspacing='1'>";

          echo "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";
            echo "<th style width='3%''>Num.</td>";
            echo "<th style = 'width:13%' align='center'>Cod. Evento</th>";
            echo "<th style = 'width:28%' align='center'>Nome</th>";
            echo "<th style = 'width:8%' align='center'>CPF</th>";
            echo "<th style = 'width:13%' align='center'>E-mail</th>";
            echo "<th style = 'width:20%' align='center'>Motivo</th>";
            echo "<th style = 'width:15%' align='center'>Data Inserção</th>";
          echo "</tr>";
            
            $indice=1;

          while ($dadosParticipante = mysqli_fetch_row($sqlResultCodEvento)){

              $dadosParticipante[5] = implode("/",array_reverse(explode("-",$dadosParticipante[5])));

              echo "<tr class='TDtable1'>";
                  echo "<td class='TDtable1' style='text-align: center'>$indice</td>";
                  echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[0]</td>";
                  echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[1]</td>";
                  echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[2]</td>";
                  echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[3]</td>";
                  echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[4]</td>";
                  echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[5]</td>";
              echo "</tr>";
                $indice++;
            }

                echo"<tr>
                        <td colspan='7' id='btn-print' width='50%' align='center'><a href='javascript:print();'><img src='../images/impressora.png' width='36' height='35' /></a></td>
                     </tr>
            </table>";
      }

}

/** 
  ======================================================================================
   @GERALDO Implementação da nova funcionalidade e validação por data
  ======================================================================================
**/
  elseif (!empty($_POST["dataEvento"]) ){

      $dataEvento = $_POST["dataEvento"];           
      $dataEvento = implode("-",array_reverse(explode("/",$dataEvento)));
    
      $sqlCodEvento = "SELECT codEvento, nome, cpf, email, obs, insert_data
                                  FROM  webinar_invalidos
                                  WHERE  insert_data = '$dataEvento'
                                  ORDER BY nome";
      $sqlResultCodEvento = mysqli_query($conn, $sqlCodEvento) or die(mysqli_error($conn));
      
      if (mysqli_num_rows($sqlResultCodEvento) == 0) {
          echo "<strong><b><h3>Não existe resultado para sua busca </h3></b></strong>";
      }else{

          

        echo "<table class='TDtable1 table table-striped' align='center' border=1 cellpadding='1' cellspacing='1'>";

          echo "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";
              echo "<th style width='3%''>Num.</td>";
              echo "<th style = 'width:13%' align='center'>Cod. Evento</th>";
              echo "<th style = 'width:28%' align='center'>Nome</th>";
              echo "<th style = 'width:8%' align='center'>CPF</th>";
              echo "<th style = 'width:13%' align='center'>E-mail</th>";
              echo "<th style = 'width:20%' align='center'>Motivo</th>";
              echo "<th style = 'width:15%' align='center'>Data Inserção</th>";
          echo "</tr>";
            
            $indice=1;

            while ($dadosParticipante = mysqli_fetch_row($sqlResultCodEvento)){

                $dadosParticipante[5] = implode("/",array_reverse(explode("-",$dadosParticipante[5])));
                    echo "<tr class='TDtable1'>";
                        echo "<td class='TDtable1' style='text-align: center'>$indice</td>";
                        echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[0]</td>";
                        echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[1]</td>";
                        echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[2]</td>";
                        echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[3]</td>";
                        echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[4]</td>";
                        echo "<td class='TDtable1' style='text-align: center'>$dadosParticipante[5]</td>";
                    echo "</tr>";

                $indice++;
            }

                echo"<tr>
                        <td colspan='7' id='btn-print' width='50%' align='center'><a href='javascript:print();'><img src='../images/impressora.png' width='36' height='35' /></a></td>
                     </tr>
            </table>
            
              ";
      }
} 

?>
    </div>

  <!--** 
  ======================================================================================
   @GERALDO Fim da Implementação da nova funcionalidade e validação por data e codigo
  ======================================================================================
  **/ -->

        <div id="footer" style="margin-bottom:0px;">

            <br>

            <div align="center">
              <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
                Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />
              </strong></span></div>
            </div>
        </div>

        <br><br><br>

    </body>
</html>