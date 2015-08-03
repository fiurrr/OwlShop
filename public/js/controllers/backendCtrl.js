backend.controller('mainCtrl', ['$scope', 'Auth', '$location', 'Session', function($scope, Auth, $location, Session) {
    $scope.currentUser = Session.get('name');

    if (!$scope.currentUser == '') $location.path('/dashboard');
    if (Session.get('name') == null) $location.path('/');


   $scope.logoutShow = function() { $(".top .logout").slideDown(); };
   $scope.logoutHide = function() { $(".top .logout").slideUp(); };

    $scope.loginUser = function(credentials) {
        Auth.login(credentials).success(function(data) {
           if(data.error == null) {

               Session.set('token', data.token);
               Session.set('id', data.id);
               Session.set('name', data.name);

               $location.path('/dashboard');

           } else {
               console.log(data);
           }
        });
    }

    $scope.logout = function() {
        Auth.logout().success(function(data) {
            Session.unset('token');
            Session.unset('id');
            Session.unset('name');
            $location.path('/');
        });
    }

}]);