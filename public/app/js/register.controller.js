angular.module('myApp').controller('RegisterCtrl', function($scope, $http, languages, dataservice){
    var vm = $scope;
    vm.user = [];
    vm.user.email = undefined;
    vm.user.password = undefined;
    vm.user.type = undefined;
    vm.user.phone = undefined;

    vm.types = [
        {
            name: "Organisation",
            key: "organisation"
        },
        {
            name: "Volunteer",
            key: "volunteer"
        },
        {
            name: "Student",
            key: "student"
        }
    ];

    vm.languages =  languages;
    vm.nativeLanguages = languages;
    vm.user.languages = [];

    vm.toggleLang = function(lang){
        var index = vm.user.languages.indexOf(lang);

        if(index > -1) vm.user.languages.splice(index, 1);
        else vm.user.languages.push(lang);

        console.log(vm.user.languages);
    };

    vm.submitForm = function(){
        dataservice.addUser(vm.user);
    };

    vm.isStudentOrVolunteer = function(type){
        return type == "Volunteer" || type == "Student";
    };


});