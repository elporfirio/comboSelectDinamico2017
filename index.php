<?php
/**
 * Created by PhpStorm.
 * User: elporfirio
 * Date: 06/04/17
 * Time: 8:38 AM
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form>
    <fieldset>
        <legend>Seleccione ubicación</legend>
        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="">- Seleccione un Estado -</option>
        </select>
        <br>
        <label for="municipio">Municipio:</label>
        <select name="municipio" id="municipio">
            <option value="">- primero seleccione un Estado -</option>
        </select>
        <br>
        <label for="localidad">Localidad:</label>
        <select name="localidad" id="localidad">
            <option value="">- primero seleccione un municipio -</option>
        </select>
    </fieldset>
    <hr>
    <div class="log"></div>
</form>

<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>

<script>
    $('select').on('change', function () {
        var current = $(this);

        if (current.attr('id') === 'estado') {
            buscarMunicipios(current.val());
        } else if (current.attr('id') === 'municipio') {
            buscarLocalidades(current.val());
        }
    });

    $(document).ajaxStart(function () {
        $('.log').text('Cargando...');
        $('select').prop('disabled', true);

    }).ajaxComplete(function () {
        $('.log').text('');
        $('select').prop('disabled', false);
    });

    buscarEstados();

    function buscarEstados() {
        $.get('functions/buscarEstados.php')
            .done(function (result) {
                pintarOptions(
                    'estado',
                    $.parseJSON(result),
                    '<option value="" selected disabled>-- Seleccione un Estado --</option>'
                );
            })
            .fail(function (error) {
                console.error(error);
            });
    }

    function buscarMunicipios(estado) {
        $.get('functions/buscarMunicipios.php', {estado: estado})
            .done(function (result) {
                pintarOptions(
                    'municipio',
                    $.parseJSON(result),
                    '<option value="" selected disabled>-- Seleccione un Municipio --</option>'
                );
                pintarOptions(
                    'localidad',
                    [],
                    '<option value="" selected disabled>- primero seleccione un municipio -</option>'
                );
            })
            .fail(function (error) {
                console.error(error);
            });
    }

    function buscarLocalidades(municipio) {
        $.get('functions/buscarLocalidades.php', {municipio: municipio})
            .done(function (result) {
                pintarOptions(
                    'localidad',
                    $.parseJSON(result),
                    '<option value="" selected disabled>-- Seleccione una Localidad --</option>'
                );
            })
            .fail(function (error) {
                console.error(error);
            });
    }

    function pintarOptions(selectId, opciones, opcionDefault) {
        var opcionesHtml = '';
        if (typeof opcionDefault === 'string') {
            opcionesHtml += opcionDefault;
        }
        $.each(opciones, function (ind, val) {
            opcionesHtml += '<option value="' + val.id + '">' + val.nombre + '</option>'
        });
        $('#' + selectId).html(opcionesHtml);
    }
</script>
</body>
</html>
