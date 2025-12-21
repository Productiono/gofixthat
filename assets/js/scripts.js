/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.$window = exports.$doc = exports.$body = exports.$ = void 0;
exports.csGetCookie = csGetCookie;
exports.csSetCookie = csSetCookie;
exports.csThrottleScroll = csThrottleScroll;
exports.docH = exports.csco = void 0;
exports.smartTimer = smartTimer;
exports.wndW = exports.wndH = void 0;
// Create csco object.
var csco = {
  addAction: function addAction(x, y, z) {
    return;
  }
};
exports.csco = csco;
if ('undefined' !== typeof wp && 'undefined' !== typeof wp.hooks) {
  csco.addAction = wp.hooks.addAction;
}

/**
 * Window size
 */
var $ = jQuery;
exports.$ = $;
var $window = $(window);
exports.$window = $window;
var $doc = $(document);
exports.$doc = $doc;
var $body = $('body');
exports.$body = $body;
var wndW = 0;
exports.wndW = wndW;
var wndH = 0;
exports.wndH = wndH;
var docH = 0;
exports.docH = docH;
function csGetWndSize() {
  exports.wndW = wndW = $window.width();
  exports.wndH = wndH = $window.height();
  exports.docH = docH = $doc.height();
}
$window.on('resize load orientationchange', csGetWndSize);
csGetWndSize();

/**
 * Throttle scroll
 * thanks: https://jsfiddle.net/mariusc23/s6mLJ/31/
 */
var csHideOnScrollList = [];
var csDidScroll;
var csLastST = 0;
$window.on('scroll load resize orientationchange', function () {
  if (csHideOnScrollList.length) {
    csDidScroll = true;
  }
});
function csHasScrolled() {
  var ST = $window.scrollTop();
  var type = null;
  if (ST > csLastST) {
    type = 'down';
  } else if (ST < csLastST) {
    type = 'up';
  } else {
    type = 'none';
  }
  if (ST === 0) {
    type = 'start';
  } else if (ST >= docH - wndH) {
    type = 'end';
  }
  csHideOnScrollList.forEach(function (item) {
    if (typeof item === 'function') {
      item(type, ST, csLastST, $window);
    }
  });
  csLastST = ST;
}
setInterval(function () {
  if (csDidScroll) {
    csDidScroll = false;
    window.requestAnimationFrame(csHasScrolled);
  }
}, 250);
function csThrottleScroll(callback) {
  csHideOnScrollList.push(callback);
}

/**
 * In Viewport checker
 */
$.fn.isInViewport = function () {
  var elementTop = $(this).offset().top;
  var elementBottom = elementTop + $(this).outerHeight();
  var viewportTop = $(window).scrollTop();
  var viewportBottom = viewportTop + $(window).height();
  return elementBottom > viewportTop && elementTop < viewportBottom;
};

/**
 * Cookies
 */
function csGetCookie(name) {
  var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
function csSetCookie(name, value) {
  var props = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  props = {
    path: '/'
  };
  if (props.expires instanceof Date) {
    props.expires = props.expires.toUTCString();
  }
  var updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
  for (var optionKey in props) {
    updatedCookie += "; " + optionKey;
    var optionValue = props[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }
  document.cookie = updatedCookie;
}

/*
 * Smart Timer
 */
function smartTimer(milliseconds, oncomplete, oniteration) {
  var startTime,
    obj,
    ms = milliseconds;
  obj = {};
  obj.sleepState = false;
  obj.resume = function () {
    if (1 === obj.status) {
      return;
    }
    obj.status = 1;
    startTime = new Date().getTime();
    obj.timer = setInterval(obj.step, 17);
  };
  obj.pause = function () {
    if (2 === obj.status) {
      return;
    }
    obj.status = 2;
    ms = obj.step();
    clearInterval(obj.timer);
  };
  obj.sleep = function (state) {
    obj.sleepState = state;
    if (state) {
      obj.pause();
    } else {
      obj.resume();
    }
  };
  obj.step = function () {
    var now = Math.max(0, ms - (new Date().getTime() - startTime));
    if ('function' === typeof oniteration) {
      var progress = 100 - 100 / milliseconds * now;
      oniteration(progress);
    }
    if (now == 0) {
      clearInterval(obj.timer);
      obj.resume = function () {};
      if (oncomplete) {
        oncomplete();
      }
    }
    return now;
  };
  obj.resume();
  return obj;
}

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
__webpack_require__(3);
__webpack_require__(4);
__webpack_require__(5);
__webpack_require__(6);
__webpack_require__(7);
__webpack_require__(8);
__webpack_require__(9);
__webpack_require__(10);
__webpack_require__(11);
__webpack_require__(12);
__webpack_require__(13);
__webpack_require__(14);
__webpack_require__(15);
__webpack_require__(16);
__webpack_require__(17);
module.exports = __webpack_require__(18);


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Carousel */

(function () {
  function initCarousel() {
    /**
     * Carousel
     */
    (0, _utility.$)('.mbf-gallery-layout-carousel .woocommerce-product-gallery__wrapper').each(function (indexBlock, objectBlock) {
      (0, _utility.$)(objectBlock).imagesLoaded(function (instance) {
        var objectCarousels = [];
        (0, _utility.$)(instance.elements).each(function (index, el) {
          var settings = {
            rightToLeft: _utility.$body.hasClass('rtl') ? true : false,
            wrapAround: true,
            autoPlay: false,
            prevNextButtons: true,
            pageDots: true,
            resize: true,
            adaptiveHeight: true,
            cellAlign: 'left',
            pauseAutoPlayOnHover: false
          };

          // Set object of carousel.
          objectCarousels[index] = (0, _utility.$)(el);

          // Initialize Flickity.
          objectCarousels[index].flickity(settings);
        });
      });
    });
    (0, _utility.$)(document).on('click', '.mbf-single-product-summary-wrap .variations .variable-item, .mbf-single-product-summary-wrap .variations .reset_variations', function (e) {
      (0, _utility.$)('.mbf-gallery-layout-carousel .woocommerce-product-gallery__wrapper').flickity('select', 0);
    });
    (0, _utility.$)('.mbf-single-product-summary-wrap .variations_form').on('woocommerce_update_variation_values', function () {
      (0, _utility.$)('.mbf-gallery-layout-carousel .woocommerce-product-gallery__wrapper').flickity('select', 0);
    });
  }
  initCarousel();
})();

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Clear Hash Click */

(function () {
  (0, _utility.$)(document).on('click', '.menu-item-without-link[href="#"]', function (e) {
    e.preventDefault();
  });
})();

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function () {
  (0, _utility.$)(document).on('click', '.woocommerce-loop-product__color_variation', function (e) {
    e.preventDefault();
    var $product_img = (0, _utility.$)(this).closest('.product, .wc-block-grid__product').find('.attachment-woocommerce_thumbnail');
    (0, _utility.$)(this).siblings().removeClass('is-active');
    (0, _utility.$)(this).addClass('is-active');
    if ((0, _utility.$)(this).is('.is-variation-default')) {
      (0, _utility.$)($product_img).siblings('.woocommerce-product-variation-image').remove();
      return;
    }
    var selectColor = (0, _utility.$)(this).data('color');
    var Variations = (0, _utility.$)(this).parent().data('product_variations');
    if (!selectColor) {
      return;
    }
    if (!Array.isArray(Variations) || !Variations) {
      return;
    }
    var variation = null;
    for (var index = 0; index < Variations.length; index++) {
      variation = Variations[index];
      if (_typeof(variation) !== 'object' || !variation.hasOwnProperty('attributes') || !variation.attributes.hasOwnProperty('attribute_pa_color')) {
        continue;
      }
      if (selectColor === variation.attributes.attribute_pa_color) {
        if ($product_img) {
          (0, _utility.$)($product_img).siblings('.woocommerce-product-variation-image').remove();
          var $variation_img = (0, _utility.$)('<img class="woocommerce-product-variation-image"/>').insertBefore($product_img);
          if (variation.hasOwnProperty('image') && variation.image.hasOwnProperty('thumb_src') && variation.image.thumb_src) {
            $variation_img.attr('src', variation.image.thumb_src);
          }
          if (variation.hasOwnProperty('image') && variation.image.hasOwnProperty('sizes') && variation.image.sizes) {
            $variation_img.attr('sizes', variation.image.sizes);
          }
          if (variation.hasOwnProperty('image') && variation.image.hasOwnProperty('srcset') && variation.image.srcset) {
            $variation_img.attr('srcset', variation.image.srcset);
          }
          if (variation.hasOwnProperty('image') && variation.image.hasOwnProperty('title') && variation.image.title) {
            $variation_img.attr('src', variation.image.title);
          }
          if (variation.hasOwnProperty('image') && variation.image.hasOwnProperty('alt') && variation.image.alt) {
            $variation_img.attr('src', variation.image.alt);
          }
          $variation_img.addClass('is-visible');
        }
        break;
      }
    }
    e.preventDefault();
  });
})();

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Header Smart Streatch */

(function () {
  (0, _utility.$)(document).on('header-smart-stretch-scroll-sticky-scroll-init', function (event, headerParams) {
    (0, _utility.$)(window).scroll(function () {
      var scrolled = (0, _utility.$)(window).scrollTop();
      headerParams.headerSmartPosition = headerParams.headerSmart.length > 0 ? headerParams.headerSmart.offset().top : 0;
      if (scrolled > headerParams.smartStart + headerParams.scrollPoint + 10 && scrolled > headerParams.scrollPrev) {
        if (scrolled > headerParams.smartStart + headerParams.headerLargeHeight + 200) {
          (0, _utility.$)(document).trigger('sticky-nav-hide', headerParams);
        }
      } else {
        if (headerParams.scrollUpAmount >= headerParams.scrollPoint || scrolled === 0) {
          (0, _utility.$)(document).trigger('sticky-nav-visible', headerParams);
        }
      }
      if (scrolled > headerParams.smartStart + headerParams.headerLargeHeight) {
        (0, _utility.$)(document).trigger('nav-stick', headerParams);
      } else if (headerParams.headerSmartPosition <= headerParams.smartStart) {
        (0, _utility.$)(document).trigger('nav-unstick', headerParams);
      }
      if (scrolled < headerParams.scrollPrev) {
        headerParams.scrollUpAmount += headerParams.scrollPrev - scrolled;
      } else {
        headerParams.scrollUpAmount = 0;
      }
      if (headerParams.wpAdminBar.length > 0 && _utility.wndW <= 600 && scrolled >= headerParams.wpAdminBarHeight) {
        (0, _utility.$)(document).trigger('adminbar-mobile-scrolled', headerParams);
      } else {
        (0, _utility.$)(document).trigger('adminbar-mobile-no-scrolled', headerParams);
      }
      headerParams.scrollPrev = scrolled;
    });
  });
})();

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Hover Header Menu */

(function () {
  (0, _utility.$)('.mbf-header__nav-inner > .menu-item').hover(function () {
    (0, _utility.$)(this).siblings('.menu-item').addClass('mbf-no-focus');
  }, function () {
    (0, _utility.$)(this).siblings('.menu-item').removeClass('mbf-no-focus');
  });
})();

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
if ('undefined' === typeof window.load_more_query) {
  window.load_more_query = [];
}

/**
 * Get next posts
 */
function mbf_ajax_get_posts(object) {
  var container = (0, _utility.$)(object).closest('.mbf-posts-area');
  var settings = (0, _utility.$)(object).data('settings');
  var page = (0, _utility.$)(object).data('page');
  (0, _utility.$)(object).data('loading', true);

  // Set button text to Load More.
  (0, _utility.$)(object).text(settings.translation.loading);
  var data = {
    action: 'mbf_ajax_load_more',
    page: page,
    posts_per_page: settings.posts_per_page,
    query_data: settings.query_data,
    attributes: settings.attributes,
    options: settings.options,
    _ajax_nonce: settings.nonce
  };

  // Request Url.
  var mbf_pagination_url;
  if ('ajax_restapi' === settings.type) {
    mbf_pagination_url = settings.rest_url;
  } else {
    mbf_pagination_url = settings.url;
  }

  // Send Request.
  _utility.$.post(mbf_pagination_url, data, function (res) {
    if (res.success) {
      // Get the posts.
      var data = (0, _utility.$)(res.data.content);
      if (data.length) {
        var cscoAppendEnd = function cscoAppendEnd() {
          // WP Post Load trigger.
          (0, _utility.$)(document.body).trigger('post-load');

          // Reinit Facebook widgets.
          if ((0, _utility.$)('#fb-root').length && 'object' === (typeof FB === "undefined" ? "undefined" : _typeof(FB))) {
            FB.XFBML.parse();
          }

          // Set button text to Load More.
          (0, _utility.$)(object).text(settings.translation.load_more);

          // Increment a page.
          page = page + 1;
          (0, _utility.$)(object).data('page', page);

          // Set the loading state.
          (0, _utility.$)(object).data('loading', false);
        };
        if ((0, _utility.$)(container).find('.mbf-posts-area__main').hasClass('woocommerce')) {
          (0, _utility.$)(container).find('.mbf-posts-area__main .products').append(data);
        } else {
          (0, _utility.$)(container).find('.mbf-posts-area__main').append(data);
        }
        cscoAppendEnd();
      }

      // Remove Button on Posts End.
      if (res.data.posts_end || !data.length) {
        // Remove Load More button.
        (0, _utility.$)(object).remove();
      }
    } else {
      // console.log(res);
    }
  }).fail(function (xhr, textStatus, e) {
    // console.log(xhr.responseText);
  });
}

/**
 * Initialization Load More
 */
function mbf_load_more_init(infinite) {
  (0, _utility.$)('.mbf-posts-area').each(function () {
    if ((0, _utility.$)(this).data('init')) {
      return false;
    }
    var mbf_ajax_settings;
    var archive_data = (0, _utility.$)(this).data('posts-area');
    if (archive_data) {
      mbf_ajax_settings = JSON.parse(window.atob(archive_data));
    } else if ('undefined' !== typeof mbf_ajax_pagination && 'undefined' === typeof archive_data) {
      mbf_ajax_settings = mbf_ajax_pagination;
    }
    if (mbf_ajax_settings) {
      if (!infinite && mbf_ajax_settings.infinite_load) {
        return false;
      }

      // Add load more button.
      (0, _utility.$)(this).append('<div class="mbf-posts-area__pagination"><button class="mbf-load-more">' + mbf_ajax_settings.translation.load_more + '</button></div>');

      // Set load more settings.
      (0, _utility.$)(this).find('.mbf-load-more').data('settings', mbf_ajax_settings);
      (0, _utility.$)(this).find('.mbf-load-more').data('page', 2);
      (0, _utility.$)(this).find('.mbf-load-more').data('loading', false);
      (0, _utility.$)(this).find('.mbf-load-more').data('scrollHandling', {
        allow: _utility.$.parseJSON(mbf_ajax_settings.infinite_load),
        delay: 400
      });
    }
    (0, _utility.$)(this).data('init', true);
  });
}
mbf_load_more_init(true);

// On Scroll Event.
(0, _utility.$)(window).scroll(function () {
  (0, _utility.$)('.mbf-posts-area .mbf-load-more').each(function () {
    var loading = (0, _utility.$)(this).data('loading');
    var scrollHandling = (0, _utility.$)(this).data('scrollHandling');
    if ('undefined' === typeof scrollHandling) {
      return;
    }
    if ((0, _utility.$)(this).length && !loading && scrollHandling.allow) {
      scrollHandling.allow = false;
      (0, _utility.$)(this).data('scrollHandling', scrollHandling);
      var object = this;
      setTimeout(function () {
        var scrollHandling = (0, _utility.$)(object).data('scrollHandling');
        if ('undefined' === typeof scrollHandling) {
          return;
        }
        scrollHandling.allow = true;
        (0, _utility.$)(object).data('scrollHandling', scrollHandling);
      }, scrollHandling.delay);
      var offset = (0, _utility.$)(this).offset().top - (0, _utility.$)(window).scrollTop();
      if (4000 > offset) {
        mbf_ajax_get_posts(this);
      }
    }
  });
});

// On Click Event.
(0, _utility.$)('body').on('click', '.mbf-load-more', function () {
  var loading = (0, _utility.$)(this).data('loading');
  if (!loading) {
    mbf_ajax_get_posts(this);
  }
});

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Woocommerce Myaccount Tabs */

(function () {
  (0, _utility.$)('.woocommerce-account-no-logged .woocommerce-MyAccount-navigation a').click(function (e) {
    (0, _utility.$)(this).parent().addClass('is-active');
    (0, _utility.$)(this).parent().siblings().removeClass('is-active');
    if ('#register' === (0, _utility.$)(this).attr('href')) {
      (0, _utility.$)('.woocommerce-form-login').hide();
      (0, _utility.$)('.woocommerce-form-register').show();
    } else {
      (0, _utility.$)('.woocommerce-form-login').show();
      (0, _utility.$)('.woocommerce-form-register').hide();
    }
    e.preventDefault();
  });
})();

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Navigation */

var cscoNavigation = {};
(function () {
  var $this;
  cscoNavigation = {
    /** Initialize */
    init: function init(e) {
      if ((0, _utility.$)('body').hasClass('wp-admin')) {
        return;
      }
      (0, _utility.$)(document).trigger('header-init');
      $this = cscoNavigation;

      // Init events.
      $this.events(e);
    },
    /** Events */
    events: function events(e) {
      // DOM Load
      document.addEventListener('DOMContentLoaded', function (e) {
        $this.smartLevels(e);
        $this.adaptTablet(e);
        $this.stickyScroll(e);
        $this.headerClassesChange(e);
      });
      // Resize
      window.addEventListener('resize', function (e) {
        $this.smartLevels(e);
        $this.adaptTablet(e);
        $this.stickyScroll(e);
      });
    },
    /** Smart multi-Level menu */
    smartLevels: function smartLevels(e) {
      var windowWidth = _utility.$window.width();

      // Reset Calc.
      (0, _utility.$)('.mbf-header__nav-inner li').removeClass('mbf-sm__level');
      (0, _utility.$)('.mbf-header__nav-inner li').removeClass('mbf-sm-position-left mbf-sm-position-right');
      (0, _utility.$)('.mbf-header__nav-inner li .sub-menu').removeClass('mbf-mm__position-init');

      // Set Settings.
      (0, _utility.$)('.mbf-header__nav-inner > li.menu-item').not('.mbf-mm').each(function (index, parent) {
        var position = 'mbf-sm-position-right'; //right
        var objPrevWidth = 0;
        (0, _utility.$)(parent).find('.sub-menu').each(function (index, el) {
          // Reset child levels.
          (0, _utility.$)(el).parent().next('li').addClass('mbf-sm__level');
          if ((0, _utility.$)(el).parent().hasClass('mbf-sm__level')) {
            (0, _utility.$)(el).parent().removeClass('mbf-mm-level');
            position = 'mbf-sm-position-right'; //right
            objPrevWidth = 0;
          }

          // Find out position items.
          var offset = (0, _utility.$)(el).offset();
          var objOffset = offset.left;
          if ('mbf-sm-position-right' === position && (0, _utility.$)(el).outerWidth() + objOffset > windowWidth) {
            position = 'mbf-sm-position-left';
          }
          if ('mbf-sm-position-left' === position && objOffset - ((0, _utility.$)(el).outerWidth() + objPrevWidth) < 0) {
            position = 'mbf-sm-position-right'; //right
          }

          objPrevWidth = (0, _utility.$)(el).outerWidth();
          (0, _utility.$)(el).addClass('mbf-sm-position-init').parent().addClass(position);
        });
      });
    },
    /** Adapting nav bar for tablet */
    adaptTablet: function adaptTablet(e) {
      // Click outside.
      (0, _utility.$)(document).on('touchstart', function (e) {
        if (!(0, _utility.$)(e.target).closest('.mbf-header__nav-inner').length) {
          (0, _utility.$)('.mbf-header__nav-inner .menu-item-has-children').removeClass('submenu-visible');
        } else {
          (0, _utility.$)(e.target).parents('.menu-item').siblings().find('.menu-item').removeClass('submenu-visible');
          (0, _utility.$)(e.target).parents('.menu-item').siblings().closest('.menu-item').removeClass('submenu-visible');
        }
      });
      (0, _utility.$)('.mbf-header__nav-inner .menu-item-has-children').each(function (e) {
        // Reset class.
        (0, _utility.$)(this).removeClass('submenu-visible');

        // Remove expanded.
        (0, _utility.$)(this).find('> a > .expanded').remove();

        // Add a caret.
        if ('ontouchstart' in document.documentElement) {
          (0, _utility.$)(this).find('> a').append('<span class="expanded"></span>');
        }

        // Check touch device.
        (0, _utility.$)(this).addClass('ontouchstart' in document.documentElement ? 'touch-device' : '');
        (0, _utility.$)('> a .expanded', this).on('touchstart', function (e) {
          e.preventDefault();
          (0, _utility.$)(this).closest('.menu-item-has-children').toggleClass('submenu-visible');
        });
        if ('#' === (0, _utility.$)('> a', this).attr('href')) {
          (0, _utility.$)('> a', this).on('touchstart', function (e) {
            e.preventDefault();
            if (!(0, _utility.$)(e.target).hasClass('expanded')) {
              (0, _utility.$)(this).closest('.menu-item-has-children').toggleClass('submenu-visible');
            }
          });
        }
      });
    },
    /** Make nav bar sticky */
    stickyScroll: function stickyScroll(e) {
      var headerParams = {
        // Get css variables.
        'headerLargeHeight': parseInt(getComputedStyle(document.documentElement).getPropertyValue('--mbf-header-initial-height')),
        'headerCompactHeight': parseInt(getComputedStyle(document.documentElement).getPropertyValue('--mbf-header-height')),
        // Get header elements.
        'headerSmart': (0, _utility.$)('.mbf-navbar-smart-enabled .mbf-header, .mbf-navbar-sticky-enabled .mbf-header'),
        'wpAdminBar': (0, _utility.$)('#wpadminbar'),
        'headerBefore': (0, _utility.$)('.mbf-header-before'),
        'headerStretch': (0, _utility.$)('.mbf-navbar-smart-enabled .mbf-header-stretch'),
        'wpAdminBarHeight': null,
        // Set values to hide.
        'smartStart': null,
        'scrollPoint': 200,
        'scrollPrev': 200,
        'scrollUpAmount': 0,
        'headerSmartPosition': 0
      };

      // Adminbar sizes.

      if (headerParams.wpAdminBar.length > 0) {
        headerParams.wpAdminBarHeight = headerParams.wpAdminBar.outerHeight();
      } else {
        headerParams.wpAdminBarHeight = 0;
      }

      // Header smart start position.

      if (headerParams.headerBefore.length > 0) {
        headerParams.smartStart = headerParams.headerBefore.offset().top;
      } else {
        if (headerParams.headerSmart.length > 0) {
          headerParams.smartStart = headerParams.headerSmart.offset().top + headerParams.wpAdminBarHeight;
        } else {
          headerParams.smartStart = headerParams.wpAdminBarHeight;
        }
      }
      if (headerParams.headerStretch.length > 0) {
        (0, _utility.$)(document).trigger('header-smart-stretch-scroll-sticky-scroll-init', headerParams);
      } else {
        (0, _utility.$)(window).scroll(function () {
          var scrolled = (0, _utility.$)(window).scrollTop();
          headerParams.headerSmartPosition = headerParams.headerSmart.length > 0 ? headerParams.headerSmart.offset().top : 0;
          if (scrolled > headerParams.smartStart + headerParams.scrollPoint + 10 && scrolled > headerParams.scrollPrev) {
            if (scrolled > headerParams.smartStart + headerParams.headerCompactHeight + 200) {
              (0, _utility.$)(document).trigger('sticky-nav-hide', headerParams);
            }
          } else {
            if (headerParams.scrollUpAmount >= headerParams.scrollPoint || scrolled === 0) {
              (0, _utility.$)(document).trigger('sticky-nav-visible', headerParams);
            }
          }
          if (headerParams.headerSmart.length > 0) {
            if (scrolled > headerParams.smartStart + headerParams.headerCompactHeight) {
              (0, _utility.$)(document).trigger('nav-stick', headerParams);
            } else if (headerParams.headerSmartPosition <= headerParams.smartStart) {
              (0, _utility.$)(document).trigger('nav-unstick', headerParams);
            }
          }
          if (scrolled < headerParams.scrollPrev) {
            headerParams.scrollUpAmount += headerParams.scrollPrev - scrolled;
          } else {
            headerParams.scrollUpAmount = 0;
          }
          if (headerParams.wpAdminBar.length > 0 && _utility.wndW <= 600 && scrolled >= headerParams.wpAdminBarHeight) {
            (0, _utility.$)(document).trigger('adminbar-mobile-scrolled', headerParams);
          } else {
            (0, _utility.$)(document).trigger('adminbar-mobile-no-scrolled', headerParams);
          }
          headerParams.scrollPrev = scrolled;
        });
      }
    },
    /** Change header classes on triggers */
    headerClassesChange: function headerClassesChange(e) {
      (0, _utility.$)(document).on("sticky-nav-visible", function (event, headerParams) {
        headerParams.headerSmart.addClass('mbf-header-smart-visible');
      });
      (0, _utility.$)(document).on("sticky-nav-hide", function (event, headerParams) {
        headerParams.headerSmart.removeClass("mbf-header-smart-visible");
      });
      (0, _utility.$)(document).on("nav-stick", function (event, headerParams) {
        headerParams.headerSmart.addClass('mbf-scroll-sticky');
      });
      (0, _utility.$)(document).on("nav-unstick", function (event, headerParams) {
        headerParams.headerSmart.removeClass('mbf-scroll-sticky').removeClass('mbf-header-smart-visible');
      });
      (0, _utility.$)(document).on("adminbar-mobile-scrolled", function (event, headerParams) {
        _utility.$body.addClass('mbf-adminbar-mobile-scrolled');
      });
      (0, _utility.$)(document).on("adminbar-mobile-no-scrolled", function (event, headerParams) {
        _utility.$body.removeClass('mbf-adminbar-mobile-scrolled');
      });
    }
  };
})();

// Initialize.
cscoNavigation.init();

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Offcanvas */

(function () {
  (0, _utility.$)('.mbf-header__offcanvas-toggle, .mbf-site-overlay, .mbf-offcanvas__toggle').on('click', function (e) {
    e.preventDefault();

    // Transition.
    if (!_utility.$body.hasClass('mbf-offcanvas-active')) {
      _utility.$body.addClass('mbf-offcanvas-transition');
    } else {
      setTimeout(function () {
        _utility.$body.removeClass('mbf-offcanvas-transition');
      }, 400);
    }

    // Toogle offcanvas.
    _utility.$body.toggleClass('mbf-offcanvas-active');
  });
  var focusOffcanvasSearchTimeout;
  (0, _utility.$)('.mbf-offcanvas__search-toggle').click(function (e) {
    if (!(0, _utility.$)('.mbf-offcanvas__search').is(":visible")) {
      focusOffcanvasSearchTimeout = setTimeout(function () {
        (0, _utility.$)('.mbf-offcanvas__search .mbf-offcanvas__search-input').focus();
      }, 300);
    } else {
      clearTimeout(focusOffcanvasSearchTimeout);
    }
    (0, _utility.$)('.mbf-offcanvas__search').stop().slideToggle();
    e.preventDefault();
  });
})();

/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Parallax */

(function () {
  if ('undefined' !== typeof jarallax) {
    jarallax(document.querySelectorAll('.is-style-mbf-cover-featured'), {
      speed: 0.85,
      imgPosition: '50% 10%',
      imgElement: '.wp-block-cover__image-background'
    });
    jarallax(document.querySelectorAll('.is-style-mbf-cover-promo'), {
      speed: 0.85,
      imgPosition: '50% 0%',
      imgElement: '.wp-block-cover__image-background'
    });
    jarallax(document.querySelectorAll('.is-style-mbf-cover-landscape'), {
      speed: 0.85,
      imgPosition: '50% 30%',
      imgElement: '.wp-block-cover__image-background'
    });
    jarallax(document.querySelectorAll('.is-style-mbf-cover-wide'), {
      speed: 0.85,
      imgPosition: '50% 40%',
      imgElement: '.wp-block-cover__image-background'
    });
    jarallax(document.querySelectorAll('.mbf-entry__media-inner'), {
      speed: 0.85,
      imgPosition: '50% 50%',
      imgElement: 'img'
    });
  }
})();

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Responsive Embeds */

(function () {
  /**
   * Add max-width & max-height to <iframe> elements, depending on their width & height props.
   */
  function initResponsiveEmbeds() {
    var proportion, parentWidth;

    // Loop iframe elements.
    (0, _utility.$)('.entry-content').find('iframe').each(function (index, iframe) {
      // Don't handle if the parent automatically resizes itself.
      if ((0, _utility.$)(iframe).closest('div').is('[data-video-start], [data-video-end]')) {
        return;
      }
      // Only continue if the iframe has a width & height defined.
      if (iframe.width && iframe.height) {
        // Calculate the proportion/ratio based on the width & height.
        proportion = parseFloat(iframe.width) / parseFloat(iframe.height);
        // Get the parent element's width.
        parentWidth = parseFloat(window.getComputedStyle(iframe.parentElement, null).width.replace('px', ''));
        // Set the max-width & height.
        iframe.style.maxWidth = '100%';
        iframe.style.maxHeight = Math.round(parentWidth / proportion).toString() + 'px';
      }
    });
  }

  // Document ready.
  _utility.$doc.ready(function () {
    initResponsiveEmbeds();
  });

  // Post load.
  _utility.$body.on('post-load', function () {
    initResponsiveEmbeds();
  });

  // Document resize.
  _utility.$window.on('resize', function () {
    initResponsiveEmbeds();
  });

  // Run on initial load.
  initResponsiveEmbeds();
})();

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Color Scheme Toogle */

var cscoDarkMode = {};
(function () {
  var $this;
  cscoDarkMode = {
    init: function init(e) {
      $this = cscoDarkMode;
      $this.initMode(e);
      window.matchMedia('(prefers-color-scheme: dark)').addListener(function (e) {
        $this.initMode(e);
      });
      document.querySelectorAll('.mbf-site-scheme-toggle').forEach(function (toggle) {
        toggle.onclick = function () {
          if ('dark' === document.getElementsByTagName('body')[0].getAttribute('data-scheme')) {
            $this.changeScheme('light', true);
          } else {
            $this.changeScheme('dark', true);
          }
        };
      });
    },
    initMode: function initMode(e) {
      var siteScheme = false;
      switch (csLocalize.siteSchemeMode) {
        case 'dark':
          siteScheme = 'dark';
          break;
        case 'light':
          siteScheme = 'light';
          break;
        case 'system':
          siteScheme = 'auto';
          break;
      }
      if (csLocalize.siteSchemeToogle) {
        if ('light' === (0, _utility.csGetCookie)('_color_schema')) {
          siteScheme = 'light';
        }
        if ('dark' === (0, _utility.csGetCookie)('_color_schema')) {
          siteScheme = 'dark';
        }
      }
      if (siteScheme && siteScheme !== document.getElementsByTagName('body')[0].getAttribute('data-scheme')) {
        $this.changeScheme(siteScheme, false);
      }
    },
    changeScheme: function changeScheme(siteScheme, cookie) {
      document.getElementsByTagName('body')[0].classList.add('mbf-scheme-toggled');
      document.getElementsByTagName('body')[0].setAttribute('data-scheme', siteScheme);
      if (cookie) {
        (0, _utility.csSetCookie)('_color_schema', siteScheme, {
          expires: 2592000
        });
      }
      setTimeout(function () {
        document.getElementsByTagName('body')[0].classList.remove('mbf-scheme-toggled');
      }, 100);
    }
  };
})();
cscoDarkMode.init();

/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Search Dropdown */

(function () {
  var focusSearchTimeout;
  (0, _utility.$)('.mbf-header__search-toggle').click(function (e) {
    if (!(0, _utility.$)('.mbf-search').is(":visible")) {
      focusSearchTimeout = setTimeout(function () {
        (0, _utility.$)('.mbf-search .mbf-search__input').focus();
      }, 300);
    } else {
      clearTimeout(focusSearchTimeout);
    }
    (0, _utility.$)('.mbf-search').stop().slideToggle();
    e.preventDefault();
  });
})();

/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Shop Minicart */

(function () {
  (0, _utility.$)(document).on('click', '.mbf-shop-minicart__toggle, .mbf-shop-minicart-overlay, .mbf-header__cart', function (e) {
    // Transition.
    if (!_utility.$body.hasClass('mbf-shop-minicart-active')) {
      _utility.$body.addClass('mbf-shop-minicart-transition');
    } else {
      setTimeout(function () {
        _utility.$body.removeClass('mbf-shop-minicart-transition');
      }, 400);
    }

    // Toogle minicart.
    _utility.$body.toggleClass('mbf-shop-minicart-active');
    e.preventDefault();
  });
})();

/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Shop Offcanvas */

(function () {
  (0, _utility.$)('.mbf-shop-sidebar-overlay, .mbf-shop-sidebar__toggle, .woocommerce-products-wrap__sidebar-toggle').on('click', function (e) {
    e.preventDefault();

    // Transition.
    if (!_utility.$body.hasClass('mbf-shop-sidebar-active')) {
      _utility.$body.addClass('mbf-shop-sidebar-transition');
    } else {
      setTimeout(function () {
        _utility.$body.removeClass('mbf-shop-sidebar-transition');
      }, 400);
    }

    // Toogle offcanvas.
    _utility.$body.toggleClass('mbf-shop-sidebar-active');
  });
})();

/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Sticky Sidebar */

(function () {
  var stickyElementsSmart = [],
    stickyElements = [];
  stickyElementsSmart.push('.mbf-navbar-smart-enabled.mbf-stick-to-top .mbf-single-product .entry-summary');
  stickyElementsSmart.push('.mbf-sticky-sidebar-enabled.mbf-navbar-smart-enabled.mbf-stick-to-top .mbf-sidebar__inner');
  stickyElementsSmart.push('.mbf-sticky-sidebar-enabled.mbf-navbar-smart-enabled.mbf-stick-last .mbf-sidebar__inner .widget:last-child');
  stickyElements.push('.mbf-navbar-sticky-enabled.mbf-stick-to-top .mbf-single-product .entry-summary');
  stickyElements.push('.mbf-sticky-sidebar-enabled.mbf-navbar-sticky-enabled.mbf-stick-to-top .mbf-sidebar__inner');
  stickyElements.push('.mbf-sticky-sidebar-enabled.mbf-navbar-sticky-enabled.mbf-stick-last .mbf-sidebar__inner .widget:last-child');
  _utility.$doc.ready(function () {
    var headerStick = (0, _utility.$)('.mbf-header'),
      wpAdminBar = (0, _utility.$)('#wpadminbar'),
      headerStickHeight = headerStick.outerHeight(),
      wpAdminBarHeight = wpAdminBar.outerHeight(),
      headerStretch = (0, _utility.$)('.mbf-header-stretch'),
      headerStretchHeight = headerStretch.outerHeight(),
      allHeight = (headerStickHeight || 0) + (wpAdminBarHeight || 0) + 20,
      windowWidth = (0, _utility.$)(window).width();

    // Sticky sidebar for mozilla.
    if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
      stickyElementsSmart.push('.mbf-sticky-sidebar-enabled.mbf-stick-to-bottom .mbf-sidebar__inner');
      stickyElements.push('.mbf-sticky-sidebar-enabled.mbf-stick-to-bottom .mbf-sidebar__inner');
    }

    // Join elements.
    stickyElementsSmart = stickyElementsSmart.join(',');
    stickyElements = stickyElements.join(',');

    // Sticky nav visible.
    _utility.$doc.on('sticky-nav-visible', function () {
      headerStickHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--mbf-header-height'));
      if (headerStretchHeight) {
        allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      } else {
        allHeight = (headerStickHeight || 0) + (wpAdminBarHeight || 0) + 20;
      }
      (0, _utility.$)(stickyElementsSmart).css('top', allHeight + 'px');
    });

    // Sticky nav hide.
    _utility.$doc.on('sticky-nav-hide', function () {
      headerStickHeight = 0;
      allHeight = (headerStickHeight || 0) + (wpAdminBarHeight || 0) + 20;
      (0, _utility.$)(stickyElementsSmart).css('top', allHeight + 'px');
    });
    _utility.$doc.on('stretch-nav-to-small', function () {
      headerStretchHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--mbf-header-height'));
      if (headerStretchHeight) {
        allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      } else {
        allHeight = (headerStickHeight || 0) + (wpAdminBarHeight || 0) + 20;
      }
      if (headerStretch.hasClass("mbf-scroll-sticky") && !headerStretch.hasClass("mbf-scroll-active")) {
        (0, _utility.$)(stickyElementsSmart).css('top', allHeight + 'px');
      }
    });
    _utility.$doc.on('stretch-nav-to-big', function () {
      headerStretchHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--mbf-header-initial-height'));
    });

    // Add top style
    if (_utility.$body.hasClass('mbf-navbar-smart-enabled') && windowWidth >= 1020) {
      if (headerStretchHeight) {
        allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      } else {
        allHeight = (headerStickHeight || 0) + (wpAdminBarHeight || 0) + 20;
      }
      (0, _utility.$)(stickyElementsSmart).css('top', allHeight + 'px');
    } else if (_utility.$body.hasClass('mbf-navbar-sticky-enabled') && windowWidth >= 1020) {
      if (headerStretchHeight) {
        allHeight = (headerStretchHeight || 0) + (wpAdminBarHeight || 0) + 20;
      } else {
        allHeight = (headerStickHeight || 0) + (wpAdminBarHeight || 0) + 20;
      }
      (0, _utility.$)(stickyElements).css('top', allHeight + 'px');
    }

    // Remove top style rafter resize
    _utility.$window.resize(function () {
      var windowWidthResize = _utility.$window.width();
      if (windowWidthResize < 1020) {
        (0, _utility.$)(stickyElements).removeAttr('style');
        (0, _utility.$)(stickyElementsSmart).removeAttr('style');
      }
    });
  });
})();

/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _utility = __webpack_require__(0);
/** ----------------------------------------------------------------------------
 * Widget Nav Menu */

(function () {
  _utility.$.fn.responsiveNav = function () {
    this.removeClass('menu-item-expanded');
    if (this.prev().hasClass('submenu-visible')) {
      this.prev().removeClass('submenu-visible').slideUp(350);
      this.parent().removeClass('menu-item-expanded');
    } else {
      this.parent().parent().find('.menu-item .sub-menu').removeClass('submenu-visible').slideUp(350);
      this.parent().parent().find('.menu-item-expanded').removeClass('menu-item-expanded');
      this.prev().toggleClass('submenu-visible').hide().slideToggle(350);
      this.parent().toggleClass('menu-item-expanded');
    }
  };

  //
  // Navigation Menu Widget
  //
  (0, _utility.$)(document).ready(function (e) {
    (0, _utility.$)('.widget_nav_menu .menu-item-has-children').each(function (e) {
      // Add a caret.
      (0, _utility.$)(this).append('<span></span>');

      // Fire responsiveNav() when clicking a caret.
      (0, _utility.$)('> span', this).on('click', function (e) {
        e.preventDefault();
        (0, _utility.$)(this).responsiveNav();
      });

      // Fire responsiveNav() when clicking a parent item with # href attribute.
      if ('#' === (0, _utility.$)('> a', this).attr('href')) {
        (0, _utility.$)('> a', this).on('click', function (e) {
          e.preventDefault();
          (0, _utility.$)(this).next().next().responsiveNav();
        });
      }
    });
  });
})();

/***/ })
/******/ ]);