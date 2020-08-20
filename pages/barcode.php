<?php
/*
****************************************************
*	Rotina para gerar códigos de barra padrão 2of5 .
*	Basta chamar a função fbarcode(valor da barra).
*****************************************************
*/	
 
$valor = isset($_POST["valor"]) ? $_POST["valor"] : ""; // Valor Inicial

function fbarcode($valor) {

	$fino = 1 ;
	$largo = 3 ;
	$altura = 40 ;

	$barcodes[0] = "00110" ;
	$barcodes[1] = "10001" ;
	$barcodes[2] = "01001" ;
	$barcodes[3] = "11000" ;
	$barcodes[4] = "00101" ;
	$barcodes[5] = "10100" ;
	$barcodes[6] = "01100" ;
	$barcodes[7] = "00011" ;
	$barcodes[8] = "10010" ;
	$barcodes[9] = "01010" ;
	
	for($f1=9;$f1>=0;$f1--){ 
    	for($f2=9;$f2>=0;$f2--){  
      		$f = ($f1 * 10) + $f2 ;
 			$texto = "" ;
      		for($i=1;$i<6;$i++){ 
        		$texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      		}
      		$barcodes[$f] = $texto;
    	}
	}


	//Desenho da barra
	//Guarda inicial
	echo("<img src=\"../images/p.gif\" width=\"" . $fino . "\" height=\"" . $altura . "\" border=\"0\"><img " .
	          "src=\"../images/b.gif\" width=\"" . $fino . "\" height=\"" . $altura . "\" border=\"0\"><img " .
		      "src=\"../images/p.gif\" width=\"" . $fino . "\" height=\"" . $altura . "\" border=\"0\"><img " .
              "src=\"../images/b.gif\" width=\"" . $fino . "\" height=\"" . $altura . "\" border=\"0\"><img ");

	$texto = $valor ;
	if(bcmod(strlen($texto),2) <> 0){
		$texto = "0" . $texto;
	}

	// Draw dos dados
	while (strlen($texto) > 0) {
  		$i = round(esquerda($texto,2));
  		$texto = direita($texto,strlen($texto)-2);
  		$f = $barcodes[$i];
  		for($i=1;$i<11;$i+=2){
    		if (substr($f,($i-1),1) == "0") {
      			$f1 = $fino ;
    		}else{
      			$f1 = $largo ;
    		}

			echo("src=\"../images/p.gif\" width=\"" . $f1 . "\" height=\"" . $altura. "\" border=\"0\"><img ");

		    if (substr($f,$i,1) == "0") {
      			$f2 = $fino ;
    		}else{
      			$f2 = $largo ;
		    }

			echo("src=\"../images/b.gif\" width=\"" . $f2. "\" height=\"" . $altura . "\" border=\"0\"><img ");

  		}
	}

	// Draw guarda final
	echo("src=\"../images/p.gif\" width=\"" . $largo . "\" height=\"" . $altura . "\" border=\"0\"><img " .
    	 "src=\"../images/b.gif\" width=\"" . $fino  . "\" height=\"" . $altura . "\" border=\"0\"><img " .
     	 "src=\"../images/p.gif\" width=\"" . 1 . "\" height=\"" . $altura . "\" border=\"0\">");

} //Fim da função

function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

function vbarcode($valor) {
	$fino = 1 ;
	$largo = 3 ;
	$altura = 40 ;

	$barcodes[0] = "00110" ;
	$barcodes[1] = "10001" ;
	$barcodes[2] = "01001" ;
	$barcodes[3] = "11000" ;
	$barcodes[4] = "00101" ;
	$barcodes[5] = "10100" ;
	$barcodes[6] = "01100" ;
	$barcodes[7] = "00011" ;
	$barcodes[8] = "10010" ;
	$barcodes[9] = "01010" ;
	
	for($f1=9;$f1>=0;$f1--){ 
    	for($f2=9;$f2>=0;$f2--){  
      		$f = ($f1 * 10) + $f2 ;
 			$texto = "" ;
      		for($i=1;$i<6;$i++){ 
        		$texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      		}
      		$barcodes[$f] = $texto;
    	}
	}


	//Desenho da barra
	//Guarda inicial
	echo("<table border='0' align='left' cellpadding='0' cellspacing='0' style='padding:0;border:0;margin:0'><tr><td><img src=\"imagens/p.gif\" width=\"" . $altura . "\" height=\"" . $fino . "\" border=\"0\"></td></tr><tr><td><img " .
	      "src=\"../images/b.gif\" width=\"" . $altura . "\" height=\"" . $fino . "\" border=\"0\"></td></tr><tr><td><img " .
		  "src=\"../images/p.gif\" width=\"" . $altura . "\" height=\"" . $fino . "\" border=\"0\"></td></tr><tr><td><img " .
          "src=\"../images/b.gif\" width=\"" . $altura . "\" height=\"" . $fino . "\" border=\"0\"></td></tr><tr><td><img ");

	$texto = $valor ;
	if(bcmod(strlen($texto),2) <> 0){
		$texto = "0" . $texto;
	}

	// Draw dos dados
	while (strlen($texto) > 0) {
  		$i = round(esquerda($texto,2));
  		$texto = direita($texto,strlen($texto)-2);
  		$f = $barcodes[$i];
  		for($i=1;$i<11;$i+=2){
    		if (substr($f,($i-1),1) == "0") {
      			$f1 = $fino ;
    		}else{
      			$f1 = $largo ;
    		}

			echo("src=\"../images/p.gif\" width=\"" . $altura . "\" height=\"" . $f1. "\" border=\"0\"></td></tr><tr><td><img ");

		    if (substr($f,$i,1) == "0") {
      			$f2 = $fino ;
    		}else{
      			$f2 = $largo ;
		    }

			echo("src=\"../images/b.gif\" width=\"" . $altura. "\" height=\"" . $f2 . "\" border=\"0\"></td></tr><tr><td><img ");

  		}
	}

	// Draw guarda final
	echo("src=\"../images/p.gif\" width=\"" . $altura . "\" height=\"" . $largo . "\" border=\"0\"></td></tr><tr><td><img " .
    	 "src=\"../images/b.gif\" width=\"" . $altura  . "\" height=\"" . $fino . "\" border=\"0\"></td></tr><tr><td><img " .
     	 "src=\"../images/p.gif\" width=\"" . $altura . "\" height=\"" . 1 . "\" border=\"0\"></td></tr><tr><td></table>");

} //Fim da função
?>