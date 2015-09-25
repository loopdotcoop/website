<?php
//// 20150921^RP 0.0.1

//// `rp_enqueue()` enqueues useful stylesheets and scripts, if needed:
//// WordPress’s ‘dashicons’ @todo describe
//// @todo add more
//// @todo only enqueue what the current page needs


//// `rp_enqueue.php` can be `require`d multiple times without causing errors. 
if (! function_exists('rp_enqueue')) {

  //// https://codex.wordpress.org/Function_Reference/wp_enqueue_script
  add_action('wp_enqueue_scripts', 'rp_enqueue');
  function rp_enqueue($classes) {

    //// http://jameskoster.co.uk/work/using-wordpress-3-8s-dashicons-theme-plugin/
    wp_enqueue_style('dashicons');

  }

}


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