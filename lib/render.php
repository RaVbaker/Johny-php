<?php

/**
 * Class for support in rendering views. Support layouts and more. To change default layout `Layout` use constructor like this:
 * new Render('DifferentLayout#template', array('val1'=> 1,...));
 * 
 * All vars from second argument are available in layout scope. Also feel free to create own classes based on this one. Like: class JsonRender { ... } class XmlRender { ... } etc.
 * 
 * More examples soon...
 *
 * @author Rafał Piekarski
 */
class Render {
  
  protected $_template;
  protected $_vars = array();
  protected $_layout = 'Layout';
  
  protected static $_dir = 'views/';
  protected static $_extension = '.php';
  protected static $_layoutTemplateSeparator = '#';
  
  function __construct($what = '', $vars = array()) {
    $this->_setTemplate($what);
    $this->_vars = $vars;
  }
  
  private function _setTemplate($what) {
    if (false !== strpos($what, self::$_layoutTemplateSeparator)) {
      list($this->_layout, $this->_template) = explode(self::$_layoutTemplateSeparator, $what);
    } else {
      $this->_template = $what;
    }
  }
  
  public function printOut() {
    extract($this->_vars, EXTR_REFS);
    $_template = $this->_viewFilePath();
    if (file_exists($_template)) {
      $_layout = $this->_viewFilePath($this->_layout);
      if (file_exists($_layout)) {
        include $_layout;
      } else {
        include $_template;
      }
    }
  }
  
  private function _viewFilePath($file = null) {
    if (is_null($file)) {
      $file = $this->_template;
    }
    return self::$_dir.$file.self::$_extension;
  }
  
  public function setVar($name, $value) {
    $this->_vars[$name] = $value;
  }
  
  public function getVar($name) {
    return $this->_vars[$name];
  }
}
