var product = angular.module('productCtrl', []);

product.controller('admProductList', ['$scope', 'Product', '$location', function($scope, Product, $location) {
    if($scope.currentUser == null) $location.path('/');

    Product.get().success(function(data) {
        $scope.products = data;
    });

    $scope.deleteProduct = function(id) {
        // Sprawdzenie czy jest autoryzacja
        Product.delete(id).success(function(data){
            console.log(data);

            Product.get().success(function(data) {
                $scope.products = data;
            });
        });
    }
}]);