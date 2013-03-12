$(function () {
    if ($('form[data-validate=parsley]').length) {
        $('form[data-validate=parsley]').each(function (key, element) {
            var $element = $(element);
            if ($element.find('.help-inline, .error').length) {
                $element.find('.help-inline').remove();
                $element.find('.error').removeClass('error');
                $element.parsley('validate');
            }
        });

    }
    // this will fix the blocking requests while also forcing 1 request at a time
    var activeRequests = {},
        // add more reg exps here
        ajaxUrlRegExps = [
            /\/register\/validate\/\w+\?\w+/gi
        ];
    $.ajaxSetup({
        beforeSend: function(jqXHR, options) {
            var url = options.url,
                i = null;
            for (i = 0; i < ajaxUrlRegExps.length; i++) {
                if (url.match(ajaxUrlRegExps[i]) !== null) {
                    url = url.split('=', 2)[0];
                    if (url in activeRequests) {
                        activeRequests[url].abort();
                        delete activeRequests[url];
                    }
                    activeRequests[url] = jqXHR;
                    options.async = true;
                    return true;
                }
            }
        }
    });
});