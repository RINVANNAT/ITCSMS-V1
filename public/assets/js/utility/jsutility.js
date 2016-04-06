/**
 * Created by snakecc on 11/20/15.
 */

if (typeof JSUTILITY !== 'object') {
    JSUTILITY = {};
}
(function () {
    JSUTILITY.Url = JSUTILITY.Url || {};
    JSUTILITY.Url.GetURLParameter = function (sParam)
    {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    };
}());