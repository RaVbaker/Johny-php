<?php

require 'lib/app.php';

class ShopCat extends App {
  public $title = "Shop Cat";
  public function shopPage($id) {
    var_dump($this->_method, $id);
    return $this->_render('Layout#index');
  }
}
