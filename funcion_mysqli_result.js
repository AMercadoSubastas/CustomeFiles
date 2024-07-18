// SEARCH ENTIDADES

$(document).ready(function(){
    $('.search-box-A input[type="text"]').on("keyup input", function(){
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("validando_datos.php", {clienteA: inputVal}).done(function(data){
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Delegación de eventos para manejar clics en los elementos p generados dinámicamente
    $(document).on("click", ".search-box-A .result p", function(){
        let selectedText = $(this).text();
        let cliente = selectedText.split("|");
        $('#A-CUIT').val(cliente[0].trim());
        $('#A-razon-social').val(cliente[1].trim());
        $("#A-search-field").val(selectedText);       
        $(".result").empty();
    });
});


$(document).ready(function(){
    // Evento para el input
    $('.search-box-B input[type="text"]').on("keyup input", function(){
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("validando_datos.php", {clienteB: inputVal}).done(function(data){
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Delegación de eventos para manejar clics en los elementos p generados dinámicamente
    $(document).on("click", ".search-box-B .result p", function(){
        let selectedText = $(this).text();
        let cliente = selectedText.split("|");
        $('#B-CUIT').val(cliente[0].trim());
        $('#B-razon-social').val(cliente[1].trim());
        $("#B-search-field").val(selectedText);       
        $(".result").empty();
    });
});


$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("validando_datos.php", {term: inputVal}).done(function(data){
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    $(document).on("click", ".search-box .result p", function(){
        let selectedText = $(this).text();
        let cliente = selectedText.split("|");
        $('#CUIT').val(cliente[0].trim());
        $('#razon-social').val(cliente[1].trim());
        $("#search-field").val(selectedText);       
        $(".result").empty();
    });
});
