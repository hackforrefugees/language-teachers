'use strict';

angular.module('sweTea.authentication-service', ['ngStorage'])
    .factory('AuthenticationService', function ($sessionStorage, $rootScope) {

        var $storage = $sessionStorage.$default({
            authToken: ''
        });

        var dashBoardScripts = [
            {
                src: 'js/dashboard-layout/jquery-ui-1.10.4.min.js'
            },
            {
                src: 'js/dashboard-layout/jquery-ui-1.9.2.custom.min.js'
            },
            {
                src: 'js/dashboard-layout/bootstrap.min.js'
            },
            {
                src: 'js/dashboard-layout/jquery.scrollTo.min.js'
            },
            {
                src: 'js/dashboard-layout/jquery.nicescroll.js'
            },
            {
                src: 'assets/jquery-knob/js/jquery.knob.js'
            },
            {
                src: 'js/dashboard-layout/jquery.sparkline.js'
            },
            {
                src: 'assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js'
            },
            {
                src: 'js/dashboard-layout/owl.carousel.js'
            },
            {
                src: 'js/dashboard-layout/fullcalendar.min.js'
            },
            {
                src: 'assets/fullcalendar/fullcalendar/fullcalendar.js'
            },
            {
                src: 'js/dashboard-layout/calendar-custom.js'
            },
            {
                src: 'js/dashboard-layout/jquery.rateit.min.js'
            },
            {
                src: 'js/dashboard-layout/jquery.customSelect.min.js'
            },
            {
                src: 'assets/chart-master/Chart.js'
            },
            {
                src: 'js/dashboard-layout/scripts.js'
            },
            {
                src: 'js/dashboard-layout/sparkline-chart.js'
            },
            {
                src: 'js/dashboard-layout/easy-pie-chart.js'
            },
            {
                src: 'js/dashboard-layout/jquery-jvectormap-1.2.2.min.js'
            },
            {
                src: 'js/dashboard-layout/jquery-jvectormap-world-mill-en.js'
            },
            {
                src: 'js/dashboard-layout/xcharts.min.js'
            },
            {
                src: 'js/dashboard-layout/jquery.autosize.min.js'
            },
            {
                src: 'js/dashboard-layout/jquery.placeholder.min.js'
            },
            {
                src: 'js/dashboard-layout/gdp-data.js'
            },
            {
                src: 'js/dashboard-layout/morris.min.js'
            },
            {
                src: 'js/dashboard-layout/sparklines.js'
            },
            {
                src: 'js/dashboard-layout/charts.js'
            },
            {
                src: 'js/dashboard-layout/jquery.slimscroll.min.js'
            }
        ];

        var dashBoardHeadScripts = [
            {
                src: 'js/dashboard-layout/lte-ie7.js'
            }
        ];

        var dashBoardStyleSheets = [
            {
                href: 'css/dashboard/bootstrap-theme.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/elegant-icons-style.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'assets/fullcalendar/fullcalendar/fullcalendar.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css',
                type: 'text/css',
                media: 'screen'
            },
            {
                href: 'css/dashboard/owl.carousel.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/jquery-jvectormap-1.2.2.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/fullcalendar.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/widgets.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/style.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/style-responsive.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/xcharts.min.css',
                type: 'text/css',
                media: 'all'
            },
            {
                href: 'css/dashboard/jquery-ui-1.10.4.min.css',
                type: 'text/css',
                media: 'all'
            }
        ];

        var landingScripts = [
            {
                src: 'lib/sedulous/jquery.sticky.js'
            }
        ];

        var landingHeadScripts = [];

        var landingStyleSheets = [
            {
                href: 'css/style.css',
                type: 'text/css',
                media: 'all'
            }
        ];

        return {
            getAuthToken: function () {
                return $storage.authToken;
            },
            setAuthToken: function (value, temporary) {
                $storage.authToken = value;
                $storage.temporaryLogin = !!temporary;
            },
            isLoggedIn: function () {
                return $storage.authToken.length > 0 && !$storage.temporaryLogin;
            },
            hasTokenOnHold: function () {
                return !!$storage._authTokenHold;
            },
            logout: function () {
                $storage.authToken = '';
                $rootScope.scripts = landingScripts;
                $rootScope.headScripts = landingHeadScripts;
                $rootScope.styleSheets = landingStyleSheets;
            },
            setScripts: function () {
                if (this.isLoggedIn()) {
                    $rootScope.scripts = dashBoardScripts;
                    $rootScope.headScripts = dashBoardHeadScripts;
                    $rootScope.styleSheets = dashBoardStyleSheets;
                } else {
                    $rootScope.scripts = landingScripts;
                    $rootScope.headScripts = landingHeadScripts;
                    $rootScope.styleSheets = landingStyleSheets;
                }
            }
        }
    });

