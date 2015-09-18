Clientside List Shortcode
=========================

#### Included inline by /library/sc/sc-ldc_list.php

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
        M = "/library/sc/sc-ldc_list.litcoffee
          Main()\n  "

        if 'function' != typeof $ then throw Error "
          #{M}No jQuery"




Main Properties
---------------

#### `$$listWraps <NodeList>`
jQuery references to each '.ldc-list-wrap' element. 

        @$$listWraps = $ '.ldc-list-wrap'
        if 0 == @$$listWraps.length then ª "
          #{M}Warning: no '.ldc-list-wrap' elements found"


#### `$$listings <NodeList>`
jQuery references to each '.ldc-listing' element. 

        @$$listings = $ '.ldc-listing'
        if 0 == @$$listings.length then ª "
          #{M}Warning: no '.ldc-listing' elements found"


Initialize the app. 

        @initListings()
        @initListPosts()




Main Init Methods
-----------------


#### `initListings()`
Initialize each '.ldc-listing' element. 

      initListings: ->
        main = @

Record the initial width of each list-wrap. When the window width changes, we 
will check whether each list-wrap’s width has changed. Then run `resizePosts()`,
which sets each list-post to the proper initial height. 

        @$$listings.each ->
          $listingWrap = $ @
          $listingWrap.data 'ldc-width', $listingWrap.width()
          main.resizePosts $listingWrap

Run `resizePosts()` every time a list-wrap element’s width changes. 

        $(window).on 'resize', ->
          main.$$listings.each ->
            $listingWrap = $ @
            if $listingWrap.width() != $listingWrap.data 'ldc-width'
              $listingWrap.data 'ldc-width', $listingWrap.width()
              main.resizePosts $listingWrap




#### `resizePosts()`
Change the height of each listing list-post so that their background-images are 
fully visible. 

      resizePosts: ($listingWrap) ->
        $('.ldc-list-post', $listingWrap).each ->
          $post = $ @
          aspectRatio = $post.attr 'data-ldc-aspect-ratio' or 1
          $post.height $post.width() * aspectRatio




#### `initListPosts()`
Initialize each '.ldc-list-post' element. This must be re-run if new posts are 
added to the DOM. 

      initListPosts: ->
        main = @

Position each list-post absolutely.  
@todo probably delete this and just MOVE posts between 3-column grid and 1-column grid

        #@$$listWraps.each ->
        #  $wrap = $ @
        #  y = 0
        #  $('.ldc-list-post', $wrap).each ->
        #    $post = $ @
        #    $post.css
        #      position: 'absolute'
        #      width: $wrap.width()
        #      top:   y
        #    y += $post.height()
        #  $wrap.height y

Add a click handler to each list-post, Note that we remove the click handler 
first, in case `initListPosts()` is being re-run after new posts were added to 
the DOM. 

        $('.ldc-list-post', @$$listWraps).off 'click', @onListPostClick
        $('.ldc-list-post', @$$listWraps).on  'click', @onListPostClick




#### `onListPostClick()`
A click handler added to all list-post elements by `initListPosts()`. Adds the 
'.ldc-focus' class to the clicked list-post, and removes it from all others. 

      onListPostClick: ->
        $('.ldc-list-wrap .ldc-list-post').removeClass 'ldc-focus'
        $(@).addClass 'ldc-focus'




Boot
----

When the DOM is ready create an instance of Main, which boots the app. 

    $ -> new Main




