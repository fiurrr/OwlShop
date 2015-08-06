var productCtrls = angular.module('productCtrls', []);
//jeden ctrl na jeden plik i tak ze wszystkim, nawet jak ma 3 linijki
productCtrls.controller('productListCtrl', function ($scope, Product) {
    Product.get().success(function(data) {
            $scope.products = data;
        });

    Pojebales wciecia xD, używasz intelliJ? Możesz użyć alt+shift+L i magicznie zformatuje Ci kodzik :)

    $(".icons .search").slideDown();

    Jedna z ważniejszych rzeczy,  nie używamy jquery w controllerze, poczytaj sobie o dyrektywach i wynie todo dyrektywy.

});

Sposób w jaki to wszystko rejestrujesz trzeba zmienić:
function productListCtrl($scope, Product) {
    Product.get().success(function(data) {
        $scope.products = data;
    });
}
productListCtrl.$inject = ['$scope', 'Product'];
productCtrls.controller('productListCtrl', productListCtrl);

Przy okazji wytumacze Ci dlaczego tak.


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
    
    Teraz wazna kwestia, powinno być wikszoć tego kodu wyniesiona do serwisu:
    przykadowo:
    Product.get($routeParams.productId).then(function(product){
        /*$scope.name = product.name;
        $scope.price = product.price;
        $scope.desc = product.desc;
        $scope.photo = product.photo;*/
        //to moznaby trzymac w $scope.product co daloby : 
        $scope.product = product;
    });
    
    i teraz musimy zmodyfikowac odpowiednio serwis, trzeba użyć promisów serwis - $q (poczytaj)
    ponizej pseudoimplementacja funnkcji get z serwisu:
    function get(){
        var q = $q.defer();
        $http.get(productId) - czy post czy whatever .success(function(data) {
            for (i = 0; i < data.length ; i++) {
                if (data[i].id == productId) {
                    q.resolve(data[i]);
                    break;
                }
            }
        });
        return q.promise;
    }
    
    czyli logika biznesowa jak sie da w serwisie. Wiem ze to powyzej bedzie nie zrozumiale ale nie martw sie.

    $scope.setPhoto = function(photoUrl) { // mozna wyniesc $scope.setPhoto = setPhoto; na gore i na dole zrobic function setPhoto....
        $scope.photo = photoUrl;
    };

    $(".icons .search").slideUp(); //pacze jak to widze :D

    $scope.productId = $routeParams.productId; // na sama góre
});
