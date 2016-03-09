$(document).ready(function() {

    // Fast Click
    FastClick.attach(document.body);

    // Onclear
    $("#search").addClear({ top:'2px', right:'10px' });

    // Activate link, search and random
    activateLinkResults();
    activateSearchResults();
    activateLoadRandomResult();

    // We load random result on load
    loadInitialResult();

});


/** INITIAL **/
function loadInitialResult() {

    if (window.location.pathname && window.location.pathname!="/") return;

    var hash = window.location.hash;
    if (hash) {
        hash = hash.split("#!/")[1];

        if (hash) {

            if (hash.indexOf('s/')!==-1) { // search
                hash = hash.split("s/")[1];
                hash = hash.replace(/_/g, ' ');
                search(hash);
            } else if (hash.indexOf('d/')!==-1) { // director
                hash = hash.split("d/")[1];
                hash = hash.replace(/_/g, ' ');
                loadSingleResult('director', hash);
            } else if (hash.indexOf('a/')!==-1) { // actor
                hash = hash.split("a/")[1];
                hash = hash.replace(/_/g, ' ');
                loadSingleResult('actor', hash);
            }

            return false;

        }

    }

    loadRandomResult();

}


/** SEARCH **/
function activateSearchResults() {

    var waiting = 300;

    $("#search").on('keyup', function(e) {

        $("#loader").show();

        var avoidChars = [16, 37, 38, 39, 40, 27];
        if (avoidChars.indexOf(e.which)==-1) {

            $("#results").html('');

            var selector = $(this);
            delay(function(){
                var value = selector.val();
                if (value.trim().length>0) {
                    search(value);
                } else {
                    $("#loader").hide();
                }
            }, waiting);
        } else {
            $("#loader").hide();
        }

    });

}

function search($query) {

    var url = urlBase + "html/search";
    var data = {};
    data.query = $query;

    $.get(url, data, function(response) {

        $("#loader").hide();
        $("#results").html(response);

        setHashtagURL("search", $query);
        activateLinkResults();
        ga('send', 'event', 'Films', 'search', $query);

    });

}

/** LINKING **/
function activateLinkResults() {

    $(".to-single").off().on('click', function() {

        var type = $(this).data('type');
        var name = $(this).data('name');

        loadSingleResult(type, name);

        return false;

    });

}

function loadSingleResult(type, name) {

    $("#loader").show();
    $("#results").html('');

    var url = urlBase + "html/single";
    var data = {};
    data.type = type;
    data.name = name;

    $.get(url, data, function(response) {

        $("#loader").hide();
        $("#results").html(response);
        $("#search").val(data.name);

        setHashtagURL(data.type, data.name);
        updateMeta(data.type, data.name);
        activateLinkResults();
        updateSocialLinks();
        ga('send', 'event', 'Films', 'result', data.name);

    });

}

/** RANDOM **/
function activateLoadRandomResult() {

    $("#to-random").off().on('click', function() {

        loadRandomResult();
        return false;

    });

}

function loadRandomResult() {

    $("#loader").show();
    $("#results").html('');

    var url = urlBase + "html/random";

    $.get(url, function(response) {

        $("#loader").hide();
        $("#results").html(response);

        var name = $("#results .single-header .title").html();
        var type = $("#results .single-header .type").data('type');
        $("#search").val(name);

        setHashtagURL(type, name);
        activateLinkResults();
        updateMeta(type, name);
        updateSocialLinks();

    });

}

/** URL **/
function setHashtagURL(type, name) {

    if (type == 'director') {
        name = name.replace(/ /g, '_');
        window.location.hash = "!/d/" + name;
    }  else if (type == 'actor') {
        name = name.replace(/ /g, '_');
        window.location.hash = "!/a/" + name;
    } else if (type == 'search') {
        name = name.replace(/ /g, '_');
        window.location.hash = "!/s/" + name;
    }

}

/** META **/
function updateMeta(type, name) {

    var title = "Las mejores películas de " + name;
    document.title = title;
    $("#title").html(title);

}

function updateSocialLinks() {
    if (typeof twttr != "undefined") twttr.widgets.load();
    if (typeof FB != "undefined") FB.XFBML.parse();
}


/** AUXILIARS **/
var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();