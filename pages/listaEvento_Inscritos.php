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
    $editFormAction = $_SERVER["PHP_SELF"];

    if (isset($_SERVER["QUERY_STRING"])) {
        
      $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
    }

    //DIGITAR COMO DD/MM/AAAA

    if (isset($_POST["dtInicio"])!="" && ($_POST["dtFinal"])!="") {
        
        $dataInicio=$_POST["dtInicio"];
        $dataFinal=$_POST["dtFinal"];
        $dataInicio = implode("-",array_reverse(explode("/",$dataInicio)));
        $dataFinal = implode("-",array_reverse(explode("/",$dataFinal)));

        $queryEv = "SELECT distinct DATE_FORMAT(po.data, '%d/%m/%Y') as Data, e.nome as Evento, po.codEvento as Codigo, count(i.codparticipante) as Inscrições FROM evento e, porta po, inscricoes i WHERE e.codigo = po.codevento and po.codevento = i.codevento and po.tipo=1 and po.data BETWEEN '$dataInicio' and '$dataFinal' group by e.codigo order by po.data";
        
        $rsBuscaEv = mysqli_query($conn, $queryEv) or die(mysqli_error($conn));
        $row_rsBuscaEv = mysqli_fetch_row($rsBuscaEv);
        $totalRows_rsBuscaEv = mysqli_num_rows($rsBuscaEv);
        $totalFields_rsBuscaEv = mysqli_num_fields($rsBuscaEv);
    }
    if (isset($_POST["dtInicio"])!="" && ($_POST["dtFinal"])!="") {
        
        $dataInicio=$_POST["dtInicio"];
        $dataFinal=$_POST["dtFinal"];
        $dataInicio = implode("-",array_reverse(explode("/",$dataInicio)));
        $dataFinal = implode("-",array_reverse(explode("/",$dataFinal)));
        
        $query = "SELECT count(i.codparticipante) as Participantes FROM inscricoes i, porta po where po.codevento = i.codevento and po.tipo=1 and po.data BETWEEN '$dataInicio' and '$dataFinal'";
        
        $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $row_rsBusca = mysqli_fetch_row($rsBusca);
        $totalRows_rsBusca = mysqli_num_rows($rsBusca);
        $totalFields_rsBusca = mysqli_num_fields($rsBusca);
    }

    ?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/evento_periodo.css">
    <!--CHAMANDO CSS QUE OCULTA ÍCONE IMPRESSORA-->
    <link rel="stylesheet" type="text/css" href="../css/print.css" media="print"/>
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    
    <script>
        
        $("input[id*='dtInicio']").inputmask({
            mask: ['99/99/9999'],
            keepStatic: true
        });
        
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
    
    <style>
        
        @media print {
            form{
                display: none;
            }
        }
        
    </style>
    
</head>

<body>

<div id="title">
    <center>
        <spam>RELATÓRIO DE EVENTOS POR PERÍODO - INSCRITOS</spam>
    </center>
</div>
    <br>
    
<div class="container">
    <form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
        
        <fieldset>
            <legend>Informe dados abaixo:</legend>


    <br>

            <div class="row">
            <div class="form-group">
                
            <div class="col-md-3 col-xs-6">
                <label for="evento">Período De:</label>
                <input class="form-control textoNormal" type="text" id="dtInicio" name="dtInicio" maxlength="10" onKeyPress="DataHora(event, this)">
            </div>
            <div class="col-md-3 col-xs-6">
                <label for="evento">Até:</label>
                <input class="form-control textoNormal" type="text" id="dtFinal" name="dtFinal" maxlength="10" onKeyPress="DataHora(event, this)">
            </div>
           
             <div class="col-md-2 col-xs-2">
                <input type="submit"  name="Enviar" id="Enviar" style="margin-top:25px;" class="btn btn-primary col-md-12 form-group" value="Buscar">
             </div>
            </div>
            </div>
            
                <br>
        </fieldset>
        </form>
    
    
<?php
  
      if (isset($totalRows_rsBuscaEv) && ($totalRows_rsBuscaEv>0)) {
        echo("<table class='table table-striped' border=1 width=810>\n");

        // Total de eventos
        echo("\t<tr style='background-color:#337AB7; color:white;'>\n");
        for ($n=0; $n<$totalFields_rsBuscaEv; $n++) {
            echo("\t\t<td><b>" . mysqli_fetch_field_direct($rsBuscaEv, $n)->name . "</b></td>\n");
        }
        echo("\t</tr>\n");

        for ($i=0; $i<$totalRows_rsBuscaEv; $i++) {
            echo("\t<tr>\n");
            for ($n=0; $n<$totalFields_rsBuscaEv; $n++) {
                if ($n == 0) {
                    echo("\t\t<td align=left>" . $row_rsBuscaEv[$n] . "</td>\n");
                } else {
                    echo("\t\t<td align='left'>" . $row_rsBuscaEv[$n] . "&nbsp;</td>\n");
                }
            }
            $row_rsBuscaEv = mysqli_fetch_row($rsBuscaEv);
            echo("\t</tr>\n");
        }

	// rodapé da tabela
	echo("\t<tr>\n");
	echo("<td colspan=4></td>");
	echo("\t</tr>\n");
	
	echo("\t<tr>\n");
	echo("\t\t<td colspan=4><b>Total de Eventos Realizados: " . $totalRows_rsBuscaEv . "</b></td>\n");
	echo("\t</tr>\n");
}
   // retorna um objeto do tipo mysqli_result. Use a propriedade num_rows do resultado. Isso é um número inteiro.   
  if (isset($rsBusca) && ($rsBusca -> num_rows > 0)) {
  	echo("\t<tr>\n");
	echo("\t\t<td colspan=4 ><b>Total de Participantes Inscritos: " . $row_rsBusca[0] . "</b></td>\n");
	echo("\t</tr>\n");
    echo("</table>");
    
}		
    ?>
</div> 
     <div id="footer" style="margin-bottom:0px;">
<br />
    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />
      </strong></span></div>
  </div>
</div> 
<!--BOTÃO IMPRESSORA -->
<div id="btn-print"  align="center"><a href="javascript:print();"><img src="../images/impressora.png" width="36px" height="35px" /></a></div>
</body>

    

</html>
