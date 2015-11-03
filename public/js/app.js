$(document).ready(function() {

    activateLinkResults();
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

});

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

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

function activateLinkResults() {

    console.log('activate pre');
    $(".to-single").off().on('click', function() {

        console.log('activate post');

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

    });


}