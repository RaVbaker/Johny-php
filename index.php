<?php

require_once 'app/shop_cat.php';

$shopCat = new ShopCat($_SERVER, $_SESSION, $_REQUEST, $_POST, $_GET);


$shopCat->dispatchRoute(array(
  '/:int.html' => 'shopPage',
  '/' => 'index'
));
