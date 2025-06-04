/* INSERE PRODUTOS */
$('#form1').submit(function(e){
  e.preventDefault();    /*Interronpendo a atualização automatica da pagina*/ 

    var d_pedido = $('#pedido').val();

    let resultados = document.getElementById('resultados');

    console.log(d_pedido);

    $.ajax({
        url: 'http://databitbh.com:51230/coletores/coletortriagem/inserir.php',
        method: 'POST',
        data: {pedido: d_pedido},
        /* dataType: 'json' */
    }).done(function(result){
      $('#pedido').val('').focus();
        console.log(result);
        
        resultados.innerHTML = result; 
    });
});



  function goBack() {
    window.history.back()
}
  
function reload(){
  Location.reload();
}