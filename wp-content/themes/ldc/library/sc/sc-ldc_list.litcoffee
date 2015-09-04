Clientside List Shortcode
=========================

#### Included inline by /sc/sc-ldc_list.php

To watch and compile automatically, from the theme directory: 
```bash
coffee --watch --compile library/sc/sc-ldc_list.litcoffee
```




Helpers
-------

#### `ª()`
Shorthand `console.log()`. 

    ª = console.log.bind console




#### `$()`
Ensure `$` stands for `jQuery` within this closure. 

    $ = jQuery




Main Class
----------

    class Main

      constructor: (config) ->
        @me = 'sc/sc-ldc_list.js:Main()\n  '

        if 'function'  != typeof $ then throw Error @me + "No jQuery"




Main Properties
---------------

#### `$$listWraps <NodeList>`
jQuery references to each '.ldc-list-wrap' element. 

        @$$listWraps = $ '.ldc-list-wrap'
        if 0 == @$$listWraps.length
          return ª @me + "Warning: no '.ldc-list-wrap' elements found"


#### `$$listPosts <NodeList>`
jQuery references to each '.ldc-list-wrap' element. 

        @$$listPosts = $ '.ldc-list-post'
        if 0 == @$$listPosts.length
          return ª @me + "Warning: no '.ldc-list-post' elements found"


Initialize the app. 

        @initListPosts()




Main Init Methods
-----------------


#### `initListPosts()`
Xx. 

      initListPosts: ->
        main = @
        @$$listPosts.on 'click', (event) ->
          main.$$listPosts.removeClass 'ldc-focus'
          $(@).addClass 'ldc-focus'




Boot
----

When the DOM is ready, create an instance of Main, which boots the app. 

    $ -> window.sc_ldc_list = new Main




