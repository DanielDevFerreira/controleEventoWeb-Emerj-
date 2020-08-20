<?php 



?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/presenca_manual.css">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>

<body>

<div id="title">
    <center>
        <spam>HORA FINAL DA PORTA</spam>
    </center>
</div>
    <br>
    
<div class="container">
    <form method="post" action="">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Seleção do Evento</legend>

            <div class="row">
            <div class="form-group">
            <div class="col-md-8">
                <label for="evento">Evento:</label>
                <input class="form-control" type="text" id="evento" name="evento" placeholder="Nome do evento">
            </div>
            </div>
            </div>

    <br>

            <div class="row">
            <div class="form-group">
            <div class="col-md-3">
                <label for="evento">Período De:</label>
                <input class="form-control" type="date" id="evento" name="evento">
            </div>
            <div class="col-md-3">
                <label for="evento">Até:</label>
                <input class="form-control" type="date" id="evento" name="evento">
            </div>
            <div class="col-md-2">
                <button style="margin-top:25px;" class="btn btn-primary col-md-12 form-group">Busca</button>
            </div>
           
            </div>
            </div>
            
            <br>
            
            <div class="row">
            <div class="form-group">
            <div class="col-md-3">
                <label for="hora-fim">Hora Fim Real:</label>
                <input class="form-control" type="text" id="hora-fim" name="hora-fim">
            </div>
            </div>
            </div>
            
                <br>
            
            <div class="row">
            <div class="form-group">
            <div class="col-md-12">
                <label>Resultado:</label>
                <textarea id="textarea" class="col-md-12"></textarea> 
            </div>
        </fieldset>
        </form>
</div>
    
        <br><br>
        
    <div style="padding-bottom: 25px;" class="container">
    <div class="row">
    <div class="form-group">
    <div class="col-md-2 pull-right">
        <button class="btn btn-primary col-md-12 ">Sair</button>    
    </div>
    <div class="col-md-2 pull-right">
        <button class="btn btn-primary col-md-12 ">Alterar</button>    
    </div>    
    </div>
    </div>    
    </div>
        
        
<div id="footer" style="margin-bottom:0px;">
<br />
    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />
      </strong></span></div>
  </div>
</div> 
</body>

</html>
