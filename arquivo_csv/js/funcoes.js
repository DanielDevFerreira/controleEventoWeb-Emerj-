
//  FUNÇÃO PARA VERIFICA SE CONTEM SOMENTE NUMEROS E NAO CARACTERES
function verificaNumeros(e) {
    if (document.all) // Internet Explorer
        var tecla = e.keyCode;
    else if(document.layers) // Nestcape
        var tecla = e.which;
    if  (!((tecla == 8) || (tecla > 47 && tecla < 58))) // numeros de 0 a 9
        e.keyCode = 0;
    return true;
}

// FUNÇÃO PARA INSERIR MASCARA EM UM CAMPO DATA
function mascara_data(evento, txtData) {
    if (verificaNumeros(evento)) {
        var data = '';
        data = data + txtData;
        if (data.length == 2) {
            data = data + '/';
            document.getElementById('txtData').value = data;
        }
        if (data.length == 5) {
            data = data + '/';
            document.getElementById('txtData').value = data;
        }
    }
}

// MARCAR TODOS OS CHECKBOX
$('#check_tudo').click(function () {
    // marca ou desmarca os outros checkbox

    $('input:checkbox').not(this).prop('checked', this.checked);

});

// PEGAR O VALOR DO SELECT E COLOCAR NO INPUT HIDDEN
$(function(){
    $('#tamanho').on('change', function(){
        tamanho = $('option').filter(':selected').prop('value');
        $('#hdAcao').not('visible').prop('value',tamanho);
    })
})


// FUNÇÃO PARA PEGAR O VALOR DIGITAR, JOGAR NA QUERY, DEPOIS ENVIAR O RESULTA PARA O HTML
$(function(){
    //Pesquisar sem refresh na página
    $("#pesquisa").keyup(function(){ //keyup - a chave é liberada

        var pesquisa = $(this).val();

        //Verificar se há algo digitado
        if(pesquisa != ''){
            var dados = {
                palavra : pesquisa
            }
            $.post('busca.php', dados, function(retorna){
                //Mostra dentro da ul os resultado obtidos
                $(".resultado").html(retorna);
            });
        }else{
            $(".resultado").html('');
        }
    });
});

$(function(){
    //Pesquisar sem refresh na página
    $("#pesquisa").keyup(function(){ //keyup - a chave é liberada

        var pesquisa = $(this).val();

        //Verificar se há algo digitado
        if(pesquisa != ''){
            var dados = {
                palavra : pesquisa
            }
            $.post('busca2.php', dados, function(retorna){
                //Mostra dentro da ul os resultado obtidos
                $(".resultado").html(retorna);
            });
        }else{
            $(".resultado").html('');
        }
    });
});