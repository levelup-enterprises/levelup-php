<?php

namespace Http;

use Http\Session;
use FFI\Exception;

class Request
{
  public function handleRequest()
  {
    if ($_POST) {
      return true;
    } else {
      return false;
    }
  }

  /** ----------------------------
   *? Redirect browser
   * -----------------------------
   * @return string $query
   */
  public static function redirect($page, $code = null)
  {
    try {
      $page === "/" && ($page = "");
      $code
        ? header("Location: /$page", true, $code)
        : header("Location: /$page");
    } catch (Exception $e) {
      die($e);
    }
  }

  /** ----------------------------
   *? Get GET query
   * -----------------------------
   * Get & clean query
   * @return string $query
   */
  public static function get($query)
  {
    try {
      if (isset($_GET[$query])) {
        if (self::isJson($query)) {
          $value = json_decode($query);
          foreach ($value as $n => $v) {
            $_GET[$query][$n] = $v;
          }
          return $_GET[$query];
        }
        return $_GET[$query];
      } else {
        return null;
      }
    } catch (Exception $e) {
      die($e);
    }
  }

  /** ----------------------------
   *? Get POST query
   * -----------------------------
   * Get & clean query
   * @return string $query
   */
  public static function post($query)
  {
    if (isset($_POST[$query])) {
      return $_POST[$query];
    } else {
      return null;
    }
  }

  /** ----------------------------
   *? Get All GET queries
   * -----------------------------
   * Get & clean GET queries
   * @return array $form
   */
  public static function allGet()
  {
    try {
      if (isset($_GET)) {
        $form = [];
        foreach ($_GET as $name => $value) {
          if (self::isJson($value)) {
            $value = json_decode($value);
            foreach ($value as $n => $v) {
              $form[$n] = $v;
            }
          } else {
            $form[$name] = $value;
          }
        }
        return $form;
      } else {
        return null;
      }
    } catch (Exception $e) {
      die($e);
    }
  }

  /** ----------------------------
   *? Get All POST queries
   * -----------------------------
   * Get & clean POST queries
   * @return array $form
   */
  public static function allPost()
  {
    try {
      $form = [];
      foreach ($_POST as $name => $value) {
        $form[$name] = $value;
      }
      return $form;
    } catch (Exception $e) {
      die($e);
    }
  }

  /** ----------------------------
   *? Return HTTP_HOST
   * -----------------------------
   * @return string host
   */
  public static function getHost()
  {
    if ($_SERVER['HTTP_HOST']) {
      return urldecode(parse_url($_SERVER['HTTP_HOST'], PHP_URL_PATH));
    } else {
      return null;
    }
  }

  /** ----------------------------
   *? Return REQUEST_URI
   * -----------------------------
   * @return string uri
   */
  public static function getURI()
  {
    if (isset($_SERVER['REQUEST_URI'])) {
      return urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    } else {
      return '/';
    }
  }

  /** ----------------------------
   *? Return IP address
   * -----------------------------
   * @return string uri
   */
  public static function getIPAddress()
  {
    if (isset($_SERVER['REMOTE_ADDR'])) {
      return $_SERVER['REMOTE_ADDR'];
    } else {
      return null;
    }
  }

  /** ----------------------------
   *? Return HTTP_X_AUTH_TOKEN
   * -----------------------------
   * @return string token
   */
  public static function getXToken()
  {
    if (isset($_SERVER['HTTP_X_AUTH_TOKEN'])) {
      return urldecode(parse_url($_SERVER['HTTP_X_AUTH_TOKEN'], PHP_URL_PATH));
    } else {
      return null;
    }
  }

  /** ----------------------------
   *? Return HTTP_REFERER
   * -----------------------------
   * @return string referer
   */
  public static function getReferer()
  {
    if (isset($_SERVER['HTTP_REFERER'])) {
      return urldecode(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
    } else {
      return null;
    }
  }

  /** ----------------------------
   *? Return DOCUMENT_ROOT
   * -----------------------------
   * @return string root
   */
  public static function getRoot()
  {
    if (isset($_SERVER['DOCUMENT_ROOT'])) {
      return realpath(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']));
    } else {
      return null;
    }
  }

  /** ---------------------------
   *? Set timeout
   * -----------------------------
   * Set timeout counter for
   *   session.
   * @param string $session
   */
  private static function timeout()
  {
    if (
      !empty(Session::get('LAST_ACTIVITY')) &&
      time() - Session::get('LAST_ACTIVITY') > TIMEOUT
    ) {
      Session::logout([
        'success' => 'You have been logged out due to inactivity.',
      ]);
      exit();
    }
    Session::set('LAST_ACTIVITY', time(), true);
  }

  /** ----------------------------
   *? Redirect to login
   * -----------------------------
   * If loggedIn session is false
   *  set intended URI as session
   *  & redirect to login page.
   *
   * @return header login
   */
  public static function secure()
  {
    self::timeout();
    if (empty(Session::get('loggedIn'))) {
      Session::logout(
        ['success' => 'You have been successfully logged out.'],
        true
      );
    }
  }

  /** ----------------------------
   *? Continue to intended url
   * -----------------------------
   * Capture URI session if set &
   *  continue after login.
   *
   * @return string url
   */
  public static function continue()
  {
    if (!empty(Session::get('uri'))) {
      echo Session::get('uri');
    } else {
      echo "/";
    }
  }

  /** ---------------------------
   *? Verify CSRF Token
   * -----------------------------
   * Compare session token with
   *  post token.
   * @param POST csrf-token
   * @return bool
   */
  public static function verify_token($token = false)
  {
    try {
      !$token && ($token = self::post('csrf-token'));
      if (!empty(Session::get('auth_token')) && !empty($token)) {
        if (hash_equals(Session::get('auth_token'), $token)) {
          return true;
        }
      }
      return false;
    } catch (Exception $e) {
      Session::set('status', ['errors' => $e]);
    }
  }

  /** ---------------------------
   *? Verify Header Token
   * -----------------------------
   * Compare session token with
   *  header token to
   *  validate ajax.
   *
   * @param HTTP header
   * @return bool
   */
  public static function verify_header()
  {
    try {
      if (self::getXToken() !== Session::get('auth_token')) {
        Response::message(['errors' => 'Invalid token!']);
      }
    } catch (Exception $e) {
      Session::set('status', ['errors' => $e->getMessage()]);
    }
  }

  /** ---------------------------
   *? Authenticate auth request
   * -----------------------------
   * Decrypt request to validate
   *
   * @param HTTP header
   * @return bool
   */
  public static function authRequest()
  {
    try {
      if (
        isset($_SERVER["HTTP_AUTHORIZATION"]) &&
        stripos($_SERVER["HTTP_AUTHORIZATION"], 'basic ')
      ) {
        $secret = substr($_SERVER["HTTP_AUTHORIZATION"], 0, 6);
        // $nonce = sodium_hex2bin(substr($secret, 0, 48));
        // $ciphered = sodium_hex2bin(substr($secret, 48));
        // $plaintext = sodium_crypto_auth_verify(
        //   $ciphered,
        //   $nonce,
        //   getenv('KEY')
        // );

        // print_r($plaintext);
        // die();

        // $plaintext !== getenv('AUTH_PHRASE')
        //   ? Response::message([
        //     'error' => ['message' => 'Authentication failed!'],
        //   ])
        //   : Response::message(['success' => true]);
      } else {
        Response::message(['error' => ['message' => 'Authentication failed!']]);
      }
    } catch (Exception $e) {
      Response::message(['error' => ['message' => $e]]);
    }
  }

  /** ---------------------------
   *? Sanitize values
   * -----------------------------
   *
   * @param mixed values
   * @return mixed
   */
  private static function sanitize($values)
  {
    if (is_object($values)) {
      $return = "";
      foreach ($values as $k => $v) {
        $return[htmlspecialchars($k)] = htmlspecialchars($v);
      }
    } elseif (is_array($values)) {
      $return = [];
      foreach ($values as $v) {
        $return[] = htmlspecialchars($v);
      }
    } else {
      $return = htmlspecialchars($values);
    }
    return $return;
  }

  /** ---------------------------
   *? Check if json data type
   * -----------------------------
   *
   * @param string string
   * @return bool
   */
  private static function isJson($string)
  {
    json_decode($string);
    return json_last_error() == JSON_ERROR_NONE;
  }
}