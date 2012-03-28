<?php

class DataSource {
  
  private static $_storage = array();
  
  private $_instance = null;
  private $_lookupIndex = null;
  
  function _construct($object = array()) {
    $this->_instance = $object;
  }
  
  public function create($index = null) {
    self::$_storage[$index] = $this->_instance;
    if (!is_null($index)) {
      return $this->_lookupIndex = array_search($this->_instance, self::$_storage, true);
    }
    return $this->_lookupIndex = $index;
  }
  
  public function read($index) {
    $this->_lookupIndex = $index;
    return $this->_instance = self::$_storage[$index];
  }
  
  public function update($index = null) {
    if (is_null($index)) {
      $index = $this->_lookupIndex;
    }
    self::$_storage[$index] = $this->_instance;
    return $index;
  }
  
  public function delete($index = null) {
    if (!is_null($index)) {
      $this->_lookupIndex = $index;
    }
    $this->_instance = clone self::$_storage[$this->_lookupIndex];
    unset(self::$_storage[$this->_lookupIndex]);
    return array($this->_lookupIndex, $this->_instance);
  }
  
  public function set($instance) {
    $this->_instance = $instance;
  }
  
  public function get() {
    return $this->_instance;
  }
}
