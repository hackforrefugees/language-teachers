'use strict';

angular.module('sweTea.form-service', ['sweTea.form-functions'])
    .factory('FormService', function() {

        function mapModel(target, source) {
            if(angular.isObject(target.model)) {
                angular.forEach(source.model, function (value, key) {
                    target.model[key] = value;
                });
            } else {
                target.model = source.model;
            }
        }

        function createLayoutClasses(form) {
            var layoutClasses = "";
            angular.forEach(form, function (field, index) {
                if(angular.isObject(field)) {
                    if(field.nextLine === true){
                        layoutClasses += 'clear-'+(index+1)+' ';
                    }
                    if(field.align){
                        layoutClasses += 'align-'+(field.align)+'-'+(index+1)+' ';
                    }
                }
            });
            return layoutClasses;
        }

        function setReferenceOfObjectIn(target, key, sourceItem) {
            if(angular.isDefined(target) && angular.toJson(target[key]) == angular.toJson(sourceItem)) {
                target[key] = sourceItem;
            }
        }

        function fieldIsDate(field) {
            return angular.isObject(field) && (field.type == 'date' || field.type == 'angular-ui-date');
        }

        function setDateObjects(model, form, schema) {
            function applyDateObjects() {
                angular.forEach(model, function (value, key) {
                    var field;
                    for(var index = 0; index < form.length; index++) {
                        var formField = form[index];
                        if(angular.isObject(formField) && formField.key == key) {
                            field = formField;
                            break;
                        }
                    }
                    if(fieldIsDate(field) || fieldIsDate(schema[key]) && !(value instanceof Date)) {
                        model[key] = new Date(value);
                    }
                });
            }
            if(model.$promise) {
                model.$promise.then(applyDateObjects);
            } else {
                applyDateObjects();
            }
        }

        function getFormFieldObject(form, key) {
            for(var index = 0; index < form.length; index++) {
                var field = form[index];
                if(angular.isObject(field) && field.key == key) {
                    return field;
                }
            }
            return undefined;
        }

        function applyFormConditions(form) {
            for(var index = form.length - 1; index >= 0; index--) {
                var field = form[index];
                if(angular.isObject(field) && angular.isString(field.condition) &&
                    field.condition.indexOf('form.') === 0) {
                    var dependsOnFieldKey = field.condition.replace(/^form\.(.+)$/g, '$1');
                    var dependsOnField = getFormFieldObject(form, dependsOnFieldKey);
                    if(angular.isUndefined(dependsOnField)) {
                        form.splice(index, 1);
                    } else {
                        delete field.condition;
                    }
                }
            }
        }

        return {
            mapSchema: function(responseData, target) {
                target.schema = responseData.schema;
                target.form = responseData.form;
                //applyFormConditions(target.form);
                mapModel(target, responseData);
                setDateObjects(target.model, target.form, target.schema);

                target.formClasses = createLayoutClasses(responseData.form);
            },
            mapSelect: function mapSelect(data, form, model, dataKey) {
                angular.forEach(form, function mapField(field) {
                    if(angular.isObject(field)) {
                        if(field.dataKey == dataKey) {
                            if(!angular.isArray(field.titleMap)) {
                                field.titleMap = [];
                            }
                            field.titleMap.splice(0,field.titleMap.length);
                            angular.forEach(data, function (item) {
                                var name = angular.isObject(item) ? item.name : item;
                                field.titleMap.push({
                                    value: item,
                                    name: name
                                });

                                setReferenceOfObjectIn(model, field.key, item);
                            });
                        } else if(angular.isArray(field.items)) {
                            mapSelect(data, field.items, model[field.key], dataKey);
                        }
                    }

                });
            }
        };
    });