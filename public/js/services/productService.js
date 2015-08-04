var productService = angular.module('productService', []);

productService.factory('Product', function($http) {
        return {
            get : function(id) {
                return $http.get('/shop/api/products')
            },
            delete : function(id) {
                return $http.delete('/shop/api/products/' + id);
            },
            product : function(id) {
                return $http.get('/shop/api/products/get/' + id)
            },
            update : function(id, data) {
                return $http.post('/shop/api/products/update/' + id, data)
            },
            add : function(data) {
                return $http.post('/shop/api/products/add', data);
            }
        }
    });