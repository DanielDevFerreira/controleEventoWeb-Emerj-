<?php
    $vet = null;
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

header("Content-Type: text/html; charset=uft8", true);

$editFormAction = $_SERVER["PHP_SELF"];


if (isset($_POST["codEvento"])) {

$query_rsBusca = "SELECT distinct p.nome AS Nome, p.email AS Email, p.codigo as Código_Participante FROM participante p, inscricoes i, evento e WHERE i.codevento=" . $_POST["codEvento"] . " AND		p.codigo=i.codparticipante AND e.codigo=" . $_POST["codEvento"]  . " ORDER BY p.nome";

	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));	

}

if (isset($_POST["codEvento"])) {

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
    
    <div id="title"><center><spam>LISTA DE PARTICIPANTES INSCRITOS NO EVENTO</spam></center></div>

    <form id="cadastrar_eventos" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
         <div class="container">
             <fieldset>
                   <legend>Informe os dados abaixo:</legend>
                
          <div class="row">
              <div class="form-group">
                  <div class="col-md-4 col-xs-9">
                      <label for="codevento">Código do Evento:</label>
                      <input class="form-control" type="text" id="codEvento" name="codEvento" placeholder="Código do evento">
                  </div>

                  <div class="col-md-2 col-xs-4">
                      <input style="margin-top: 25px;" class="form-control btn btn-primary" type="submit" id="buscar" name="buscar" value="Buscar">
                  </div>
              </div>
          </div>               
    
        <br><br>
                          
     <div id="border"></div>  
                    
        <br><br>               
                               
            </fieldset>
        </form>

        <?php if (isset($_POST["codEvento"])) { ?>

		<table class="TDtable1 table table-striped" align="center" border=1 cellpadding="1" cellspacing="1" style="width:'910px';">
            <tr class="TDdata">

				<?php  while ($vet=mysqli_fetch_row($rsBuscaNome)) { 

					$vet[2] = implode("/",array_reverse(explode("-",$vet[2])));

					echo "<tr class='TDtable1' style='width: 100%; background-color:#337AB7; color:white;' >";
					echo "<td style='width: 100%;' colspan='4' class='TDtable1' align='center'>$vet[1] - $vet[0]<br/>Data: $vet[2]</td>";
					echo "</tr>";

					}

			?>

			</tr>			         

			<tr class="TDdata">

				<td style width="3%">Num.</td>
                <td style = "width:30%" align="center">Código Participante</td>
                <td style = "width:30%" align="center">Nome</td>
				<td style = "width:30%" align="center">E-mail</td>

			</tr>

			<?php  $i = 1;

				while ($vet=mysqli_fetch_row($rsBusca)) { 

					echo "<tr class='TDtable1'>";
					echo "<td class='TDtable1' align='center'>" . $i . "</td>"; 
					echo "<td class='TDtable1' align='center'>$vet[2]</td>";
					echo "<td class='TDtable1' align='center'>$vet[0]</td>";
					echo "<td class='TDtable1' align='center'>$vet[1]&nbsp;</td>";
					echo "</tr>"; 

					$i++;

				}

			?>

			<tr>
				<td colspan="5">
					<table width="100%">
						<tr>
                        <td id="btn-print" width="50%" align="center"><a href="javascript:print();"><img src="../images/impressora.png" width="36" height="35" /></a></td>
                        <td id="btn-print" width="50%" align="center"><a href="javascript:window.close();"><img src="../images/fechar.png" width="36" height="35" border="0" /></a></td>
					  </tr>
					</table>
				</td>
			</tr>
		</table>
        
        		<br/><br/>

<?php

	} else {

		echo("<p>Nenhum evento foi selecionado!</p><hr>");

	} ?> 
            
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