angular.module('mainCtrl', [])

.controller('mainController', function($scope, $http, Product) {

        $scope.loading = true;

    Product.get()
        .success(function(data) {
            $scope.products = data;
            $scope.loading = false;
        });

    });
    
    wciecia zdupczone :D
    
    Musimy zmienic troche sposób w jaki to robisz. Wrzuć projekt na bitbucketa i zrob init commit na branchu develop.
    Przed kolejnymi zmianami stworzysz nowego brancha od developa na ktory wypchniesz zmiany.
    Pozniej zrobisz pull requesta i dasz mi do code review. BO tutaj nawet komentarzy nie da sie pisac.


