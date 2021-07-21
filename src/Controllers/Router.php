<?php

namespace Controllers;

use Exception;
use Http\Request;
use Http\Response;

class Router
{
  private $request;
  private $isRequest;
  private $pages;

  public function __construct()
  {
    $this->request = Request::getURI();
    // POST calls
    $isRequest = (new Request())->handleRequest();
    $isRequest && $this->backendRoutes();
  }

  /** ----------------------------
   ** Redirect URI
   * -----------------------------
   * Capture & clean URI to filter
   *  through accepted pages.
   *
   * @return string url
   */
  public function route()
  {
    // Get url var
    $uri = trim($this->request, "/");
    $uri = explode(".php", $uri);
    $uri = explode("/", $uri[0]);

    // If var empty set to index
    if (empty($uri[0])) {
      $uri[0] = HOME_DIR;
    }

    // Check for deep roots
    if (isset($uri[1])) {
      $uris = "";
      foreach ($uri as $i) {
        $uris .= $i . "/";
      }
      $uri[0] = substr($uris, 0, -1);
    }

    // Check if site exist
    if (in_array(strtolower($uri[0]), $this->getPages())) {
      return "/" . VIEWS . "$uri[0]/index.php";
    } else {
      return '/' . VIEWS . '404/index.php';
    }
  }

  /** ------------------------------
   ** Allow access to backend files
   * -------------------------------
   *  - Get available routes
   *  - Check with URI request
   *  - Verify header matches token
   */
  private function backendRoutes()
  {
    // Get url var
    $uri = trim($this->request, "/");
    $uri = explode(".php", $uri);
    $uri = explode("/", $uri[0]);

    // Check if file exist
    if (in_array(strtolower($uri[1]), $this->getPages(true))) {
      Request::verify_header();
    } else {
      Response::message(['error' => 'Method not allowed!'], 400);
    }
  }

  /** ----------------------------
   ** Filter Pages
   * -----------------------------
   * Look through folders in
   *  received path & build array
   *  of pages to return.
   *
   * @param string path to page directory
   * @return array pages
   */
  private function getPages($returnBackend = false)
  {
    try {
      // Build for backend/frontend accessible files
      $returnBackend
        ? ($root = Request::getRoot() . "/" . SCRIPTS)
        : ($root = Request::getRoot() . "/" . VIEWS);

      $pages = [];
      $directories = [];
      $last_letter = $root[strlen($root) - 1];
      $root = $last_letter == '\\' || $last_letter == '/' ? $root : $root . '/';

      $directories[] = $root;

      // Get multilevel pages
      while (sizeof($directories)) {
        $dir = array_pop($directories);
        if ($handle = opendir($dir)) {
          while (false !== ($file = readdir($handle))) {
            // Avoid these directories
            if ($file == '.' || $file == '..') {
              continue;
            }
            // Remove component directories
            if ($file !== COMPONENT_DIR) {
              $file = $dir . $file;
              if (!$returnBackend && is_dir($file)) {
                $directory_path = $file . '/';
                array_push($directories, $directory_path);
              } elseif (is_file($file)) {
                // Remove root
                $file = str_replace($root, "", $file);
                // Remove extension
                $page = explode("/", $file);
                // Format for return
                if ($returnBackend) {
                  $page = explode(".php", $file);
                  array_push($pages, $page[0]);
                } else {
                  array_pop($page);
                  $page = implode('/', $page);
                  array_push($pages, $page);
                }
              }
            }
          }
          closedir($handle);
        }
      }
      return $returnBackend ? $pages : array_unique($pages);
    } catch (Exception $e) {
      print_r($e);
    }
  }

  /** ----------------------------
   ** Format URI
   * -----------------------------
   * Return clean uri as path OR
   *  as page name.
   *
   * @param string $uri
   * @param boolean $returnName
   * @return string uri or name
   */
  public static function trimURI($returnName = false)
  {
    $page = rtrim(Request::getURI(), '/');
    $page = explode(".php", $page);

    $page = explode("/", $page[0]);
    $pageName = array_pop($page);

    $root = "";
    foreach ($page as $i) {
      $root .= $i . "/";
    }
    // Return page name
    if ($returnName) {
      empty($pageName) && ($pageName = HOME_DIR);
      $root = $pageName;
    }
    return $root;
  }
}