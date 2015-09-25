<?php
//// 20150814^RP 0.0.2

//// `rp_dump()` returns a `print_r()` representation of one or more variables, 
//// each wrapped in a `<PRE>` element. It's most useful for revealing an array 
//// or object's contents at a given point in the code. 


//// `rp_dump.php` can be `require`d multiple times without causing errors. 
if (! function_exists('rp_dump')) {

  function rp_dump() {

    //// In non-development environments, do nothing. 
    //// `WP_ENV` should be defined in /wp-config.php, eg one of:
    //// `define('WP_ENV', 'development');`
    //// `define('WP_ENV', 'staging');`
    //// `define('WP_ENV', 'production');`
    //// See https://roots.io/plugins/stage-switcher/
    if (! defined('WP_ENV') || 'development' !== WP_ENV) { return ''; }

    //// Initialize the output-array. 
    $out = array();
    $out[] = '<div class="rp-dump-wrap">';

    //// Wrap each argument in a `<PRE>` element.
    ////@todo pretty?
    $arguments = func_get_args();
    foreach ($arguments as $argument) {
      switch (gettype($argument)) { // http://php.net/gettype
        case 'boolean':
          $out[] = '<div class="rp-boolean"><tt>' . ($argument ? 'true' : 'false') . '</tt></div>';
          break;
        case 'integer':
          $out[] = '<div class="rp-integer"><tt>' . $argument . '</tt></div>';
          break;
        case 'double':
          $out[] = '<div class="rp-double"><tt>' . $argument . '</tt></div>';
          break;
        case 'string':
          $out[] = '<div class="rp-string"><tt>' . ('' === $argument ? '[EMPTY STRING]' : esc_html($argument)) . '</tt></div>';
          break;
        case 'NULL':
          $out[] = '<div class="rp-null"><tt>[NULL]</tt></div>';
          break;
        default:
          $out[] = '<pre class="rp-other">' . esc_html(print_r($argument, true)) . '</pre>';
      }
    }

    //// In development environments, show the arguments formatted for HTML. 
    //// @todo plaintext and ansi formats
    $out[] = '</div>';
    return implode("\n", $out);
  }

}


//// Example 1: Reveal an array's content. 
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