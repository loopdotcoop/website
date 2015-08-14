<?php
//// 20150814^RP 0.0.3

//// `rp_dev()` can help to reveal how an HTML page has been constructed, and 
//// also make error messages quicker to debug. See the end of this file for 
//// usage examples and licensing info. 


//// `rp-dev.php` can be `require`d multiple times without causing errors. 
if (! function_exists('rp_dev')) {

  function rp_dev($title='', $dev_message='') {

    //// In non-development environments, just show the title. 
    //// `WP_ENV` should be defined in /wp-config.php, eg one of:
    //// `define('WP_ENV', 'development');`
    //// `define('WP_ENV', 'staging');`
    //// `define('WP_ENV', 'production');`
    //// See https://roots.io/plugins/stage-switcher/
    if (! defined('WP_ENV') || 'development' !== WP_ENV) { return $title; }

    //// Get the caller's `__FILE__`, `__FUNCTION__` and `__LINE__`. 
    $bt       = debug_backtrace(); // http://php.net/debug-backtrace
    $file     = $bt[0]['file'];
    $function = $bt[1]['function'];
    $line     = $bt[0]['line'];

    //// Strip the absolute path, up to the the developer's working directory. 
    preg_match('/\/(themes|plugins)\/[^\/]+\//', $file, $matches, PREG_OFFSET_CAPTURE); //@todo test plugins
    if (0 === count($matches)) { // not in a theme or plugin directory
      $file = basename($file);   // don't risk exposing absolute path
    } else {
      $file = substr($file, $matches[0][1] + strlen($matches[0][0]));
    }

    //// In development environments, show the title followed by useful context, 
    //// followed by a developer-only message. 
    //// Note that the 'foo/bar/foobar.php:123' part of the output string can be
    //// pasted into the command line, eg `$ subl foo/bar/foobar.php:123`. 
    return ($title ? $title . ':  ' : '')
      . $file . ':' . $line . '  ' . $function . '()'
      . ($dev_message ? '  <br>' . $dev_message : '')
    ;
  }

}


//// Example 1: Reveal an HTML snippet's origin. 
//// ```
//// function foobar($foo, $bar) {
////   echo '<!-- BEGIN ' . rp_dev('Foobar Widget') . " -->\n";
////   echo "The $foo $bar. \n";
////   echo '<!-- END   ' . rp_dev('Foobar Widget') . " -->\n";
//// }
//// foobar('fox','trots');
//// ```
//// ...which renders as...
//// ```
//// <!-- BEGIN Foobar Widget:  foo/bar/foobar.php:2  foobar() -->
//// The fox trots. 
//// <!-- END   Foobar Widget:  foo/bar/foobar.php:4  foobar() -->
//// ```
//// ...during development, but...
//// ```
//// <!-- BEGIN Foobar Widget -->
//// The fox trots. 
//// <!-- END   Foobar Widget -->
//// ```
//// ...in the staging and production environments.


//// Example 2: Make error messages quicker to debug. 
////@todo


/*
The MIT License (MIT)

Copyright (c) 2015 Rich Plastow (http://richplastow.com/)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
?>