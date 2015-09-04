<?php
//// 20150814^RP 0.0.1
//// Add a `<STYLE>` block to the `<HEAD>` for development. 

add_action('wp_head'   , 'rp_dev_style'); // frontend
add_action('admin_head', 'rp_dev_style'); // backend
function rp_dev_style() {
  echo "\n\n<!-- BEGIN " . rp_dev('Development Style') . " -->\n";
?>
<style type="text/css">
  body.wp-admin .rp-dump-wrap,
  body.wp-admin .rp-success,
  body.wp-admin .rp-error {
    margin: 1em 1em 0 200px;
  }
  .rp-dump-wrap {
    padding: .2em .5em;
    margin-bottom: .5em;
    border: 1px solid #999;
    border-radius: 8px;
    background-color: #fff;
  }
  body.wp-admin .rp-other {
    background-color: #e8e8e8;
    padding: .1em .5em;
    border-radius: 4px;
  }
</style>
<?php
  echo "<!-- END   " . rp_dev('Development Style') . " -->\n\n\n";
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