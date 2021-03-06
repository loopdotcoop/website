// Generated by CoffeeScript 1.9.2
(function() {
  var $, Main, ª;

  ª = console.log.bind(console);

  $ = jQuery;

  Main = (function() {
    function Main(config) {
      var M;
      M = "/library/sc/sc-ldc_list.litcoffee Main()\n  ";
      if ('function' !== typeof $) {
        throw Error(M + "No jQuery");
      }
      this.$$listWraps = $('.ldc-list-wrap');
      if (0 === this.$$listWraps.length) {
        ª(M + "Warning: no '.ldc-list-wrap' elements found");
      }
      this.$$carousels = $('.ldc-carousel');
      if (0 === this.$$carousels.length) {
        ª(M + "Warning: no '.ldc-carousel' elements found");
      }
      this.$$listings = $('.ldc-listing');
      if (0 === this.$$listings.length) {
        ª(M + "Warning: no '.ldc-listing' elements found");
      }
      this.initCarousels();
      this.initListings();
      this.initListPosts();
    }

    Main.prototype.initCarousels = function() {
      var main;
      main = this;
      return this.$$carousels.each(function() {
        var $carouselWrap;
        $carouselWrap = $(this);
        return $carouselWrap.removeClass('ldc-preload');
      });
    };

    Main.prototype.initListings = function() {
      var main;
      main = this;
      this.$$listings.each(function() {
        var $listingWrap, width;
        $listingWrap = $(this);
        width = $listingWrap.width();
        $listingWrap.data('ldc-width', width);
        if (808 <= width) {
          main.resizePosts($listingWrap, 3, (width - 24 - 24) / 3);
        } else {
          main.resizePosts($listingWrap, 1, width);
        }
        return $listingWrap.removeClass('ldc-preload');
      });
      return $(window).on('resize', function() {
        return main.$$listings.each(function() {
          var $listingWrap, width;
          $listingWrap = $(this);
          width = $listingWrap.width();
          if (width !== $listingWrap.data('ldc-width')) {
            $listingWrap.data('ldc-width', width);
            if (808 <= width) {
              return main.resizePosts($listingWrap, 3, (width - 24 - 24) / 3);
            } else {
              return main.resizePosts($listingWrap, 1, width);
            }
          }
        });
      });
    };

    Main.prototype.resizePosts = function($listingWrap, cols, width) {
      var colH;
      colH = [0, 0, 0];
      $('.ldc-list-post', $listingWrap).each(function(i) {
        var $post, aspectRatio, height;
        $post = $(this);
        aspectRatio = $post.attr('data-ldc-aspect-ratio' || 1);
        height = width * aspectRatio;
        $post.width(width);
        $post.height(height);
        if (3 === cols) {
          switch (i % 3) {
            case 0:
              $post.css({
                'margin-left': 0,
                'margin-top': colH[0]
              });
              return colH[0] += height + 24;
            case 1:
              $post.css({
                'margin-left': width + 24,
                'margin-top': colH[1]
              });
              return colH[1] += height + 24;
            case 2:
              $post.css({
                'margin-left': width + 24 + width + 24,
                'margin-top': colH[2]
              });
              return colH[2] += height + 24;
          }
        } else {
          return $post.css({
            'margin-left': 0,
            'margin-top': 0
          });
        }
      });
      if (3 === cols) {
        return $listingWrap.height(Math.max(colH[0], colH[1], colH[2])).addClass('ldc-3-col');
      } else {
        return $listingWrap.height('auto').removeClass('ldc-3-col');
      }
    };

    Main.prototype.initListPosts = function() {
      var main;
      main = this;
      $('.ldc-list-post', this.$$listWraps).off('click', this.onListPostClick);
      return $('.ldc-list-post', this.$$listWraps).on('click', this.onListPostClick);
    };

    Main.prototype.onListPostClick = function() {
      $('.ldc-list-wrap .ldc-list-post').removeClass('ldc-focus');
      return $(this).addClass('ldc-focus');
    };

    return Main;

  })();

  $(function() {
    return new Main;
  });

}).call(this);
