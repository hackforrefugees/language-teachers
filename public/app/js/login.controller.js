(function() {
    var as = angular.module('myApp.controllers');

    as.controller('LoginCtrl', function(loginService){
        var vm = this;
        vm.username = undefined;
        vm.password = undefined;
        vm.login = login();


        /////////////////////////////////////////////////////////////

        function login(username, password){

        }


    });

})