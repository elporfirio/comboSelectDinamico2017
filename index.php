<?php
/**
 * Created by PhpStorm.
 * User: elporfirio
 * Date: 06/04/17
 * Time: 8:38 AM
 */
?>
<!doctype html>
<html lang="es" ng-app="dinamicCombo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body ng-controller="LocationController as locationctrl">

<form>
    <fieldset>
        <legend>Seleccione ubicación</legend>
        <label for="estado">Estado:</label>
        <select name="estado" id="estado" ng-model="locationctrl.estado"
                ng-options="estado.clave_estado as estado.nombre for estado in locationctrl.estadosDisponibles"
                ng-change="locationctrl.buscarMunicipios(locationctrl.estado)"
                ng-disabled="locationctrl.cargando">
            <option value="" selected disabled>- Seleccione un Estado -</option>
        </select>
        <br>
        <label for="municipio">Municipio:</label>
        <select name="municipio" id="municipio" ng-model="locationctrl.municipio"
                ng-options="municipio.clave_municipio as municipio.nombre for municipio in locationctrl.municipiosDisponibles"
                ng-change="locationctrl.buscarLocalidades(locationctrl.municipio)"
                ng-disabled="locationctrl.cargando">
            <option value="" selected disabled>- primero seleccione un Estado -</option>
        </select>
        <br>
        <label for="localidad">Localidad:</label>
        <select name="localidad" id="localidad" ng-model="locationctrl.localidad"
                ng-options="localidad.clave_localidad as localidad.nombre for localidad in locationctrl.localidadesDisponibles"
                ng-disabled="locationctrl.cargando">
            <option value="" selected disabled>- primero seleccione un municipio -</option>
        </select>
    </fieldset>
    <hr>
    <div class="log" ng-show="locationctrl.cargando">
        Cargando...
    </div>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script>
    angular.module('dinamicCombo', [])
        .service('LocationServices', ['$http', function ($http) {
            var service = {
                buscarEstados: buscarEstados,
                buscarMunicipios: buscarMunicipios,
                buscarLocalidades: buscarLocalidades
            };

            return service;

            function buscarEstados() {
                return $http.get('functions/buscarEstados.php')
                    .then(buscarComplete)
                    .catch(buscarError);
            }

            function buscarMunicipios(estado) {
                return $http({
                    url: 'functions/buscarMunicipios.php',
                    method: "GET",
                    params: {estado: estado}
                })
                    .then(buscarComplete)
                    .catch(buscarError);
            }

            function buscarLocalidades(municipio) {
                return $http({
                    url: 'functions/buscarLocalidades.php',
                    method: "GET",
                    params: {municipio: municipio}
                })
                    .then(buscarComplete)
                    .catch(buscarError);
            }

            function buscarComplete(response) {
                return response.data;
            }

            function buscarError(error) {
                console.log(error);
            }
        }])
        .controller('LocationController', ['LocationServices', function (LocationServices) {
            var self = this;

            self.estadosDisponibles = [];
            self.municipiosDisponibles = [];
            self.localidadesDisponibles = [];

            self.estado = '';
            self.municipio = '';
            self.localidad = '';

            self.cargando = false;

            self.init = function () {
                self.cargando = true;
                LocationServices.buscarEstados()
                    .then(function (result) {
                        self.estadosDisponibles = result;
                        self.cargando = false;
                    })
            };

            self.buscarMunicipios = function (estado) {
                self.municipio = '';
                self.cargando = true;
                LocationServices.buscarMunicipios(estado)
                    .then(function (result) {
                        self.municipiosDisponibles = result;
                        self.localidadesDisponibles = [];
                        self.cargando = false;
                    })
            };

            self.buscarLocalidades = function (municipio) {
                self.localidad = '';
                self.cargando = true;
                LocationServices.buscarLocalidades(municipio)
                    .then(function (result) {
                        self.localidadesDisponibles = result;
                        self.cargando = false;
                    })
            };

            self.init();
        }]);
</script>
</body>
</html>
