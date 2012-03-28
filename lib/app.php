<?php

require_once 'lib/render.php';

/**
* 
*/
class App {
  
  protected $_env = array(); 
  protected $_method = 'index';
  
  function __construct($server, $session, $request, $post, $get) {
    foreach (array('server', 'session', 'request', 'post', 'get') as $name) {
      $this->_env[$name] = $$name;
    }
  }
  
  public function dispatchRoute($routes) {
    foreach($routes as $route => $method) {
      if ($args = $this->_checkRoute($route)) {     
        $this->_method = $method;
        break;
      }
    }                
    $this->_callBefore();
    $renderer = call_user_func_array(array($this, $this->_method), $args);
    $this->_callAfter();
    if ($renderer instanceOf Render) {
      $renderer->printOut();
      $this->_callAfterRender();
    }
  }
  
  private function _checkRoute($route) {
    $url = $this->_env['server']['PATH_INFO'];

    $expression = $this->_routeAsExpression($route);
    $args = array();
    
    if (preg_match($expression, $url, $args)) {
      array_shift($args);
      return $args;
    }
    return false;
  }
  
  private function _routeAsExpression($route) {
    $route = str_replace('.', '\\.', $route);
    $route = str_replace(':int', '(\d+)', $route);
    $route = str_replace(':string', '([^/.]+)', $route);
    $route = str_replace(':float', '([0-9.]+)', $route);
    $route = str_replace(':catch_all', '(.*)', $route);
    return '#'.$route.'#';
  }
  
  /**
   * Default action
   *
   * @return Render
   * @author Rafał Piekarski
   */
  public function index() {
    return $this->_render();
  }
                                   
  /**
   * Renders view
   *
   * @param string|Render $what 
   * @param array $vars 
   * @return Render
   * @author Rafał Piekarski
   */
  protected function _render($what='', $vars = array()) {
    if ($what instanceOf Render) {
      $what->setVar('app', $this);
      return $what;
    } elseif (is_array($what)) {
      $vars = $what;
      $what = '';
    }
    if (empty($what)) {
      $what = $this->_method;
    }
    
    $vars['app'] = $this;
    return new Render($what, $vars);
  }
  
  /**
   * Overwrite this method if needed
   *
   * @return void
   * @author Rafał Piekarski
   */
  protected function _callBefore() {
    
  }
  
  
  /**
   * Overwrite this method if needed
   *
   * @return void
   * @author Rafał Piekarski
   */
  protected function _callAfter() {
    
  }
  
  /**
   * Overwrite this method if needed
   *
   * @return void
   * @author Rafał Piekarski
   */
  protected function _callAfterRender() {
    
  }
}
