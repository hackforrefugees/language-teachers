'use strict';

angular.module('sweTea.authorization-service', ['ngStorage', 'mm.acl'])
    .factory('AuthorizationService', function ($sessionStorage, AclService) {
        var $storage = $sessionStorage.$default({
            admin: false,
            student: false,
            volunteer: false,
            organisation: false
        });

        return {
            isAdmin: function(){
                return $storage.admin = true;
            },
            isStudent: function(){
                return $storage.student = true;
            },
            isVolunteer: function(){
                return $storage.volunteer = true;
            },
            isOrganisation: function(){
                return $storage.organisation = true;
            },
            setAdmin: function(value){
                $storage.admin = !!value;
                $storage.student = !value;
                $storage.volunteer = !value;
                $storage.organisation = !value;
                AclService.attachRole('admin');
                AclService.detachRole('guest');
                AclService.detachRole('student');
                AclService.detachRole('volunteer');
                AclService.detachRole('organisation');
            },
            setStudent: function(value){
                $storage.admin = !value;
                $storage.student = !!value;
                $storage.volunteer = !value;
                $storage.organisation = !value;
                AclService.attachRole('student');
                AclService.detachRole('guest');
                AclService.detachRole('admin');
                AclService.detachRole('volunteer');
                AclService.detachRole('organisation');
            },
            setVolunteer: function(value){
                $storage.admin = !value;
                $storage.student = !value;
                $storage.volunteer = !!value;
                $storage.organisation = !value;
                AclService.attachRole('volunteer');
                AclService.detachRole('guest');
                AclService.detachRole('admin');
                AclService.detachRole('student');
                AclService.detachRole('organisation');
            },
            setOrganisation: function(value){
                $storage.admin = !value;
                $storage.student = !value;
                $storage.volunteer = !value;
                $storage.organisation = !!value;
                AclService.attachRole('organisation');
                AclService.detachRole('guest');
                AclService.detachRole('admin');
                AclService.detachRole('student');
                AclService.detachRole('volunteer');
            },
            logout: function(){
                $storage.admin = false;
                $storage.student = false;
                $storage.volunteer = false;
                $storage.organisation = false;
                AclService.flushRoles();
            }
        }
    });