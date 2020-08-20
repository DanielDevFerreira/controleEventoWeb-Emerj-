<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-BR'>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf8'>
	</head>

<?php
	require 'PHPMailer/PHPMailerAutoload.php';

header("Content-Type: text/html; charset=utf8", true);

if(isset($_POST['valorselecionado']) && $_POST['valorselecionado'] != "") {

    $valor_query = $_POST['valorselecionado'];

    $valorReal = explode("*",$valor_query);

     $codParticipante = $valorReal[0];
     $nome = $valorReal[1];
     $email = $valorReal[2];
     $senha = $valorReal[3];

     $mensagem =  "  <p>Prezado(a) $nome </p>
					 <p>Para realizar a alteração do seu e-mail e senha do cadastro de participante de eventos EMERJ, acesse o link abaixo:</p> 
					 <p><a href='http://emerj.com.br/evento/participante.php?codParticipante=$codParticipante&codAtivacao=$senha'>http://emerj.com.br/evento/participante.php?codParticipante=$codParticipante&codAtivacao=$senha</a></p>
                     <p>Qualquer dúvida poderá ser tirada nos links <a href='http://www.emerj.tjrj.jus.br/paginas/eventos/duvidaseperguntas.html'>Dúvidas e Perguntas Frequentes</a> e
                     <a href='http://www.emerj.tjrj.jus.br/paginas/eventos/regras_inscricoesonline.html'>Regras e Inscrições On-line.</a></p>
                     
                     <br><br><br>
                     
                     <div id='texto'>
                     
                     <img  src='cid:logo'>
                    
                        <div>Atenciosamente,</div>
                    
                        <div>EMERJ - SITE</div>
                    
                        <div>Departamento de Tecnologia da Informação e Comunicação</div>
                    
                        <div>Escola da Magistratura do Estado do Rio de Janeiro</div>
                    
                        <div>Poder Judiciário do Estado do Rio de Janeiro</div>
                    
                        <div>e-mail: emerjsite@tjrj.jus.br</div>
                    
                    </div>
                    
                    <br>
                    
                    <small>Ato Executivo Conjunto TJ/CGJ nº 4/2004, art. 8º, de 27/01/2004: As comunicações por correio eletrônico entre Serventias, Secretarias de Órgãos Julgadores e demais Órgãos do Poder Judiciário terão o mesmo efeito de entregues pessoalmente</small>";

    $Mailer = new PHPMailer();

    //Define que será usado SMTP
    $Mailer->IsSMTP();

    //Enviar e-mail em HTML
    $Mailer->isHTML(true);

    //Aceitar carasteres especiais
    $Mailer->Charset = 'utf-8';

    //Configurações
    $Mailer->SMTPAuth = true;
    //$Mailer->SMTPSecure = 'ssl';

    //nome do servidor
    $Mailer->Host = 'smtp.hostinger.com.br ';
    //Porta de saida de e-mail
    $Mailer->Port = 587;

    //Dados do e-mail de saida - autenticação
    $Mailer->Username = 'rfreitas@refseg.com.br';
    $Mailer->Password = '163312Ref@';

    //E-mail remetente (deve ser o mesmo de quem fez a autenticação)
    $Mailer->From = 'rfreitas@refseg.com.br';

    //Nome do Remetente
    $Mailer->FromName = 'EMERJ';

    //Assunto da mensagem
    $Mailer->Subject = 'EVENTO: Link para Alterar Dados Cadastrais como Senha e E-mail.';

    //Corpo da Mensagem
    $Mailer->Body = utf8_decode($mensagem);

    //Imagem
    $Mailer->addEmbeddedImage(dirname(__FILE__) . '/emerj_Imagem_email.png','logo');

    //Corpo da mensagem em texto
    $Mailer->AltBody = 'conteudo do E-mail em texto';

    //Destinatario
    $Mailer->AddAddress('danielcosta@tjrj.jus.br');

    //Enviar Cópia de Email
    $Mailer->addCC('danielplay78@hotmail.com');

    if ($Mailer->Send()) {
        echo "<script>alert('E-mail enviado com sucesso');
        location.href = '../pages/consultaParticipante2.php';
        </script>";

    } else {
        echo "<script>alert('Erro no envio do e-mail:' . $Mailer->ErrorInfo . ');
        location.href = '../pages/consultaParticipante2.php';
        </script>";
    }

} else if (isset($_POST['valorselecionado']) && $_POST['valorselecionado'] == ""){
    echo "<script>alert('Erro, Selecione um Participante!')
    
    </script>";
}
	
?>
</html>


