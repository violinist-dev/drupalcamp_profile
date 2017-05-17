;(function($) {
  'use strict';
  var windowHeight = $(window).height();
  Drupal.behaviors.heroBlock = {
    attach: function(context) {
      $(context).find('#home').css('height', windowHeight);
    }
  };
})(jQuery);
