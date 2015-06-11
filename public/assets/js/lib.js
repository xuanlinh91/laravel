var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // At least Safari 3+: "[object HTMLElementConstructor]"
var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
var isIE = /*@cc_on!@*/false || !!document.documentMode;   // At least IE6
var check_browser = false,loadMore = false,count_load = 0;page_index = '';
var regExp = new RegExp("\\w"), _tagKey = ["@","#"] , _tagKey_max_length = {'tagKey':["@","#"],"@":17,"#":32}
,_mentions = ['#'],_hashTag = ['@'],_hashTag_value = [],_mentions_value = [],map_global
,data_case_status = [],data_typeahead_check_in = '',title_contenteditable = '';
var url = "http://" + window.location.host + "/";
var max_length_content_collapse = 666;
var max_default_reply = 2;
var max_default_comment = 10;
var max_title_post = 111;
var max_content_post = 500;
var min_content_post = 3;
var min_title_post = 11;
var url_socket = "http://" + window.location.host ;
var tag = document.createElement('script');
tag.src = "http://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
(function($) {
    $.fn.clickToggle = function(func1, func2) {
        var funcs = [func1, func2];
        this.data('toggleclicked', 0);
        this.click(function() {
            var data = $(this).data();
            var tc = data.toggleclicked;
            $.proxy(funcs[tc], this)();
            data.toggleclicked = (tc + 1) % 2;
        });
        return this;
    };
    $.fn.goTo = function(height) {
        $('html, body').animate({
            scrollTop: $(this).offset().top + height + 'px'
        }, 'fast');
        return this; // for chaining... shaphỉa
    }
}(jQuery));
