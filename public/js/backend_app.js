var backend = angular.module('backend', [
    'ngRoute',
    'AuthService',
    'productCtrl',
    'productService'
]);

backend.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: '/shop/public/html/admin/login.html',
                controller: 'mainCtrl'
            }).
            when('/dashboard', {
                templateUrl: '/shop/public/html/admin/dashboard.html',
                controller: 'mainCtrl'
            }).
            when('/products', {
                templateUrl: '/shop/public/html/admin/products.html',
                controller: 'admProductList'
            }).
                when('/product/:id', {
                    templateUrl: '/shop/public/html/admin/product_edit.html',
                    controller: 'admProductEdit'
                }).
            when('/categories', {
                templateUrl: '/shop/public/html/admin/categories.html',
                controller: 'categoryCtrl'
            }).
            when('/orders', {
                templateUrl: '/shop/public/html/admin/orders.html',
                controller: 'ordersCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);
