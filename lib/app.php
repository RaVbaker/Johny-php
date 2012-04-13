<?php

require_once 'lib/render.php';

/**
 * Heart of app. It's a class which supports dispatching route, calling right action and running render. 
 * To create own app make own class based on this one and add own methods to this. Then if you make simple index.php with route '/:method/:arg?.html' => ':method' you will get any action available on their address. Like: shopPage/123.html or shopPage.html. 
 * Render used is this one which is returned from function. you can use helper $this->_render() which outputs current method name view or add few vars to it using $this->_render(array('var1' => 1, ...)). Of course you can set template name: $this->_render('tmpl1', array('var1' => 1, ...)) or even with Layout: $this->_render('Layout2#tmpl1', array('var1' => 1, ...)). If you want to use your own render use like this: $this->_render(new JsonRender($vars)). Using this your printOut would have var 'app' available. 
 *
 * Every app has $this->_env with PHP magic vars like '_SERVER', '_SESSION' ,'_REQUEST', '_POST' and '_GET'. 
 *
 * @author Rafał Piekarski
 */
class App {
  
  protected $_env = array(); 
  protected $_method = 'index';
  public $url;
  
  function __construct($server, $session, $request, $post, $get) {
    foreach (array('server', 'session', 'request', 'post', 'get') as $name) {
      $this->_env[$name] = $$name;
    }
  }
  
  /**
   * Runs route method based on $routes
   *
   * @param array $routes 
   * @return void
   * @author Rafał Piekarski
   */
  public function dispatchRoute($routes) {
    foreach($routes as $route => $method) {
      if ($args = $this->_checkRoute($route)) {
        if ($method == ':method') {
          $method = array_shift($args);
        }
        
        $this->_method = $method;
        break;
      }
    }              
    $this->_callMethod($args);
  }
  
  private function _checkRoute($route) {
    $this->url = $this->_env['server']['PATH_INFO'];

    $expression = $this->_routeAsExpression($route);
    $args = array();
    
    if (preg_match($expression, $this->url, $args)) {
      array_shift($args);
      return $args;
    }
    return false;
  }
  
  private function _routeAsExpression($route) {
    $route = str_replace('.', '\\.', $route);
    $route = str_replace('/', '/?', $route);
    
    $route = str_replace(':int', '(\d+)', $route);
    $route = str_replace(':string', '([^/.]+)', $route);
    $route = str_replace(':arg', '([^/.]+)', $route);
    $route = str_replace(':method', '([^/.]+)', $route);
    $route = str_replace(':float', '([0-9.]+)', $route);
    $route = str_replace(':catch_all', '(.*)', $route);
    return '#'.$route.'#';
  }
  
  private function _callMethod($args) {
    $this->_callBefore();                                                 

    $renderer = call_user_func_array(array($this, $this->_method), $args);
    $this->_callAfter();
    
    if ($renderer instanceOf Render) {
      $renderer->printOut();
      $this->_callAfterRender();
    }
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
