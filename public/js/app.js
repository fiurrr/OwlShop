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
            fajna konwencja jest main.product.list i np main.product.add wtedy, html katalog moozna zmienic nazwe na views albo templates ale to detal
        }).
        when('/product/:productId', {
            templateUrl: '/shop/public/html/single-product.html',
            controller: 'productDetailCtrl'
        }).
        otherwise({
            redirectTo: '/'
        });
    }]);


