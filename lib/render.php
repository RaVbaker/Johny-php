<?php

class Render {
  
  protected $_template;
  protected $_vars;
  protected $_layout = 'Layout';
  
  function __construct($what = '', $vars = array()) {
    $this->_setTemplate($what);
    $this->_vars = $vars;
  }
  
  private function _setTemplate($what) {
    if (false !== strpos($what, '#')) {
      list($this->_layout, $this->_template) = explode('#', $what);
    } else {
      $this->_template = $what;
    }
  }
  
  public function printOut() {
    if (file_exists($this->_skinFile())) {
      extract($this->_vars, EXTR_REFS);
      $_template = $this->_skinFile();
      $_layout = $this->_skinFile($this->_layout);
      if (file_exists($_layout)) {
        include $_layout;
      } else {
        include $_template;
      }
    }
  }
  
  private function _skinFile($file = null) {
    if (is_null($file)) {
      $file = $this->_template;
    }
    return 'skin/'.$file.'.php';
  }
  
  public function setVar($name, $value) {
    $this->_vars[$name] = $value;
  }
}
