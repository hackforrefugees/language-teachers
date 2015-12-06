(function() {
    var as = angular.module('myApp.controllers', []);
    as.controller('AppCtrl', function($scope, $rootScope, $http, i18n, $location) {
        $scope.language = function() {
            return i18n.language;
        };
        $scope.setLanguage = function(lang) {
            i18n.setLanguage(lang);
        };
        $scope.activeWhen = function(value) {
            return value ? 'active' : '';
        };

        $scope.path = function() {
            return $location.url();
        };

//        $scope.login = function() {
//            $scope.$emit('event:loginRequest', $scope.username, $scope.password);
//            //$location.path('/login');
//        };

//        $scope.logout = function() {
//            $rootScope.user = null;
//            $scope.username = $scope.password = null;
//            $scope.$emit('event:logoutRequest');
//            $location.url('/');
//        };

        $rootScope.appUrl = "http://language.teacher.se";

    });

    as.controller('AlbumListCtrl', function($scope, $rootScope, $http, $location) {
        var load = function() {
            console.log('call load()...');
            console.log($rootScope.appUrl + '/albums');
            $http.get($rootScope.appUrl + '/albums')
                    .success(function(data, status, headers, config) {
                        $scope.albums = data.data;
                        angular.copy($scope.albums, $scope.copy);
                    });
        }

        load();

        $scope.addAlbum = function() {
            console.log('call addAlbum');
            $location.path("/new");
        }

        $scope.editAlbum = function(index) {
            console.log('call editAlbum');
            $location.path('/edit/' + $scope.albums[index].id);
        }

        $scope.delAlbum = function(index) {
            console.log('call delAlbum');
            var todel = $scope.albums[index];
            $http
                    .delete($rootScope.appUrl + '/albums/' + todel.id)
                    .success(function(data, status, headers, config) {
                        load();
                    }).error(function(data, status, headers, config) {
            });
        }

    });

    as.controller('NewAlbumCtrl', function($scope, $rootScope, $http, $location) {

        $scope.album = {};
        $scope.saveAlbum = function() {
            console.log('call saveAlbum');
            $http.post($rootScope.appUrl + '/albums', $scope.album)
                    .success(function(data, status, headers, config) {
                        console.log('success...');
                        console.log($location.path('/albums'));
                        $location.path('/albums');
                    })
                    .error(function(data, status, headers, config) {
                         console.log('error...');
                    });
        }
    });

    as.controller('EditAlbumCtrl', function($scope, $rootScope, $http, $routeParams, $location) {
        $scope.album = {};
        
        var load = function() {
            console.log('call load()...');
            $http.get($rootScope.appUrl + '/albums/' + $routeParams['id'])
                    .success(function(data, status, headers, config) {
                        $scope.album = data.data;
                        angular.copy($scope.album, $scope.copy);
                    });
        };

        load();  

        $scope.updateAlbum = function() {
            console.log('call updateAlbum');

            $http.put($rootScope.appUrl + '/albums/' + $scope.album.id, $scope.album)
                    .success(function(data, status, headers, config) {
                        $location.path('/albums');
                    })
                    .error(function(data, status, headers, config) {
                    });
		}
    });
    
    as.controller('AlbumCtrl', function($scope, $rootScope, $http, $routeParams, $location) {
        $scope.album = {};
        
        var load = function() {
            console.log('call load()...');
            $http.get($rootScope.appUrl + '/albums/' + $routeParams['id'])
                    .success(function(data, status, headers, config) {
                        $scope.album = data;
                        angular.copy($scope.album, $scope.copy);
                    });
        };

        load();  
    });

    as.controller('LoginCtrl', function($scope, $routeParams, $location){
        var vm = $scope;
        vm.username = undefined;
        vm.password = undefined;
        vm.login = function(){
            console.log("login");
        }

    });

    as.controller('RegisterCtrl', function($scope){
        var vm = $scope;
        vm.user = [];
        vm.user.email = undefined;
        vm.user.password = undefined;
        vm.user.type = undefined;
        vm.user.phone = undefined;

        vm.types = [
            {name: "Organization"},
            {name: "Teacher"},
            {name: "Student"}
        ];

        vm.languages =  [
            {name: "Arabic"},
            {name: "English"},
            {name: "Swedish"}
        ];

        vm.nativeLanguages = [
            {name: "Arabic"},
            {name: "English"},
            {name: "Swedish"}
        ];

        vm.user.languages = [];

        vm.toggleLang = function(lang){
            var index = vm.user.languages.indexOf(lang);

            if(index > -1) vm.user.languages.splice(index, 1);
            else vm.user.languages.push(lang);

            console.log(vm.user.languages);
        };

        vm.submitForm = function(){
            console.log(vm.user);
        }

    });

}());