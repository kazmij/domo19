var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) 
  isMobile = true;

var overlay = (function() {
  var functions = {
    on: function() {
      $("#overlay").addClass("active");
    },
    off: function() {
      $("#overlay").removeClass("active");
    }
  }
  return {
    on: functions.on,
    off: functions.off
  }
})();

var closet = (function() {
  var settings = {
    count: 0
  }
  var functions = {
    init: function() {
      var count = $("#closet-badge").data("count");
      settings.count = count;
      functions.update();
    },
    add: function() {
      settings.count += 1;
      functions.update();
    },
    remove: function() {
      settings.count -= 1;
      functions.update();
    },
    update: function() {
      $("#closet-badge").html(settings.count);
      $("#mobile-closet-badge").html(settings.count);
      if (settings.count === 0) {
        $("#closet-badge").addClass("hided");
        $("#mobile-closet-badge").addClass("hided");
      } else {
        $("#closet-badge").removeClass("hided");
        $("#mobile-closet-badge").removeClass("hided");
      }
    }
  }
  return {
    init: functions.init,
    add: functions.add,
    remove: functions.remove
  }
})();
var sliders = (function() {
  var settings = {
    count: 0
  }
  var functions = {
    init: function() {
      var count = $("#sliders-badge").data("count");
      settings.count = count;
      functions.update();
    },
    add: function() {
      settings.count += 1;
      functions.update();
    },
    remove: function() {
      settings.count -= 1;
      functions.update();
    },
    update: function() {
      $("#sliders-badge").html(settings.count);
      $("#mobile-sliders-badge").html(settings.count);
      if (settings.count === 0) {
        $("#sliders-badge").addClass("hided");
        $("#mobile-sliders-badge").addClass("hided");
      } else {
        $("#sliders-badge").removeClass("hided");
        $("#mobile-sliders-badge").removeClass("hided");
      }
    }
  }
  return {
    init: functions.init,
    add: functions.add,
    remove: functions.remove
  }
})();

function sameHeight(elements) {
 var max = 0;
 $.each(elements, function(index, element) {
  var height = $(element).height();
  if (max === 0) {
    max = height;
  } else if (height > max) {
    max = height;
  }
 });
  $.each(elements, function(index, element) {
    $(element).height(max);
  });
}
$(window).on("load", function() {
  setTimeout(function() {
    sameHeight($("#start-pack .item"));
    if (!isMobile) {
    $.each($("[data-sameheight]"), function() {
      sameHeight($(this).find("[data-sameheight-item]"));
    });
  }
  }, 1000);

});

$(document).ready(function() {
  closet.init();
  sliders.init();
  
  $(".testimonials-item .button").click(function() {
    var parent = $(this).parents(".content");
    parent.toggleClass("collapsed");
    if (parent.hasClass("collapsed")) {
      $(this).text("Zwiń");
    } else {
      $(this).text("Rozwiń");
    }
  });
  
  $("#to-top").click(function() {
    $("html, body").animate({ scrollTop: 0 });
  });
  
  $("#range, #range2, #filter-range-2").ionRangeSlider({
    type: "double",
    grid: true,
    from: 0,
    to: 4,
    values: ['min', 10, 20, 30, 40, 50, 'max'],
    hide_from_to: true,
    hide_min_max: true,
    grid_num: 2
  }); 
  
  $("#filter-range-1").ionRangeSlider({
    type: "double",
    grid: true,
    from: 0,
    to: 4,
    values: ['min', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 'max'],
    hide_from_to: true,
    hide_min_max: true,
    grid_num: 2
  });
  
  $(".sidebar-box header").on("click", function() {
    $(this).next(".sidebar-box-content").toggleClass("expand");
  });
  
  $('input#search-box-surface-from').inputAutogrow({maxWidth: 50, minWidth: 20, trailingSpace: 5});
  $('input#search-box-surface-to').inputAutogrow({maxWidth: 50, minWidth: 20, trailingSpace: 5});
  $('input#search-box-length').inputAutogrow({maxWidth: 70, minWidth: 35, trailingSpace: 5});
  $('input#search-box-width').inputAutogrow({maxWidth: 70, minWidth: 35, trailingSpace: 5});
  
  $('#filters-surface-from').inputAutogrow({maxWidth: 70, minWidth: 20, trailingSpace: 5});
  $('#filters-surface-to').inputAutogrow({maxWidth: 70, minWidth: 20, trailingSpace: 5});
  $('#filters-length').inputAutogrow({maxWidth: 90, minWidth: 55, trailingSpace: 5});
  $('#filters-width').inputAutogrow({maxWidth: 120, minWidth: 70, trailingSpace: 5});
  
  $('input#search-box-surface-from2').inputAutogrow({maxWidth: 60, minWidth: 45, trailingSpace: 5});
  $('input#search-box-surface-to2').inputAutogrow({maxWidth: 60, minWidth: 45, trailingSpace: 5});
  $('input#search-box-length2').inputAutogrow({maxWidth: 80, minWidth: 55, trailingSpace: 5});
  $('input#search-box-width2').inputAutogrow({maxWidth: 80, minWidth: 55, trailingSpace: 5});

  $("#desktop-menu-button").click(function() {
    if ($(this).hasClass("closed")) {
      $(this).removeClass("closed");
      $("#desktop-header-logo").removeClass("invert");
      $("#desktop-menu").removeClass("active");
      $("#desktop-header-menu-bar").removeClass("shadow");
      overlay.off();
    } else {
      $(this).addClass("closed");
      $("#desktop-header-logo").addClass("invert");
      $("#desktop-menu").addClass("active");
      $("#desktop-header-menu-bar").addClass("shadow");
      overlay.on();
    }
    
  });
  
  
  $("#desktop-search-button").click(function() {
    if ($("#search-box").hasClass("on-page")) {
      $("#search-box").toggleClass("active");
      $("html").animate({scrollTop: 0 });
    } else {
      $("html").animate({scrollTop: $("#search-box").offset().top });
      if ($("#desktop-menu-button").hasClass("closed")) {
        $("#desktop-menu-button").removeClass("closed");
        $("#desktop-header-logo").removeClass("invert");
        $("#desktop-menu").removeClass("active");
        $("#desktop-header-menu-bar").removeClass("shadow");
        overlay.off();
      }
    }
  });
  
  
});

$(window).on("load", function() {
    setTimeout(function() {
      
      var mySwiperWorthAttention = new Swiper ('.projects-swiper-worth-attention', {
      // Optional parameters
      direction: 'horizontal',

      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next-worth-attention',
        prevEl: '.swiper-button-prev-worth-attention',
      },
      slidesPerView: 4,
      spaceBetween: 30,

      breakpoints: {
        767: {
          slidesPerView: 'auto',
          spaceBetween: 20
        },
        991: {
          slidesPerView: 2,
          spaceBetween: 30
        },
        1199: {
          slidesPerView: 3,
          spaceBetween: 30
        },
      }
    });
      
    var mySwiperEspiroProperty = new Swiper ('.project-swiper-espiro-property', {
      // Optional parameters
      direction: 'horizontal',

      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next-espiro-property',
        prevEl: '.swiper-button-prev-espiro-property',
      },
      slidesPerView: 4,
      spaceBetween: 30,

      breakpoints: {
        767: {
          slidesPerView: 'auto',
          spaceBetween: 20
        },
        991: {
          slidesPerView: 2,
          spaceBetween: 30
        },
        1199: {
          slidesPerView: 3,
          spaceBetween: 30
        },
      }
    });
      
    var mySwiperUsefullInformations = new Swiper ('.usefull-informations-swiper', {
      // Optional parameters
      direction: 'horizontal',

      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next-usefull-informations',
        prevEl: '.swiper-button-prev-usefull-informations',
      },
      slidesPerView: 2,
      spaceBetween: 30,
      breakpoints: {
        767: {
          slidesPerView: 'auto',
          spaceBetween: 20
        },
      }
    });
      
      
    var galleryTop = new Swiper('#single-project-gallery-main', {
      spaceBetween: 20,
      autoHeight: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
      
    var galleryThumbs = new Swiper('#single-project-gallery-thumbs', {
      spaceBetween: 20,
      centeredSlides: true,
      slidesPerView: 'auto',
      touchRatio: 0.2,
      slideToClickedSlide: true,
      slidesOffsetBefore: -230,
      breakpoints: {
        1199: {
          slidesOffsetBefore: -175,
        },
        991: {
          slidesOffsetBefore: -58,
        },
        767: {
          slidesOffsetBefore: 0,
        }
    }
    });
      
    galleryTop.controller.control = galleryThumbs;
    galleryThumbs.controller.control = galleryTop;
      
  }, 1000);
  
});


var mobileMenu = (function() {
  var e = $("#mobile-header-menu");
  var functions = {
    init: function() {
      $("#mobile-header-menu-button").on("click", functions.onMenuButtonTap);
      e.find(".with-submenu").on("click", functions.onSubmenuTap);
      e.find(".menu-list .go-back").on("click", functions.goBack);
      $("#mobile-header-search-button, #mobile-search-wide-button").on("click", functions.searchBox); 
    },
    onSubmenuTap: function() {
        e.find(".main-menu").addClass("hided");
        e.find("[data-submenu-name='" + $(this).data("submenu") + "']").addClass("active");
        $("#mobile-header-menu-button").addClass("go-back");
    },
    onMenuButtonTap: function() {
      if ($(this).hasClass("closed")) {
        if ($("#mobile-search-box").hasClass("active")) {
          functions.searchBox();
        } else {
          e.removeClass("active");
          $(this).removeClass("closed");
        }
      //} else if ($(this).hasClass("go-back")) {
      //  console.log("go-back");
      //  e.find(".main-menu").removeClass("hided");
      //  e.find("[data-submenu-name].active").removeClass("active");
      //  $(this).removeClass("go-back");
      } else {
        e.addClass("active");
        $(this).addClass("closed");
      }
    },
    goBack: function() {
      e.find(".main-menu").removeClass("hided");
      e.find("[data-submenu-name].active").removeClass("active");
    },
    searchBox: function() {
      var mbox = $("#mobile-search-box");
      if (mbox.hasClass("active")) {
        mbox.removeClass("active");
        $("#mobile-header-menu-button").removeClass("closed");
      } else {
        if ($("#mobile-header-menu-button").hasClass("closed")) {
          e.removeClass("active");
        } 
        mbox.addClass("active");
        $("#mobile-header-menu-button").addClass("closed");
      }
    }
  };
  return {
    init: functions.init
  }
})();

$(document).ready(function() {
  mobileMenu.init();
});

var tooltip = (function() {
  var functions = {
    init: function() {
      $("body").on("mouseenter", "[data-tooltip]", function() {
        functions.show($(this));
      });
      $("body").on("mouseout", "[data-tooltip]", functions.hide);
    },
    show: function(e) {
      var text = $(e).data("tooltip");
      var tooltipElement = $("<div>");
      tooltipElement.addClass("domo-tooltip");
      tooltipElement.text(text);
      $("body").append(tooltipElement);
      
      var top = $(e).offset().top + $(e).outerHeight() + 10;
      tooltipElement.css('top', top);
      
      var left = $(e).offset().left + ($(e).outerWidth() / 2) - (tooltipElement.outerWidth() / 2) ;
      tooltipElement.css('left', left);
  
    },
    hide: function() {
      $(".domo-tooltip").remove();
    }
  }
  return {
    init: functions.init
  }
})();

$(document).ready(function() {
  tooltip.init();
});


var collapse = (function() {
  var functions = {
    init: function() {
      $(".collapse-box [data-collapse]").on("click", function() {
        $(this).parents("nav").find(".active").removeClass("active");
        $(this).addClass("active");
        $(this).parents(".collapse-box").find("[data-collapse-name].active").removeClass("active");
        $(this).parents(".collapse-box").find("[data-collapse-name='"+ $(this).data('collapse') + "']").addClass("active");
      });
      return true;
    }
  }
  return {
    init: functions.init
  }
})();

$(document).ready(function() {
  collapse.init();
});

$(window).on('scroll', function() {
  if ($(window).scrollTop() > 300) {
    $("body").addClass("scrolled");
  } else {
    $("body").removeClass("scrolled");
  }
});