(function () {
    var as = angular.module('myApp.services', []);

    as.service('i18n', function () {
        var self = this;
        this.setLanguage = function (language) {
            $.i18n.properties({
                name: 'messages',
                path: 'i18n/',
                mode: 'map',
                language: language,
                callback: function () {
                    self.language = language;
                }
            });
        };
        this.setLanguage('en');
    });

    as.service('base64', function () {
        var keyStr = "ABCDEFGHIJKLMNOP" +
            "QRSTUVWXYZabcdef" +
            "ghijklmnopqrstuv" +
            "wxyz0123456789+/" +
            "=";
        this.encode = function (input) {
            var output = "",
                chr1, chr2, chr3 = "",
                enc1, enc2, enc3, enc4 = "",
                i = 0;

            while (i < input.length) {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }

                output = output +
                    keyStr.charAt(enc1) +
                    keyStr.charAt(enc2) +
                    keyStr.charAt(enc3) +
                    keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            }

            return output;
        };

        this.decode = function (input) {
            var output = "",
                chr1, chr2, chr3 = "",
                enc1, enc2, enc3, enc4 = "",
                i = 0;

            // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

            while (i < input.length) {
                enc1 = keyStr.indexOf(input.charAt(i++));
                enc2 = keyStr.indexOf(input.charAt(i++));
                enc3 = keyStr.indexOf(input.charAt(i++));
                enc4 = keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }

                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            }
        };
    });

    as.factory('dataservice', function ($rootScope, $http, $location) {
        $rootScope.appUrl = "http://language.teacher.backend.se";
        return {
            getLanguages: function () {
                return $http({
                    method: 'GET',
                    url: $rootScope.appUrl+'/languages'
                }).then(function (ref) {
                    var languages = [];
                    angular.forEach(ref.data, function (value, key) {
                        languages.push({value: key, name: value});
                    });
                    return languages;
                });
            },
            login: function (email, password, rememberMe) {
                $http($rootScope.appUrl+'/user/login', {email: email, password: password, rememberMe: rememberMe})
                    .then(function (data, status, headers) {
                        $location.path('/');
                    });
            },
            profile: function () {
                return $http({
                    method: 'GET',
                    url: $rootScope.appUrl+'/user'
                }).then(function (ref) {
                    return ref.data;
                });
            },

            addUser: function (form) {
                console.log(form);

                var data = {
                    email: form.email,
                    userType: form.userType.key,
                    region: form.region,
                    contactName: form.contactName,
                    phone: form.phone,
                    password: form.password,
                    confirmPassword: form.confirmPassword,
                    profilePicturePath: form.profilePicturePath,
                    securityQuestionId: "1",
                    securityQuestionAnswer: form.securityQuestionAnswer,
                    contactPersonName: form.contactPersonName,
                    contactPersonEmail: form.contactPersonEmail,
                    contactPersonPhone: form.contactPersonPhone,
                    organisationDescription: form.organisationDescription,
                    organisationWebsite: form.organisationWebsite,
                    languages: form.languages,
                    nativeLanguage: form.native
                };

                console.log(data);

                $http.post($rootScope.appUrl+'/user/register', data)
                    .then(function (ref) {
                        console.log("data");
                        console.log(ref.data);
                    });
            }
        }

    });

}());
