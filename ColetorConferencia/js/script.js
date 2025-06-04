/* INSERE PRODUTOS */
$('#form1').submit(function(e){
  e.preventDefault();    /*Interronpendo a atualização automatica da pagina*/ 

    var d_orcamento = $('#orcamento').val();
    var d_produto = $('#produto').val();

    let resultados = document.getElementById('resultados');

    console.log(d_orcamento, d_produto);

    $.ajax({
        url: 'inserir.php',
        method: 'POST',
        data: {orcamento: d_orcamento, produto: d_produto},
        /* dataType: 'json' */
    }).done(function(result){
      $('#produto').val('').focus();
        console.log(result);
        
        resultados.innerHTML = result; 
    });
});


/* INSERE SERIES */
$('#form2').submit(function(e){
  e.preventDefault();    /*Interronpendo a atualização automatica da pagina*/ 

    var d_serie = $('#serie').val();
    var d_produto = $('#produto').val();
    var d_orcamento = $('#orcamento').val();

    let resultados = document.getElementById('resultados');

    console.log(d_serie, d_produto, d_orcamento);

    $.ajax({
        url: 'inserirserie.php',
        method: 'POST',
        data: {serie: d_serie, produto: d_produto, orcamento: d_orcamento},
        /* dataType: 'json' */
    }).done(function(result2){
      $('#serie').val('').focus();
        console.log(result2);
        
        resultados.innerHTML = result2;
        /* getResultados(); */ 
    });
});

/* $('#produto').each (function(){
  this.reset(). focus();
}); */

$('#serie').each (function(){
  this.reset().focus();
});


$('#form3').submit(function(){
  e.preventDefault();   /*Interronpendo a atualização automatica da pagina*/ 

    var d_orcamento = $('#orcamento').val();

    let resultados = document.getElementById('resultados');

    console.log(d_orcamento);

    $.ajax({
        url: 'atualStatus.php',
        method: 'POST',
        data: {orcamento: d_orcamento},
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