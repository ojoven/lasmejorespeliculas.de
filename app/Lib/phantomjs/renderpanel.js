// Let's render an screenshot with PhantomJS

// Get the url of the repo
var system = require('system');
var url = system.args[1];
var filePath = system.args[2];
var extension = system.args[3];

var page = require('webpage').create();

var resourceWait = 300,
    maxRenderWait = 10000;

var count = 0,
    forcedRenderTimeout,
    renderTimeout;


function doRender() {
    var bb = page.evaluate(function () {
        document.body.bgColor = 'white';
        return document.getElementById("results").getBoundingClientRect();
    });

    page.render(filePath, { format: extension, quality: '100' }); // Phantom creates the images much faster in jpg but avconv creates corrupted video if JPG inputs
    phantom.exit();
}

// For logging errors
page.onResourceError = function(resourceError) {
    page.reason = resourceError.errorString;
    page.reason_url = resourceError.url;
};

page.onResourceRequested = function (req) {
    count += 1;
    console.log('> ' + req.id + ' - ' + req.url);
    clearTimeout(renderTimeout);
};

page.onResourceReceived = function (res) {
    if (!res.stage || res.stage === 'end') {
        count -= 1;
        console.log(res.id + ' ' + res.status + ' - ' + res.url);
        if (count === 0) {
            renderTimeout = setTimeout(doRender, resourceWait);
        }
    }
};

page.open(url, function (status) {
    if (status !== 'success') {
        console.log(
            "Error opening url \"" + page.reason_url
            + "\": " + page.reason
        );
        phantom.exit(1);
    } else {

        forcedRenderTimeout = setTimeout(function () {
            console.log(count);
            doRender();
            phantom.exit();
        }, maxRenderWait);
    }
});