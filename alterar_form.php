<?php
  session_start();
  if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){
    
    header("Location: index.php");
      
  }
    //se não houver sessão iniciada, redireciona para o index
    
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/form_redefinir.css">
       <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="https://fonts.googleapis.com/css?family=Bevan&display=swap" rel="stylesheet">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    </head>
    
    <body>
    
       <div class="login-form">
            <h2 class="text-center">Portal de sistemas Emerj</h2>
            <fieldset> 
            <legend>Redefinir Senha:</legend>
            <form action="alterar_senha.php" method="post">
                
                <!-- ALERTS DE LOGIN -->
            <p class="text-center text-danger">
              <?php if(isset($_SESSION['loginErro'])){
                echo $_SESSION['loginErro'];
                unset($_SESSION['loginErro']);
              }?>
            </p>
            <p class="text-center text-success">
              <?php 
              if(isset($_SESSION['logindeslogado'])){
                echo $_SESSION['logindeslogado'];
                unset($_SESSION['logindeslogado']);
              }
              ?>
            </p>
                
                <div class="form-group">
                <label for="senha">Senha Atual:</label>
                    <div class="input-group">
                        <span style="background-color:#87CEFA"; class="input-group-addon"><img style="background-color:#fff"; src="images/baseline_lock_black_18dp.png"></span>
                        <input type="password" class="form-control" placeholder="Senha Atual" required name="senha" id="senha">
                    </div> 
                </div>
                <div class="form-group">
                <label for="senha2">Nova senha:</label>
                    <div class="input-group">
                        <span style="background-color:#87CEFA"; class="input-group-addon"><img style="background-color:#fff"; src="images/baseline_lock_black_18dp.png"></span>
                        <input type="password" class="form-control" placeholder="Nova Senha" name="senha2" id="senha2">
                    </div> 
                </div>
                <div class="form-group">
                <label for="senha3">Confirme a nova senha:</label>
                    <div class="input-group">
                        <span style="background-color:#87CEFA"; class="input-group-addon"><img style="background-color:#fff"; src="images/baseline_lock_black_18dp.png"></span>
                        <input type="password" class="form-control" placeholder="Confirmação Senha" name="senha3" id="senha3">
                    </div> 
                </div>
                    <br><br>
                <center><button type="submit" class="btn btn-primary">Alterar</button></center>
            </form>
                </fieldset>
        
        </div>

    
    
    
    </body>


</html>