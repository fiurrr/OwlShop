var product = angular.module('productCtrl', []);

product.controller('admProductList', ['$scope', 'Product', '$location', function($scope, Product, $location) {
    if($scope.currentUser == null) $location.path('/');

    Product.get(-1).success(function(data) {
        $scope.products = data;
    });

    $scope.deleteProduct = function(id) {
        // Sprawdzenie czy jest autoryzacja
        Product.delete(id).success(function(data){
            $scope.messageSet('usunięto');
            console.log(data);

            Product.get().success(function(data) {
                $scope.products = data;
            });
        });
    }
}]);

product.controller('admProductEdit', ['$scope', 'Product', '$location', '$routeParams', function($scope, Product, $location, $routeParams) {
    if ($scope.currentUser == null) $location.path('/');

    Product.product($routeParams.id).success(function(data) {
       $scope.product = data;
    });

    $scope.productUpdate = function () {
        Product.update($routeParams.id, $scope.product).success(function(data) {
           console.log(data);
            $scope.messageSet('updated');
        });
    }
}]);

product.controller('admProductAdd', ['$scope', 'Product', '$location', function($scope, Product, $location) {
    $scope.productAdd = function() {
        Product.add($scope.product).success(function(data) {
            console.log(data);
        });
    };
}]);