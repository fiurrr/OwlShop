<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Owl Things - thing in beauty</title>

    <script src="/shop/public/js/jquery-1.9.1.js"></script>
    <script src="/shop/public/js/angular.min.js"></script>

    <script src="/shop/public/js/angular-route.min.js"></script>
    <!-- <script src="/shop/public/js/controllers/mainCtrl.js"></script>-->
     <script src="/shop/public/js/services/productService.js"></script>
     <script src="/shop/public/js/app.js"></script>
    <script src="/shop/public/js/controllers/ctrls.js"></script>
    <script src="/shop/public/js/tricky.js"></script>

    <link href="/shop/public/css/style.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Exo+2:400,100' rel='stylesheet' type='text/css'>
</head>
<body ng-app="productApp" ng-controller="productListCtrl">
    <header class="top">
        <div class="icons">
            <div class="scrollup"></div>
            <div class="search" onclick="searchForm()"></div>
            <div class="basket"></div>
        </div>
        <div class="search-form" style="display:none;">
            <input ng-model="query" placeholder="szukaj...">
            {{query}}
        </div>
        <h1><a href="/shop/#/">Owl Things</a></h1>
    </header>
    <div ng-view></div>
    <footer>
        created by owlCode<br/>
        copyright 2015
    </footer>
</body>
</html>