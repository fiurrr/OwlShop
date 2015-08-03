<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Owl Things - thing in beauty</title>

    <script src="/shop/public/js/jquery-1.9.1.js"></script>
    <script src="/shop/public/js/angular.min.js"></script>
    <script src="/shop/public/js/angular-route.min.js"></script>

    <script src="/shop/public/js/services/AuthService.js"></script>
    <script src="/shop/public/js/services/productService.js"></script>
    <script src="/shop/public/js/backend_app.js"></script>
    <script src="/shop/public/js/controllers/backendCtrl.js"></script>
    <script src="/shop/public/js/controllers/product.js"></script>

    <link href="/shop/public/css/backend_style.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Exo+2:400,100' rel='stylesheet' type='text/css'>
</head>
<body ng-app="backend" ng-controller="mainCtrl">
{{username}}
<header class="top" >
    <h1>Owl Things</h1>
    <div class="user" ng-mouseover="logoutShow()" ng-mouseleave="logoutHide()" ng-if="currentUser">
        <img src="/shop/public/img/site/user.png" /> {{currentUser}}
        <div class="logout" style="display:none;"><a ng-click="logout()" href="">Wyloguj</a></div>
    </div>

</header>
<div ng-view></div>
<footer>
    created by owlCode<br/>
    copyright 2015
</footer>
</body>
</html>