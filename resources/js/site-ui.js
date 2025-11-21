// src/js/site-ui.js
import $ from 'jquery'
import 'slick-carousel' // If you're using Slick via npm
import 'jquery-ui/ui/widgets/datepicker' // Optional: If you're using jQuery UI datepicker
import 'ion-rangeslider' // Optional: if you use ionRangeSlider

export const initializeSiteUI = () => {
  $(document).ready(function () {
    // Navbar toggle logic
    $("<span class='clickD'></span>").insertAfter(".navbar-nav li.menu-item-has-children > a");
    $('.navbar-nav li .clickD').on('click', function (e) {
      e.preventDefault();
      const $this = $(this);
      if ($this.next().hasClass('show')) {
        $this.next().removeClass('show');
        $this.removeClass('toggled');
      } else {
        $this.closest('.navbar-nav').find('.sub-menu').removeClass('show');
        $this.closest('.navbar-nav').find('.toggled').removeClass('toggled');
        $this.next().toggleClass('show');
        $this.toggleClass('toggled');
      }
    });

    $(window).on('resize', function () {
      if ($(this).width() < 1025) {
        $('html, document').on('click', function () {
          $('.navbar-nav li .clickD').removeClass('toggled');
          $('.sub-menu').removeClass('show');
        });
        $('.navbar-nav').on('click', function (e) {
          e.stopPropagation();
        });
      }
    }).trigger('resize');

    $(".navbar-toggler").on('click', function () {
      $(".navbar-toggler").toggleClass("open");
      $(".navbar-toggler .stick").toggleClass("open");
      $('body,html').toggleClass("open-nav");
    });

    $(window).on('scroll', function () {
      if ($(window).scrollTop() > 0) {
        $(".main-head").addClass("fixed");
      } else {
        $(".main-head").removeClass("fixed");
      }
    });

    // Initialize sliders, datepickers, FAQs, filters, etc. (cut for brevity)
    // Place the rest of your Slick, datepicker, range slider, FAQ, filter code here

  });
}
