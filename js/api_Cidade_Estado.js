// apiEstadosCidades.js

$(document).ready(function() {
    // Carrega os estados
    $.getJSON("https://servicodados.ibge.gov.br/api/v1/localidades/estados", function(estados) {
        estados.sort((a, b) => a.nome.localeCompare(b.nome)); // Ordena alfabeticamente
        $.each(estados, function(key, estado) {
            $('#estado').append('<option value="' + estado.nome + '" data-id="' + estado.id + '">' + estado.nome + '</option>');
        });
    });

    // Quando um estado é selecionado, carrega as cidades
    $('#estado').change(function() {
        var estadoId = $('#estado option:selected').data('id'); // Obtém o ID do estado do data attribute
        $('#cidade').html('<option value="">Selecione a cidade</option>'); // Limpa o campo cidade
        if (estadoId) {
            $.getJSON("https://servicodados.ibge.gov.br/api/v1/localidades/estados/" + estadoId + "/municipios", function(cidades) {
                cidades.sort((a, b) => a.nome.localeCompare(b.nome)); // Ordena alfabeticamente
                $.each(cidades, function(key, cidade) {
                    $('#cidade').append('<option value="' + cidade.nome + '">' + cidade.nome + '</option>'); // Usa nome como value
                });
            });
        }
    });
});