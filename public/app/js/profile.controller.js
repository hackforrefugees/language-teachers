angular.module('myApp').controller('ProfileCtrl', function($scope, user){
        var vm = $scope;

        vm.user = user;
    console.log(vm.user);
    });