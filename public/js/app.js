$(document).ready(function() {

    var waiting = 300;

    $("#search").on('keyup', function(e) {

        var avoidChars = [16, 37, 38, 39, 40, 27];

        if (avoidChars.indexOf(e.which)==-1) {

            var selector = $(this);
            delay(function(){
                var value = selector.val();
                search(value);
            }, waiting);
        }

    });

});

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

function search($query) {

    var url = urlBase + "html/search";
    var data = {};
    data.query = $query;

    $.get(url, data, function(response) {

        $("#results").html(response);

    });

}