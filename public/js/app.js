var productApp = angular.module('productApp', [
    'ngRoute',
    'productCtrls',
    'productService'
]);


productApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/', {
            templateUrl: '/shop/public/html/main-page.html',
            controller: 'productListCtrl'
        }).
        when('/product/:productId', {
            templateUrl: '/shop/public/html/single-product.html',
            controller: 'productDetailCtrl'
        }).
        otherwise({
            redirectTo: '/'
        });
    }]);


