<?php 



?>

    <!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/consultar_resultado.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        
    </head>
    
    <body>
    
        <div id="title"><center><spam>CONSULTAR RESULTADO DA PESQUISA</spam></center></div>
        
        <form id="cadastrar_eventos" action="" method="post">
            <div class="container">
                <fieldset>
                    <legend>Informe os dados abaixo:</legend>
  <div class="row">
      <div class="form-group">
          <div class="col-md-4">
              <label for="codigo">Digite o Código do Evento:</label>
              <input class="form-control" type="text" id="codigo" name="codigo" placeholder="Código do evento" required>
          </div>



          <div class="col-md-4">
              <label for="data-evento">Data do Evento:</label>
              <input class="form-control col-lg-6" type="date" id="data-evento" name="data-evento" placeholder="Nº de Vagas" required>
          </div>
      </div>
  </div>
        <br><br>
                    
        <div class="row">
      <div class="form-group">
          <div class="col-md-12">
    <table class="table">
        <caption> Resultado da Pesquisa:</caption>
        <thead>
            <tr>
              <th class="col-md-2" scope="col">Pesquisa</th>
              <th class="col-md-2" scope="col">Evento</th>
              <th class="col-md-2" scope="col">Cartaz</th>
              <th class="col-md-2" scope="col">Instalações do TJERJ</th>
              <th class="col-md-2" scope="col">Instalações da EMERJ</th>
              <th class="col-md-2" scope="col">Facebook/Twitter</th>
            </tr>
       </thead>
       <tbody>
            <tr>
              <th scope="row">consulta1</th>
              <th scope="row">consulta2</th>
              <th scope="row">consulta3</th>
              <th scope="row">consulta4</th>
              <th scope="row">consulta5</th>
              <th scope="row">consulta6</th>
            </tr>
      </tbody>
    </table>
          </div> 
     </div>
        </div> 
    
        <br><br>            
                    
        <div class="row">
      <div class="form-group">
          <div class="col-md-12">
    <table class="table">
        <caption></caption>
        <thead>
            <tr>
              <th class="col-md-2" scope="col">E-mail</th>
              <th class="col-md-2" scope="col">Indicação de Terceiros</th>
              <th class="col-md-2" scope="col">Ofício</th>
              <th class="col-md-2" scope="col">Universidade</th>
              <th class="col-md-2" scope="col">Site da EMERJ</th>
              <th class="col-md-2" scope="col">Outros</th>
              <th class="col-md-2" scope="col">Total</th>
            </tr>
       </thead>
       <tbody>
            <tr>
              <th scope="row">consulta1</th>
              <th scope="row">consulta2</th>
              <th scope="row">consulta3</th>
              <th scope="row">consulta4</th>
              <th scope="row">consulta5</th>
              <th scope="row">consulta6</th>
              <th scope="row">consulta7</th>
            </tr>
      </tbody>
    </table>
          </div> 
     </div>
        </div>                
                 
        <br><br>
               
    <div class="row">
      <div class="form-group">
          <div class="col-md-8">
    <table class="table">
        <caption> Resultado da Pesquisa:</caption>
        <thead>
            <tr>
              <th class="col-md-2" scope="col">Número</th>
              <th class="col-md-2" scope="col">Cartaz</th>
              <th class="col-md-2" scope="col">Outros</th>
              <th class="col-md-2" scope="col">Universidade</th>
            </tr>
       </thead>
       <tbody>
            <tr>
              <th scope="row">consulta1</th>
              <th scope="row">consulta2</th>
              <th scope="row">consulta3</th>
              <th scope="row">consulta4</th>
            </tr>
      </tbody>
    </table>
          </div> 
     </div>
        </div>
                  
            <div id="btn">        
         <button style="margin-left:30px;" class="btn btn-primary col-md-2 pull-right">Pesquisar</button>            
         <button class="btn btn-primary col-md-2 pull-right">Imprimir</button>         
            </div>    
                    </div>             
 </fieldset>
        </form>
          
    </body>
</html>