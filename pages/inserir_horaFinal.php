<?php
$acao = NULL;
    require_once('../conexao.php');
    session_start();

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
    $editFormAction = $_SERVER["PHP_SELF"];
    date_default_timezone_set('America/Sao_Paulo');
    $data = date("H:i");

    if(isset($_POST['data2'])){
        $time = $_POST['data2'];
    }

    if(isset($_POST['valorselecionado'])){

        $valorSelecionado = explode(".", $_POST['valorselecionado']);
    }



    if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inserir")) {
       
    $resultado = 'UPDATE porta SET

		 horaFimReal="'.$time.'" WHERE codEvento="'. $valorSelecionado[0] .'" AND tipo="'. $valorSelecionado[1] .'"';

	$rsUpdate =  mysqli_query($conn, $resultado) or die (mysqli_error($conn));
    
    $acao = "inserir";

    $linhaAfetada = mysqli_affected_rows($conn);
 
 
} 

?>


    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">

    <head>
    
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">
        <link rel="stylesheet" href="../css/pesquisa_evento.css">
        <link href="../css/smartphone.css" rel="stylesheet" media="screen and (min-width:150px) and (max-width:896px)">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Baloo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-1.10.0.min.js"></script>
        <script src="https://rawgit.com/RobinHerbots/Inputmask/3.x/dist/jquery.inputmask.bundle.js"></script> 
        <script src="../js/validador.js" type="text/javascript"></script>
    
    </head>
        

    <script language='JavaScript'>


        function mascara_data(evento, txtData) {
            if (verificaNumeros(evento)) {
                var data2 = '';
                data2 = data2 + txtData;
                if (data2.length == 2) {
                    data2 = data2 + '/';
                    document.form1.txtData.value = data2;
                }
                if (data2.length == 5) {
                    data2 = data2 + '/';
                    document.form1.txtData.value = data2;
                }
            }
        }


    function SomenteNumero(e){

        var tecla=(window.event)?event.keyCode:e.which;   

        if((tecla>47 && tecla<58)) return true;

        else{

            if (tecla==8 || tecla==0) return true;

        else  return false;

        }

    }
        
   function confirm() {
         if (document.form2.valorselecionado) {
            if (document.form2.valorselecionado.value != "") {
                document.form2.hdAcao.value = "inserir";
                document.form2.submit();
            } else {
                alert("Você deve selecionar um evento.");
            }
        } 
    }    
        //validação para pesquisa dos campos de codigo evento e data
        function confirm2() {
            if (document.form1.codigoEvento.value != "" || document.form1.txtData.value != "") {
                
                    document.form1.submit();
            
            } else {
                alert("Você deve informa uma busca!")
                return false;
            }

    }



    </script>

    <body style="margin-top:0px; padding-top:0px;">

        <div id="title"><center><spam>INSERIR HORA FINAL EVENTO:</spam></center></div>
        
      <br/>

        <div align="center">
        <div align="right" style="width:900px;">
            
    <?php  
    
        echo "<strong><span style='font-family:Arial, Helvetica, sans-serif'>" . "Data da consulta: " . date("d/m/Y") . " às ". date('H:i:s') . "</span></strong>";
    ?>

        </div>
        </div>
        
    <div class="container">    

    <form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">

    <fieldset>

        <legend>Informe os dados abaixo:</legend>

        
    <div class="row">
      <div class="form-group">
          <div class="col-md-4 col-xs-6">
              <label for="codevento">Código:</label>
              <input class="form-control" maxlength="4" type="text" id="codigoEvento" name="codigoEvento" placeholder="Código do evento" onkeypress='return SomenteNumero(event)'>
          </div>

          <div class="col-md-4 col-xs-6">
              <label for="dateevento">Data do Evento:</label>
              <input class="form-control textoNormal" type="text" id="txtData" name="txtData" placeholder="Data do Evento" onKeyPress="mascara_data(event, this.value);" maxlength="10">
         </div>
      </div>
    </div>

   <br>                              
                    
  <div class="row">
      <div class="form-group">
          <div class="col-md-2 col-xs-4">
              <input style="margin-top: 25px;" class="form-control btn btn-primary" type="reset" id="limpar" name="reset" value="Limpar Pesquisas">        
          </div>
          
          <div class="col-md-2 col-xs-4">
                 <input style="margin-top:25px;" class="btn btn-primary" name="Enviar2" type="submit" id="Enviar2" onclick="confirm2()" value="Pesquisar"/>    
          </div>
     </div>
  </div>


    <br><br>
        
     <div style="border-bottom: 1px solid #000"></div>
        </fieldset>
  </form>        
</div>
        
    <br/>
        
        <div class="container">

    <?php

    /* PESQUISA POR CÓDIGO */

    if (empty($_POST["codigoEvento"])) {

            echo " ";

    }

    else{

	mysqli_select_db($conn, "emerjco_eventos");

	$query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, p.horaInicio, p.horaFim, p.tipo from porta p, evento e where e.codigo = " . $_POST["codigoEvento"] . " AND p.codevento= " . $_POST["codigoEvento"])));

	echo("<p style='font-size:16px; font-family:Arial, Helvetica, sans-serif;'><strong>Código pesquisado: " . $_POST["codigoEvento"] . "</strong></p>");

	echo "<br>";

	if (substr($query, 0, 1) == "s") { 

		$rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));

		$row_rsBusca = mysqli_fetch_row($rsBusca);

		$totalRows_rsBusca = mysqli_num_rows($rsBusca);

		$totalFields_rsBusca = mysqli_num_fields($rsBusca);

	} else {

		switch(substr($query, 0, 1)) {

			case "i":

				echo("<p>Inserção");

				break;

			case "u":

				echo("<p>Atualização");

				break;

			case "d":

				echo("<p>Exclusão");

				break;

		}

		$result = mysqli_query($conn, $query);

		if ($result) {

			echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows($conn) . " linhas afetadas.</b></p>");	

		} else {

			echo(" não realizada - ERRO!</p>");

			} 

	}

}



    /* PESQUISA POR DATA */

    if (empty($_POST["txtData"])) {

            echo " ";

    } else {

	/* Inverte a data no formato do banco YYYYMMDD */

	$dataInvertida = $_POST["txtData"];

	$retornaAnoInvertida = substr($dataInvertida, -4);

	$retornaMesInvertida = substr($dataInvertida, 2, -4);

	$retornaDiaInvertida = substr($dataInvertida, 0, 2);

	$retornaDataInvertida = $retornaAnoInvertida . $retornaMesInvertida . $retornaDiaInvertida;

	echo str_replace("/","",$retornaDataInvertida);

	mysqli_select_db($conn, "emerjco_eventos");

	$query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, p.horaInicio, p.horaFim, p.tipo from porta p, evento e where p.data = '" . $retornaDataInvertida . "' and p.codevento = e.codigo order by p.data desc")));

	echo "<br>";

	/* Coloca a data no formato do padrão DDMMAAAA */

	$dataFormatada = $_POST["txtData"];

	$retornaAnoFormatada = substr($dataFormatada, -4);

	$retornaMesFormatada = substr($dataFormatada, 2, -4);

	$retornaDiaFormatada = substr($dataFormatada, 0, 2);

	/* Exibe a data no formato do padrão DD/MM/AAAA */

	echo "<p style='font-size:16px; text-align:center; font-family:Arial, Helvetica, sans-serif;'><strong>" . "Data pesquisada: " . $_POST['txtData'] . "</strong></p>";

	echo "<br>";

	if (substr($query, 0, 1) == "s") { 

		$rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));

		$row_rsBusca = mysqli_fetch_row($rsBusca);

		$totalRows_rsBusca = mysqli_num_rows($rsBusca);

		$totalFields_rsBusca = mysqli_num_fields($rsBusca);

	} else {

		switch(substr($query, 0, 1)) {

			case "i":

				echo("<p>Inserção");

				break;

			case "u":

				echo("<p>Atualização");

				break;

			case "d":

				echo("<p>Exclusão");

				break;

		}

		$result = mysqli_query($conn, $query);

		if ($result) {

			echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows() . " linhas afetadas.</b></p>");

		} else {

			echo(" não realizada - ERRO!</p>");

		}

	}

}


       // Criando a tabela da consulta de acordo com a pesquisa utilizada.

    if (isset($totalRows_rsBusca) && ($totalRows_rsBusca>0)) {

	echo("<table  class='table table-striped'  cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:13px; font-family:Arial, Helvetica, sans-serif;'>\n");



	// cabeçalho da tabela

	echo("\t<tr style= 'background-color: #337AB7; color:white;'>\n");

	//for ($n=0; $n<$totalFields_rsBusca; $n++) {

	for ($n=0; $n<1; $n++) {

		//echo("\t\t<td><b>" . mysql_field_name($rsBusca, $n) . "</b></td>\n");

		echo("\t\t<td align='center'><b>" . "" . "</b></td>\n");

		echo("\t\t<td align='center'><b>" . "Data" . "</b></td>\n");

		echo("\t\t<td align='center'>&nbsp;<b>" . "Código" . "</b>&nbsp;</td>\n");

		echo("\t\t<td>&nbsp;<b>" . "Nome" . "</b>&nbsp;</td>\n");

		echo("\t\t<td>&nbsp;<b>" . "Local" . "</b>&nbsp;</td>\n");

		echo("\t\t<td>&nbsp;<b>" . "Tipo" . "</b>&nbsp;</td>\n");

		echo("\t\t<td>&nbsp;<b>" . "Hora Início" . "</b>&nbsp;</td>\n");

		echo("\t\t<td>&nbsp;<b>" . "Hora Fim" . "</b>&nbsp;</td>\n");

		echo("\t\t<td>&nbsp;<b>" . "" . "</b>&nbsp;</td>\n");

	}

	echo("\t</tr>\n");



	for ($i=0; $i<$totalRows_rsBusca; $i++) {

		echo("\t<tr>\n");

		//for ($n=0; $n<$totalFields_rsBusca; $n++) {

		for ($n=0; $n<1; $n++) {

			//echo("\t\t<td align='center' style:'padding:15px'>" . $row_rsBusca[$n] . "&nbsp;</td>\n");


			$padraoFormatoData = date('d/m/Y', strtotime($row_rsBusca[0]));

			echo("\t\t<td><input name='rdCodevento' type='radio' id='rdCodevento' value='" . $row_rsBusca[1] . "." . $row_rsBusca[6] . "' onclick='selecionaValor(this.value);' /></td>\n");

			echo("\t\t<td align='center' style:'padding:15px'>&nbsp;" . $padraoFormatoData . "&nbsp;</td>\n");

			echo("\t\t<td align='center' style:'padding:15px'>&nbsp;" . $row_rsBusca[1] . "&nbsp;</td>\n");

			echo("\t\t<td style:'padding:15px'>&nbsp;" . $row_rsBusca[2] . "&nbsp;</td>\n");

			echo("\t\t<td style:'padding:15px'>&nbsp;" . $row_rsBusca[3] . "&nbsp;</td>\n");

			echo("\t\t<td style:'padding:15px'>&nbsp;" . $row_rsBusca[6] . "&nbsp;</td>\n");

			echo("\t\t<td style:'padding:15px'>&nbsp;" . $row_rsBusca[4] . "&nbsp;</td>\n");

			echo("\t\t<td style:'padding:15px'>&nbsp;" . $row_rsBusca[5] . "&nbsp;</td>\n");

			echo("<input type='hidden' id='tipoPorta' name='tipoPorta' value='" . $row_rsBusca[6] . "'");



		}

		$row_rsBusca = mysqli_fetch_row($rsBusca);

		echo("\t</tr>\n");

	}

	// rodapé da tabela

	echo("\t\t<td colspan='5'><b><span style='font-size:14px; font-family:Arial, Helvetica, sans-serif;'>Total de Eventos: " . $totalRows_rsBusca . "</span></b></td>\n");

	echo("\t</tr>\n");

	echo("</table>");

}

?>

        

   <br><br> 


    <!-- Formulário para inserir Hora final -->
            
           

  <div class="container">
      <form name="form2" id="form2" action="<?php echo $editFormAction; ?>" method="post">

     <div class="row">
        <div class="form-group">
            <div class="col-md-2 col-xs-4">                
                <label>Hora Fim Real:</label>
                <input style="border: 1px solid #000; font-size: 20px; text-align:center; font-family: 'Baloo', cursive;" class="col-md-12 form-control" type='time' name='data2' id='data2' value="<?php echo $data ?>">
                <input type='hidden' name="valorselecionado" id='valorselecionado'>
                 <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_POST["hdAcao"])) echo ($_POST["hdAcao"]); ?>"/>
                <input name="codEvento" type="hidden" id="codEvento" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-2"> 
                <input style="margin-top:10px;"  class="btn btn-primary" name='inserir' type='submit' id='inserir' onClick='confirm();' value='Inserir HoraFim' />
            </div>
        </div>
    </div>

    </form>

    </div>

    <br><br><br>

    <script>

        function selecionaValor(valor){

           // alert(valor);

            $("#valorselecionado").val(valor);

        }

    </script>

        <div id="footer" style="margin-bottom:0px;">

    <br/>
            
  <?php 
        if ($acao == "inserir") {
	       echo("<p><script> alert('Hora Fim inserida com Sucesso!'); </script></p><hr>");
	       echo("<script> window.location.href='inserir_horafinal.php' </script>");
        }
    ?> 

    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />
      </strong></span></div>

  </div>

</div>
</div>
</body>

</html>