angular.module('myApp').controller('LoginCtrl', function($scope, $http, dataservice){
        var vm = $scope;
        vm.login = function(loginForm){
            console.log("logging in");
            dataservice.login(loginForm.email, loginForm.password, loginForm.rememberMe);
        };

    });