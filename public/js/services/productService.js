var productService = angular.module('productService', []);

productService.factory('Product', function($http) {
        return {
            get : function() {
                return $http.get('/shop/api/products')
            },
            delete : function(id) {
                return $http.delete('/shop/api/products/' + id);
            }
        }
    });