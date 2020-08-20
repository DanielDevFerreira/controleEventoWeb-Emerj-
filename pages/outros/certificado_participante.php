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
        <spam>CERTIFICADO DO PARTICIPANTE</spam>
    </center>
</div>
    <br>
    
    <div class="container">
        <form method="post" action="" class="col-md-9" style="margin-left:-15px;">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Identificação do Participante</legend>

            <div class="row">
            <div class="form-group">
            <div class="col-md-3">
                <input type="radio" id="n-inscricao" name="n-inscricao"> <label class="col-form-label" for="n-inscricao">Código de Inscrição</label>
            </div>
            <div class="col-md-6">
                <input class="form-control" type="text" id="codinscricao" name="codinscricao">
            </div>
            </div>
            </div>

    <br>
            <div class="row">
            <div class="form-group">
            <div class="col-md-3">
                <input type="radio" id="manual" name="n-inscricao"> <label for="manual" class="col-form-label" for="n-inscricao">CPF</label>
            </div>
            <div class="col-md-4">    
                <input type="text" class="form-control" type="text" id="cpf" name="cpf">
            </div>
                
            <div class="col-md-2">    
               <button class="btn btn-primary col-md-12">Busca</button>
            </div>
            </div>
            </div>
            </fieldset>
        </form>
    </div>    
    
  
    
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
            <div class="col-md-12">
                <textarea id="textarea" class="col-md-12"></textarea> 
            </div>
            </div>
            </div>
        </fieldset>
        </form>
</div>
    
    <div class="container">
        <form method="post" action="" >
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Texto do Certificado</legend>
                
                
            <div class="row">
            <div class="form-group">
            <div class="col-md-12">
                <textarea id="textarea" class="col-md-12"></textarea> 
            </div>
            </div>
            </div>
    <br>
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
        <button class="btn btn-primary col-md-12 ">Gerar Relatório</button>    
    </div>   
    <div class="col-md-2 pull-right">
        <button class="btn btn-primary col-md-12 ">Gerar Texto</button>    
    </div>    
    </div>
    </div>    
    </div>
</body>

</html>
