;(function(window) {

  var svgSprite = '<svg>' +
    '' +
    '<symbol id="icon-dingwei" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M816.8428 332.8973c0-157.1369-129.324-284.5409-288.9861-284.5409-159.5924 0-288.9851 127.404-288.9851 284.5409 0 51.9004 14.3647 100.3704 38.9734 142.2643h-0.310272l245.7211 488.8525c0.8407 1.6742 2.5733 2.8211 4.5742 2.8211 1.9999 0 3.7335-1.1459 4.5742-2.8201L778.2502 475.1616h-0.35328C802.5907 433.2667 816.8428 384.7967 816.8428 332.8973M527.8843 475.1329c-79.7809 0-144.4915-63.7092-144.4915-142.2776 0-78.5254 64.7107-142.2633 144.4915-142.2633 79.8669 0 144.4925 63.7379 144.4925 142.2633C672.3779 411.4237 607.7512 475.1329 527.8843 475.1329"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-edit" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M34.155089 230.940227 9.17948 230.940227 9.17948 256.203386 9.17948 854.158012C9.17948 923.769568 65.248004 980.289737 134.081773 980.289737L927.938515 980.289737 952.914125 980.289737 952.914125 955.026579 952.914125 471.100561C952.914125 457.148105 941.732164 445.837402 927.938515 445.837402 914.144868 445.837402 902.962906 457.148105 902.962906 471.100561L902.962906 955.026579 927.938515 929.76342 134.081773 929.76342C92.797081 929.76342 59.130699 895.825847 59.130699 854.158012L59.130699 256.203386 34.155089 281.466543 598.93821 281.466543C612.731859 281.466543 623.91382 270.155842 623.91382 256.203386 623.91382 242.250928 612.731859 230.940227 598.93821 230.940227L34.155089 230.940227Z"  ></path>' +
    '' +
    '<path d="M437.016339 593.503789 431.876019 600.104892 431.668623 608.505214 427.984924 757.709741 427.077935 794.446421 461.312335 782.146455 605.005395 730.519447 611.980762 728.013291 616.479561 722.067243 1003.181673 210.964228 1018.529978 190.678421 998.306108 175.379305 869.49174 77.932781 849.985487 63.176536 834.913446 82.53177 437.016339 593.503789ZM839.575373 118.395018 968.389739 215.841542 963.514174 180.256619 576.81206 691.359633 588.286225 682.907428 444.593165 734.534436 477.920574 758.971151 481.604275 609.766622 476.256559 624.768047 874.153664 113.79603 839.575373 118.395018Z"  ></path>' +
    '' +
    '<path d="M891.217762 310.505713 920.474916 269.553252 808.309143 187.564266 779.051989 228.516725 891.217762 310.505713Z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-gengduo" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M501.568 937.024c4.416 4.224 10.368 6.592 16.64 6.592 6.208 0 12.224-2.368 16.576-6.592M534.848 937.024l446.4-427.52c19.648-18.752 18.432-50.048-1.152-68.8-19.52-18.688-51.136-18.816-70.784-0.064l-391.04 374.144-391.04-374.144C107.52 421.888 75.904 422.016 56.384 440.704 36.8 459.456 35.584 490.752 55.232 509.504l446.4 427.52M501.312 605.312c4.416 4.224 10.368 6.592 16.64 6.592 6.208 0 12.224-2.368 16.576-6.592M534.528 605.312l446.4-427.52c19.648-18.752 18.432-50.048-1.152-68.8-19.52-18.688-51.136-18.816-70.784-0.064l-391.04 374.144-391.04-374.144C107.264 90.176 75.648 90.304 56.128 108.992 36.544 127.744 35.328 159.04 54.912 177.792l446.4 427.52"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-renzheng" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M512 36.4928c-171.5072 0-310.5408 139.0336-310.5408 310.5408S340.4928 657.5744 512 657.5744s310.5408-139.0336 310.5408-310.5408S683.5072 36.4928 512 36.4928zM512 638.656c-161.0624 0-291.6352-130.5728-291.6352-291.6352S350.9376 55.3984 512 55.3984s291.6352 130.5728 291.6352 291.6352S673.0624 638.656 512 638.656z"  ></path>' +
    '' +
    '<path d="M512 215.6032c-72.5888 0-131.4304 58.8416-131.4304 131.4304S439.4112 478.4512 512 478.4512s131.4304-58.8416 131.4304-131.4304S584.5888 215.6032 512 215.6032zM512 462.1056c-63.552 0-115.072-51.52-115.072-115.072s51.52-115.072 115.072-115.072 115.072 51.52 115.072 115.072S575.552 462.1056 512 462.1056z"  ></path>' +
    '' +
    '<path d="M279.168 552.4864 279.168 972.2496 299.1232 972.2496 508.2368 775.9616 715.2896 972.2496 735.6672 972.2496 735.6672 562.432 716.1216 581.056 716.1216 940.8768 507.4176 743.7952 301.5424 937.664 301.5424 575.232Z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-keytop-copy" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M473.968 78.443v733.558l-180.328-187.319c-4.796-4.795-12.57-4.795-17.367 0l-37.627 37.627c-4.795 4.796-4.795 12.57 0 17.367l227.011 235.807c0.229 0.272 0.465 0.541 0.721 0.797l1.259 1.259 35.009 36.367c2.568 2.567 5.99 3.746 9.352 3.564 3.369 0.188 6.8-0.991 9.372-3.562l36.267-37.627c0.241-0.243 0.463-0.496 0.68-0.752l227.050-235.852c4.797-4.796 4.797-12.57 0-17.367l-37.627-37.627c-4.795-4.795-12.569-4.795-17.367 0l-178.637 185.562v-731.8c0-6.783-5.498-12.28-12.28-12.28h-53.213c-6.781 0-12.28 5.497-12.28 12.28z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-rise" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M550.032 945.557v-733.558l180.328 187.319c4.796 4.795 12.57 4.795 17.367 0l37.627-37.627c4.795-4.796 4.795-12.57 0-17.367l-227.011-235.807c-0.229-0.272-0.465-0.541-0.721-0.797l-1.259-1.259-35.009-36.367c-2.568-2.567-5.99-3.746-9.352-3.564-3.369-0.188-6.8 0.991-9.372 3.562l-36.267 37.627c-0.241 0.243-0.463 0.496-0.68 0.752l-227.050 235.852c-4.797 4.796-4.797 12.57 0 17.367l37.627 37.627c4.795 4.795 12.569 4.795 17.367 0l178.637-185.562v731.8c0 6.783 5.498 12.28 12.28 12.28h53.213c6.781 0 12.28-5.497 12.28-12.28z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-chaxun" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M958.224436 909.080667l-51.347458 51.308573L654.634046 708.413392c-62.166875 49.831942-140.974755 79.792301-226.918149 79.792301-200.562898 0-363.129414-162.404834-363.129414-362.726232 0-200.329585 162.566516-362.727255 363.129414-362.727255 200.563922 0 363.129414 162.39767 363.129414 362.727255 0 88.361467-31.708166 169.289638-84.289732 232.231155L958.224436 909.080667 958.224436 909.080667zM427.715897 130.4654c-163.106822 0-295.342542 132.075061-295.342542 295.013037 0 162.936953 132.236743 295.010991 295.342542 295.010991 163.138544 0 295.383474-132.074038 295.383474-295.010991C723.099371 262.540462 590.853417 130.4654 427.715897 130.4654L427.715897 130.4654z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-jiantou-copy-copy-copy" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M957.669 442.081l-444.825 471.825-446.569-471.769 30.656-29.025 415.856 439.369 414.225-439.313z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '</svg>'
  var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
  var shouldInjectCss = script.getAttribute("data-injectcss")

  /**
   * document ready
   */
  var ready = function(fn) {
    if (document.addEventListener) {
      if (~["complete", "loaded", "interactive"].indexOf(document.readyState)) {
        setTimeout(fn, 0)
      } else {
        var loadFn = function() {
          document.removeEventListener("DOMContentLoaded", loadFn, false)
          fn()
        }
        document.addEventListener("DOMContentLoaded", loadFn, false)
      }
    } else if (document.attachEvent) {
      IEContentLoaded(window, fn)
    }

    function IEContentLoaded(w, fn) {
      var d = w.document,
        done = false,
        // only fire once
        init = function() {
          if (!done) {
            done = true
            fn()
          }
        }
        // polling for no errors
      var polling = function() {
        try {
          // throws errors until after ondocumentready
          d.documentElement.doScroll('left')
        } catch (e) {
          setTimeout(polling, 50)
          return
        }
        // no errors, fire

        init()
      };

      polling()
        // trying to always fire before onload
      d.onreadystatechange = function() {
        if (d.readyState == 'complete') {
          d.onreadystatechange = null
          init()
        }
      }
    }
  }

  /**
   * Insert el before target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var before = function(el, target) {
    target.parentNode.insertBefore(el, target)
  }

  /**
   * Prepend el to target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var prepend = function(el, target) {
    if (target.firstChild) {
      before(el, target.firstChild)
    } else {
      target.appendChild(el)
    }
  }

  function appendSvg() {
    var div, svg

    div = document.createElement('div')
    div.innerHTML = svgSprite
    svgSprite = null
    svg = div.getElementsByTagName('svg')[0]
    if (svg) {
      svg.setAttribute('aria-hidden', 'true')
      svg.style.position = 'absolute'
      svg.style.width = 0
      svg.style.height = 0
      svg.style.overflow = 'hidden'
      prepend(svg, document.body)
    }
  }

  if (shouldInjectCss && !window.__iconfont__svg__cssinject__) {
    window.__iconfont__svg__cssinject__ = true
    try {
      document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
    } catch (e) {
      console && console.log(e)
    }
  }

  ready(appendSvg)


})(window)