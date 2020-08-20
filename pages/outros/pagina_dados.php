<?php

require_once('../conexao.php');

//SELECT PARA PESQUISAR INSCRIÇÃO
 $query_rsBusca = "SELECT  e.nome as nomeEvento, po.codEvento as CodigoEvento FROM evento e, porta po 
        WHERE (po.data=CURRENT_DATE) AND e.codigo = po.codEvento";
            $rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);


?>

<html>
    <body>
        <select>
            <?php
            while($row_rsBusca = mysqli_fetch_assoc($rsBusca)){    ?>
            <option value=" {{old('setor')}}">
                
            <?php
                echo ($row_rsBusca["nomeEvento"]);
            } ?>
            </option>
        </select>
    
    
    </body>


</html>
            
