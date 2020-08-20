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
	//redirecionar o usuario para a página de login
	header("Location: ../index.php");
}

$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];

header("Content-Type: text/html; charset=utf-8", true);

$editFormAction = $_SERVER["PHP_SELF"];

if (isset($_SERVER["QUERY_STRING"])) {

  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);

}

?>

<!DOCTYPE html>
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    

<style>
    
    #title{
    color: white;
    background: #0c344c;
    font-family: 'Bree Serif', serif;
    padding: 0.75%;
    font-size: 120%;
    text-shadow: 5px 5px 5px #333;
}
    
</style>

</head>

<body>

<div id="title">
    <center>
        <spam>LISTA DE EVENTOS COM FREQUÊNCIA APURADA </spam>
    </center>
</div>
    <br>
    
<div class="container">
    <form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Pesquise o Evento</legend>

            <div class="row">
            <div class="form-group">
            <div class="col-md-6 col-xs-8">
                <label for="evento">Evento:</label>
                <input class="form-control" type="text" id="txtNome" name="txtNome" placeholder="Nome do evento">
            </div>
            </div>
            </div>

    <br>

            <div class="row">
            <div class="form-group">
            <div class="col-md-3 col-xs-5">
                <label for="evento">Período De:</label>
                <input class="form-control" type="text" id="dataInicial" name="dataInicial" maxlength="10" onKeyPress="DataHora(event, this)">
            </div>
            <div class="col-md-3 col-xs-5">
                <label for="evento">Até:</label>
                <input class="form-control" type="text" id="dataFinal" name="dataFinal" maxlength="" onKeyPress="DataHora(event, this)">
            </div>
           
             <div class="col-md-2 col-xs-5">
                <input type="submit"  name="Enviar" id="Enviar" style="margin-top:25px;" class="btn btn-primary col-md-12 form-group" value="Buscar">
             </div>
            
            </div>
            </div>
            
                <br>
            
            <div class="row">
            <div class="form-group">
            <div class="col-md-12">
               
<?php
             
                
            if (isset($_POST["txtNome"]) && (!(is_null($_POST["txtNome"])))) {



            $txtNome = $_POST["txtNome"]; 


            if (empty($_POST["txtNome"])) {
                    echo " ";
            } else {
                mysqli_select_db($conn, "emerjco_eventos");
				
				$query = "SELECT DISTINCT f.codEvento as Código, e.nome as 'Nome do Evento' from evento e, frequencia2 f WHERE e.nome LIKE '%" . $txtNome . "%' AND e.codigo = f.codEvento";

                //$query = "SELECT DISTINCT r.codEvento as Código, e.nome as 'Nome do Evento' from evento e, registroponto r WHERE e.nome LIKE '%" . $txtNome . "%' AND e.codigo = r.codEvento";
				//SELECT DISTINCT f.codEvento as Código, e.nome as 'Nome do Evento' from evento e, frequencia2 f WHERE e.nome LIKE '%teste%' AND e.codigo = f.codEvento
               
                echo "<p style='font-size:16px;'><strong>" . "Nome do Evento Pesquisado: " . $txtNome .  "</strong></p>";

                $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));

                $row_rsBusca = mysqli_fetch_row($rsBusca);

                $totalRows_rsBusca = mysqli_num_rows($rsBusca);

                $totalFields_rsBusca = mysqli_num_fields($rsBusca);

            } }



            if (empty($_POST["dataInicial"]) && empty($_POST["dataFinal"])) {
                    echo " ";
            } else {
                /* Inverte a data no formato do banco YYYYMMDD */
                $dataInicialInvertida = str_replace('/', '', $_POST["dataInicial"]);//remove as barras da data
                $retornaAnoInicialInvertida = substr($dataInicialInvertida, -4);
                $retornaMesInicialInvertida = substr($dataInicialInvertida, 2, -4);
                $retornaDiaInicialInvertida = substr($dataInicialInvertida, 0, 2);
                $retornaDataInicialInvertida = $retornaAnoInicialInvertida . $retornaMesInicialInvertida . $retornaDiaInicialInvertida;

                $dataFinalInvertida = str_replace('/', '', $_POST["dataFinal"]);//remove as barras da data
                $retornaAnoFinalInvertida = substr($dataFinalInvertida, -4);
                $retornaMesFinalInvertida = substr($dataFinalInvertida, 2, -4);
                $retornaDiaFinalInvertida = substr($dataFinalInvertida, 0, 2);
                $retornaDataFinalInvertida = $retornaAnoFinalInvertida . $retornaMesFinalInvertida . $retornaDiaFinalInvertida;


                $query = "SELECT DISTINCT f.codEvento as Código, e.nome as 'Nome do Evento' from evento e,  frequencia2 f WHERE dataPorta between " . $retornaDataInicialInvertida . " AND " . $retornaDataFinalInvertida . " AND e.codigo = f.codEvento";
                
                 $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));

                $row_rsBusca = mysqli_fetch_row($rsBusca);

                $totalRows_rsBusca = mysqli_num_rows($rsBusca);

                $totalFields_rsBusca = mysqli_num_fields($rsBusca);

                echo "<br>";                

                /* Exibe a data no formato do padrão DD/MM/AAAA */
                echo "<p style='font-size:16px;'><strong>" . "Período pesquisado: " . $_POST["dataInicial"] . " a " . $_POST["dataFinal"]. "</strong></p>";
                echo "<br>";
            }



          /*if (isset($_POST["hdCodParticipante"]) && ($_POST["hdCodParticipante"] != 0)) {
	       $query = "Call apurarEvento(" . $_POST["hdCodParticipante"]. ")";
           $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
            
           //ALERTS DA APURAÇÃO
           if ($rsBusca == 1){
            echo ('<div class="alert alert-success">Evento apurado com sucesso</div>');
            }
            else{
                echo ('<div class="alert alert-danger">Erro na apuração</div>');
            }
                
          }*/

 
            if (isset($totalRows_rsBusca) && ($totalRows_rsBusca>0)) {

            echo("<br><table class='table table-striped'   align=center>\n");



            // cabeçalho da tabela

            echo("\t<tr align=center>\n");

            for ($n=0; $n<$totalFields_rsBusca; $n++) {

                echo("\t\t<td class = 'bg-primary'><b>" . mysqli_fetch_field_direct($rsBusca, $n)->name . "</b></td>\n");

            }

            echo("\t</tr>\n");



            echo("\t<tr>\n");

            echo("\t\t<td colspan=4>&nbsp;&nbsp;&nbsp;</td>\n");

            echo("\t</tr>\n");



            for ($i=0; $i<$totalRows_rsBusca; $i++) {

                echo("\t<tr>\n");

                for ($n=0; $n<$totalFields_rsBusca; $n++) {

                    if ($n == 0) {

                        echo("\t\t<td>" . $row_rsBusca[$n] . "&nbsp;</td>\n");

                    } else {

                        echo("\t\t<td>" . $row_rsBusca[$n] . "&nbsp;</td>\n"); 

                    }

                }

                $row_rsBusca = mysqli_fetch_row($rsBusca);

                echo("\t</tr>\n");

            }
                
                



            // rodapé da tabela

            echo("\t<tr>\n");

            echo("\t\t<td colspan=4>&nbsp;&nbsp;&nbsp;</td>\n");

            echo("\t</tr>\n");



            echo("\t<tr>\n");


            echo("\t</tr>\n");



            echo("</table>");

        }



        //Exibe mensagem que apurou com sucesso

        /*
        if (isset($totalLinhas_rsBusca) && ($totalLinhas_rsBusca>0)) {
            for ($i=0; $i<$totalLinhas_rsBusca; $i++) {

                if ($i == 0) {

                    echo ("Evento apurado com sucesso!");

                } else {

                    echo ("Evento não foi apurado!");

                }

                $row_rsBusca = mysqli_fetch_row($rsBusca);

            }
            
        }
        */
     ?>
            
        </div>
        </div>
        </div>    
        </fieldset>
        </form>
</div>
   
        <br><br>
        
    <div style="padding-bottom: 25px;" class="container">
    <div class="row">
    <div class="form-group">
    <div class="col-md-2 pull-right" align="center">
        <button class="btn btn-primary col-md-12">Sair</button>    
    </div>
      
    </div>
    </div>    
    </div>

    <script>
    function DataHora(evento, objeto) {
            var keypress = (window.event) ? event.keyCode : evento.which;
            campo = eval(objeto);
            if (campo.value == '00/00/0000') {
                campo.value = ""
            }

            caracteres = '0123456789';
            separacao1 = '/';
            separacao2 = ' ';
            separacao3 = ':';
            conjunto1 = 2;
            conjunto2 = 5;
            conjunto3 = 10;
            conjunto4 = 13;
            conjunto5 = 16;
            if ((caracteres.search(String.fromCharCode(keypress)) != -1) && campo.value.length < (16)) {
                if (campo.value.length == conjunto1)
                    campo.value = campo.value + separacao1;
                else if (campo.value.length == conjunto2)
                    campo.value = campo.value + separacao1;
                else if (campo.value.length == conjunto3)
                    campo.value = campo.value + separacao2;
                else if (campo.value.length == conjunto4)
                    campo.value = campo.value + separacao3;
                else if (campo.value.length == conjunto5)
                    campo.value = campo.value + separacao3;
            } else {
                event.returnValue = false;
            }
    }   
</script>

</body>

         <div id="footer" style="margin-bottom:0px;">
<br />
    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />
      </strong></span></div>
  </div>
</div> 
</html>
