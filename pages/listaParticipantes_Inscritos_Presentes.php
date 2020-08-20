    <?php 
set_time_limit(0);
ignore_user_abort(1);
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
    
</head>

<body>

<div id="title">
    <center>
        <spam>RELATÓRIO DE EVENTOS POR PERÍODO - PARTICIPANTES INSCRITOS E PRESENTES</spam>
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


    //DIGITAR COMO DD/MM/AAAA

    if (!empty($_POST["dtInicio"]) && !empty($_POST["dtFinal"])) {
        
        $dataInicio=$_POST["dtInicio"];
        $dataFinal=$_POST["dtFinal"];
        $dataInicio = implode("-",array_reverse(explode("/",$dataInicio)));
        $dataFinal = implode("-",array_reverse(explode("/",$dataFinal)));

                                    /*
                                     =========================================
                                          Query para buscar os incritos
                                     =======================================
                                    */
        $queryEventoInscrito = "SELECT DATE_FORMAT(po.data, '%d/%m/%Y') as Data, e.nome as Evento, 
                            po.codEvento as Codigo, 
                            COUNT(DISTINCT (i.codParticipante)) as Inscritos,
                            COUNT(DISTINCT (r.codParticipante)) as Presentes
                    FROM evento e, porta po, inscricoes i, registroponto r
                    WHERE e.codigo = po.codevento and 
                          e.codigo = i.codevento and
                          po.codevento = r.codevento and
                          po.data BETWEEN '$dataInicio' and '$dataFinal' group by 
                          e.codigo order by po.data";

        $rsBuscaEvInsc = mysqli_query($conn, $queryEventoInscrito) or die(mysqli_error($conn));
        $totalRows_rsBuscaEv = mysqli_num_rows($rsBuscaEvInsc);
        

                    
        

if($totalRows_rsBuscaEv > 0) {
        echo("<table class='table table-striped' border=1 width=810>\n");

        echo("\t<tr style='background-color:#337AB7; color:white;'>\n");
            echo("\t\t<td><b> Data </b></td>\n");
            echo("\t\t<td><b> Evento </b></td>\n");
            echo("\t\t<td><b> Codigo </b></td>\n");
            echo("\t\t<td><b> Inscritos </b></td>\n");
            echo("\t\t<td><b> Presentes </b></td>\n");
        echo("\t</tr>\n");

$totalInscritos = 0;
$totalPresentes = 0;
while ($row_rsBuscaEv = mysqli_fetch_row($rsBuscaEvInsc)){
      
        echo("\t<tr>\n");
        echo("\t\t<td align=left>"   . $row_rsBuscaEv[0] . "</td>\n");
        echo("\t\t<td align='left'>" . $row_rsBuscaEv[1] . "&nbsp;</td>\n");
        echo("\t\t<td align='left'>" . $row_rsBuscaEv[2] . "&nbsp;</td>\n");
        echo("\t\t<td align='left'>" . $row_rsBuscaEv[3] . "&nbsp;</td>\n");
        echo("\t\t<td align='left'>" . $row_rsBuscaEv[4] . "&nbsp;</td>\n");
        
        $totalPresentes  += $row_rsBuscaEv[4];
        $totalInscritos  += $row_rsBuscaEv[3];
        
            //$row_rsBuscaEv = mysqli_fetch_row($rsBuscaEv);
            echo("\t</tr>\n");
        
}

        echo("\t<tr>\n");
        echo("\t\t<td colspan=3><b>Total de Inscritos e Presentes </b></td>\n");
        echo("\t\t<td colspan=1><b>" . $totalInscritos . "</b></td>\n");
        echo("\t\t<td colspan=1><b>" . $totalPresentes . " </b></td>\n");//
        echo("\t</tr>\n");

        echo("\t<tr>\n");
        echo("\t\t<td colspan=5><b>Total de Eventos Realizados: " . $totalRows_rsBuscaEv . "</b></td>\n");
        echo("\t</tr>\n");
        echo("</table>");
        /*
    } else {

        echo("<script>alert('Sem Resultado no Período Informado!')</script>");
        echo "<div class='alert alert-danger col-md-6 col-xs-8' style='font-size: 1.2em; margin-top: 50px;'>  Não existe <strong>Registro</strong> no Período Informado! </div>";

    }*/
}
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
