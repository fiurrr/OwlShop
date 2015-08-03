angular.module('mainCtrl', [])

.controller('mainController', function($scope, $http, Product) {

        $scope.loading = true;

    Product.get()
        .success(function(data) {
            $scope.products = data;
            $scope.loading = false;
        });

    });


