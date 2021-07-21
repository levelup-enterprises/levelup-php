<?php

namespace Http;

use Http\Request;

class Session
{
  /** ---------------------------------
   ** Initialize session with login
   *  ---------------------------------
   * Create initial session with
   *    optional timeout.
   *
   * @param bool timeout default null
   */
  function __construct($timeout = null)
  {
    $this->start();
    $timeout && self::timeout();
  }

  /** ---------------------------
   ** Start session
   * ----------------------------
   * Start new session with
   *  timeout counter.
   */
  private function start()
  {
    session_start();
  }

  /** ---------------------------
   ** Create session
   * ----------------------------
   * Create session from value
   * @param string $session
   * @param string $value
   * @param bool $override replace value
   */
  public static function set($session, $value, $override = false)
  {
    if (!$override && isset($_SESSION[$session])) {
      is_array($value) && ($value = $value[0]);
      array_push($_SESSION[$session], $value);
    } else {
      $_SESSION[$session] = $value;
    }
  }

  /** ---------------------------
   ** Get session
   * ----------------------------
   * Get requested session
   * Optional clear after use
   * @param string $session
   * @param bool $clear get and clear
   * @return mixed array OR string
   */
  public static function get($session, $clear = null)
  {
    if (isset($_SESSION) && isset($_SESSION[$session])) {
      $return = $_SESSION[$session];
      $clear !== null && self::clear($session);
      return $return;
    }
  }

  /** ---------------------------
   ** Clear session data
   * ----------------------------
   * - Destroy session
   */
  public static function clear($session = null)
  {
    if ($session === null) {
      if (isset($_SESSION)) {
        unset($_SESSION['errors']);
        unset($_SESSION['success']);
        unset($_SESSION['info']);
        unset($_SESSION['warnings']);
      }
    } else {
      if (isset($_SESSION)) {
        unset($_SESSION[$session]);
      }
    }
  }

  /** ---------------------------
   ** Check for user session
   * ----------------------------
   * Redirect to login if session
   *  not set.
   * @param string $session
   */
  public static function active($session)
  {
    if (isset($_SESSION) && isset($_SESSION[$session])) {
      return $_SESSION[$session];
    } else {
      Request::redirect('login');
    }
  }

  /** ---------------------------
   ** Logout current user
   * ----------------------------
   * - Destroy session
   * - Redirect to login page
   */
  public static function logout(array $status = null)
  {
    if (isset($_SESSION)) {
      session_unset();
      session_destroy();

      (new Session())->set('uri', Request::getURI());
      $status && (new Session())->set('status', $status);
      Request::redirect('login');
    }
  }

  /** ---------------------------
   ** Set timeout
   * -----------------------------
   * Set timeout counter for
   *   session.
   * @param string $session
   */
  public static function timeout()
  {
    if (
      !empty(self::get('LAST_ACTIVITY')) &&
      time() - self::get('LAST_ACTIVITY') > TIMEOUT
    ) {
      self::logout([
        'success' => 'You have been logged out due to inactivity.',
      ]);
      exit();
    }
    self::set('LAST_ACTIVITY', time(), true);
  }

  /** ---------------------------
   ** Set CSRF Token
   * -----------------------------
   * Set token if it doesn't
   *  exist, get token.
   * @return string token
   */
  public static function csrf_token()
  {
    if (empty(self::get('auth_token'))) {
      self::set('auth_token', bin2hex(random_bytes(20)));
    }
    return self::get('auth_token');
  }
}
