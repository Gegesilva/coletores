/* INSERE PRODUTOS */
$('#form1').submit(function(e){
  e.preventDefault();    /*Interronpendo a atualização automatica da pagina*/ 

    var d_pedido = $('#pedido').val();
    var d_produto = $('#produto').val();

    let resultados = document.getElementById('resultados');

    console.log(d_pedido, d_produto);

    $.ajax({
        url: 'http://databitbh.com:51230/coletores/coletorretornoexp/inserir.php',
        method: 'POST',
        data: {pedido: d_pedido, produto: d_produto},
        /* dataType: 'json' */
    }).done(function(result){
      $('#produto').val('').focus();
        console.log(result);
        
        resultados.innerHTML = result; 
    });
});


$('#serie').each (function(){
  this.reset().focus();
});


$('#form3').submit(function(){
  e.preventDefault();   /*Interronpendo a atualização automatica da pagina*/ 

    var d_pedido = $('#pedido').val();

    let resultados = document.getElementById('resultados');

    console.log(d_pedido);

    $.ajax({
        url: 'http://databitbh.com:51230/coletores/coletorretornoexp/atualStatus.php',
        method: 'POST',
        data: {pedido: d_pedido},
        dataType: 'json'
    }).done(function(result3){
        console.log(result3);
        resultados.innerHTML = result3;
        /* getResultados(); */ 
    });
});


  function goBack() {
    window.history.back()
}
  
function reload(){
  Location.reload();
}