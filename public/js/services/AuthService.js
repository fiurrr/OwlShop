var AuthService = angular.module('AuthService', []);

AuthService.factory('Auth', function($http) {
    return {
        login : function(data) {
            return $http.post('/shop/api/admin/auth', data)
        },
        logout : function() {
            return $http.get('/shop/api/admin/logout');
        }
    }
});

AuthService.factory('Session', function() {
   return {
       get : function(key) {
           return sessionStorage.getItem(key);
       },
       set : function(key, value) {
           return sessionStorage.setItem(key, value);
       },
       unset : function(key) {
           return sessionStorage.removeItem(key);
       }
   }
});