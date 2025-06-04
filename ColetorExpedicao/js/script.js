/* INSERE PRODUTOS */
$('#form1').submit(function(e){
  e.preventDefault();    /*Interronpendo a atualização automatica da pagina*/ 

    var d_codmoto = $('#codmoto').val();
    var d_pedido = $('#pedido').val();
    var d_nome = $('#nome').val();

    let resultados = document.getElementById('resultados');

    console.log(d_pedido, d_codmoto, d_nome);

    $.ajax({
        url: 'http://databitbh.com:51230/coletores/coletorexpedicao/inserir.php',
        method: 'POST',
        data: {pedido: d_pedido, codmoto: d_codmoto, nome: d_nome},
        /* dataType: 'json' */
    }).done(function(result){
      $('#pedido').val('').focus();
        console.log(result);
        
        resultados.innerHTML = result; 
    });
});


$('#form2').submit(function(e){
  e.preventDefault();

  var d_pedido = $('#pedido').val();
  let resultados = document.getElementById('resultados');

  $.ajax({
    url: 'http://databitbh.com:51230/coletores/coletorexpedicao/inserirTrans.php',
    method: 'POST',
    data: {pedido: d_pedido}
  }).done(function(result){
    $('#pedido').val('').focus();
    console.log(result);

    resultados.innerHTML = result;
  })
});



  function goBack() {
    window.history.back()
}
  
function reload(){
  Location.reload();
}