<?php

namespace Http;

class Response
{
  /** ----------------------------------
   ** Exit and return jsonified message
   * -----------------------------------
   * @param {mixed} $status
   * @param {bool} $exit after echo (default)
   * @return json array
   *
   *# Error codes
   *
   * 100: 'Continue'
   *
   * 101: 'Switching Protocols'
   *
   * 200: 'OK'
   *
   * 201: 'Created'
   *
   * 202: 'Accepted'
   *
   * 203: 'Non-Authoritative Information'
   *
   * 204: 'No Content'
   *
   * 205: 'Reset Content'
   *
   * 206: 'Partial Content'
   *
   * 300: 'Multiple Choices'
   *
   * 301: 'Moved Permanently'
   *
   * 302: 'Moved Temporarily'
   *
   * 303: 'See Other'
   *
   * 304: 'Not Modified'
   *
   * 305: 'Use Proxy'
   *
   * 400: 'Bad Request'
   *
   * 401: 'Unauthorized'
   *
   * 402: 'Payment Required'
   *
   * 403: 'Forbidden'
   *
   * 404: 'Not Found'
   *
   * 405: 'Method Not Allowed'
   *
   * 406: 'Not Acceptable'
   *
   * 407: 'Proxy Authentication Required'
   *
   * 408: 'Request Time-out'
   *
   * 409: 'Conflict'
   *
   * 410: 'Gone'
   *
   * 411: 'Length Required'
   *
   * 412: 'Precondition Failed'
   *
   * 413: 'Request Entity Too Large'
   *
   * 414: 'Request-URI Too Large'
   *
   * 415: 'Unsupported Media Type'
   *
   * 500: 'Internal Server Error'
   *
   * 501: 'Not Implemented'
   *
   * 502: 'Bad Gateway'
   *
   * 503: 'Service Unavailable'
   *
   * 504: 'Gateway Time-out'
   *
   * 505: 'HTTP Version not supported'
   */
  public static function message($status, $header = 200, $exit = true)
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
      http_response_code($header);
      echo json_encode($status);
      $exit && exit();
    }
  }
}