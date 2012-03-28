<?php

/**
* 
*/
class Render {
  
  protected $_what;
  protected $_vars;
  
  function __construct($what = '', $vars = array()) {
    $this->_what = $what;
    $this->_vars = $vars;
  }
  
  public function printOut() {
    if (file_exists($this->_skinFile())) {
      extract($this->_vars, EXTR_REFS);
      include $this->_skinFile();
    }
  }
  
  private function _skinFile() {
    return 'skin/'.$this->_what.'.php';
  }
  
  public function setVar($name, $value) {
    $this->_vars[$name] = $value;
  }
}
