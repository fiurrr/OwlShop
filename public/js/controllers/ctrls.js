var productCtrls = angular.module('productCtrls', []);

productCtrls.controller('productListCtrl', function ($scope, Product) {
    Product.get().success(function(data) {
            $scope.products = data;
        });

    $(".icons .search").slideDown();

});

productCtrls.controller('productDetailCtrl', function ($scope, $routeParams, Product) {
    Product.get().success(function(data) {
        for (i = 0; i < data.length ; i++) {
            if (data[i].id == $routeParams.productId)
            {
                $scope.name = data[i].name;
                $scope.price = data[i].price;
                $scope.desc = data[i].desc;
                $scope.photo = data[i].photo;
            }
        }
    });

    $scope.setPhoto = function(photoUrl) {
        $scope.photo = photoUrl;
    };

    $(".icons .search").slideUp();

    $scope.productId = $routeParams.productId;
});