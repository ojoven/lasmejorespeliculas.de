$(document).ready(function() {

    // Fast Click
    FastClick.attach(document.body);

    // Activate link, search and random
    activateLinkResults();
    activateSearchResults();
    activateLoadRandomResult();

});



/** SEARCH **/
function activateSearchResults() {

    var waiting = 300;

    $("#search").on('keyup', function(e) {

        $("#loader").show();

        var avoidChars = [16, 37, 38, 39, 40, 27];

        if (avoidChars.indexOf(e.which)==-1) {

            var selector = $(this);
            delay(function(){
                var value = selector.val();
                search(value);
            }, waiting);
        }

    });

}

function search($query) {

    $("#results").html('');

    var url = urlBase + "html/search";
    var data = {};
    data.query = $query;

    $.get(url, data, function(response) {

        $("#loader").hide();
        $("#results").html(response);
        activateLinkResults();

    });

}

/** LINKING **/
function activateLinkResults() {

    $(".to-single").off().on('click', function() {

        $("#loader").show();
        $("#results").html('');

        var url = urlBase + "html/single";
        var data = {};
        data.type = $(this).data('type');
        data.name = $(this).data('name');

        $.get(url, data, function(response) {

            $("#loader").hide();
            $("#results").html(response);

            activateLinkResults();

        });

        return false;

    });

}

/** RANDOM **/
function activateLoadRandomResult() {

    $("#to-random").off().on('click', function() {

        $("#loader").show();
        $("#results").html('');

        var url = urlBase + "html/random";

        $.get(url, function(response) {

            $("#loader").hide();
            $("#results").html(response);

            activateLinkResults();

        });

        return false;

    });

}

// AUXILIARS
var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();