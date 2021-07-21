<?php

namespace Controllers;

use Http\request;
use Controllers\Router;

class Link
{
  function __construct()
  {
    $this->views = VIEWS;
    $this->uri = Router::trimURI();
    $this->page = Router::trimURI(true);
    $this->root = Request::getRoot();
  }

  /** ----------------------------
   ** Return App path
   * -----------------------------
   * @param string $path core directory
   * @return string path for inclusion
   */
  public function app($path)
  {
    return "$this->root/app/${path}";
  }

  /** ----------------------------
   ** Return public path
   * -----------------------------
   * @param string $path public directory
   * @return string path for inclusion
   */
  public function public($path, $file = "")
  {
    return "/public/${path}/${file}";
  }

  /** ----------------------------
   ** Return file to include
   * -----------------------------
   * - Use inside include function
   *
   * @param string $path templates directory
   * @param string $fileName name of file w/out ext
   * @return string path for inclusion
   */
  public function get($path, $fileName = null)
  {
    $fileName ? ($fileName .= ".php") : ($fileName = "");
    return "$this->root/$this->views/$path/$fileName";
  }

  /** -------------------------
   ** Return current view path
   * --------------------------
   * Includes current page view
   * - Use inside include function
   */
  public function getView()
  {
    isset($this->uri) ? ($page = "/" . $this->uri) : "";
    return "$this->root/$this->views$page$this->page/view.php";
  }

  /** -------------------------
   ** Return Component path
   * --------------------------
   * Return requested component path
   * - Use inside include function
   */
  public function getComponent($component)
  {
    return "$this->root/$this->views/" . COMPONENT_DIR . "/$component.php";
  }
}