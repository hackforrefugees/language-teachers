'use strict';

angular.module('sweTea.authorization-service', ['ngStorage', 'mm.acl'])
    .factory('AuthorizationService', function ($sessionStorage, AclService) {
        var $storage = $sessionStorage.$default({
            admin: false,
            student: false,
            volunteer: false,
            organisation: false,
            aclRole: "guest"
        });

        return {
            isAdmin: function () {
                return $storage.admin = true;
            },
            isStudent: function () {
                return $storage.student = true;
            },
            isVolunteer: function () {
                return $storage.volunteer = true;
            },
            isOrganisation: function () {
                return $storage.organisation = true;
            },
            setAdmin: function (value) {
                $storage.admin = !!value;
                $storage.student = !value;
                $storage.volunteer = !value;
                $storage.organisation = !value;
                AclService.attachRole('admin');
                AclService.detachRole('guest');
                AclService.detachRole('student');
                AclService.detachRole('volunteer');
                AclService.detachRole('organisation');
                $sessionStorage.aclRole = 'admin';
            },
            setStudent: function (value) {
                $storage.admin = !value;
                $storage.student = !!value;
                $storage.volunteer = !value;
                $storage.organisation = !value;
                AclService.attachRole('student');
                AclService.detachRole('guest');
                AclService.detachRole('admin');
                AclService.detachRole('volunteer');
                AclService.detachRole('organisation');
                $sessionStorage.aclRole = 'student';
            },
            setVolunteer: function (value) {
                $storage.admin = !value;
                $storage.student = !value;
                $storage.volunteer = !!value;
                $storage.organisation = !value;
                AclService.attachRole('volunteer');
                AclService.detachRole('guest');
                AclService.detachRole('admin');
                AclService.detachRole('student');
                AclService.detachRole('organisation');
                $sessionStorage.aclRole = 'volunteer';
            },
            setOrganisation: function (value) {
                $storage.admin = !value;
                $storage.student = !value;
                $storage.volunteer = !value;
                $storage.organisation = !!value;
                AclService.attachRole('organisation');
                AclService.detachRole('guest');
                AclService.detachRole('admin');
                AclService.detachRole('student');
                AclService.detachRole('volunteer');
                $sessionStorage.aclRole = 'organisation';
            },
            getAclRole: function () {
                return $sessionStorage.aclRole;
            },
            logout: function () {
                $storage.admin = false;
                $storage.student = false;
                $storage.volunteer = false;
                $storage.organisation = false;
                AclService.flushRoles();
                $sessionStorage.aclRole = 'guest';
            }
        }
    });