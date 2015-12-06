(function() {
    var as = angular.module('myApp.directives', []);

    as.directive('msg', function() {
        return {
            restrict: 'EA',
            link: function(scope, element, attrs) {
                var key = attrs.key;
                if (attrs.keyExpr) {
                    scope.$watch(attrs.keyExpr, function(value) {
                        key = value;
                        element.text($.i18n.prop(value));
                    });
                }
                scope.$watch('language()', function(value) {
                    element.text($.i18n.prop(key));
                });
            }
        };
    });

    as.directive('appVersion', ['version', function(version) {
            return function(scope, elm, attrs) {
                elm.text(version);
            };
        }]);

    as.directive('fileread', function(){
        return {
            restrict: 'A',
            scope: {
                fileread: "="
            },
            link: function(scope, element, attrs) {
                element.bind("change", function(changeEvent){
                    var reader = new FileReader();

                    reader.onload = function(loadEvent){
                        scope.$apply(function(){
                            scope.fileread = loadEvent.target.result;
                            console.log(scope.fileread);
                        });
                    };
                    reader.readAsDataURL(changeEvent.target.files[0]);

                })
            }
        }
    })
}());

