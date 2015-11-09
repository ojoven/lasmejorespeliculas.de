<!DOCTYPE html>
<html>
    <head>
        <title>Review</title>
        <style>

            @font-face {
                font-family: 'RalewayBold';
                font-weight: bold;
                src: url(http://filmaffinity.ojoven.es/css/fonts/Roboto_Condensed/RobotoCondensed-Bold.woff) format('woff');
            }

            @font-face {
                font-family: 'Raleway';
                src: url(http://filmaffinity.ojoven.es/css/fonts/Raleway/Raleway-Regular.woff) format('woff');
            }

        </style>
        <link href="/css/style_review.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">

            <div class="review">

                <div class="review-container">

                    <div id="review-title">
                        <?php echo $review['review']['title']; ?>
                        <span id="review-rating">
                            (<?php echo $review['review']['rating']; ?>)
                        </span>
                    </div>

                    <div id="review-body">
                        <?php echo str_replace("\n", "<br>", $review['review']['review']); ?>
                    </div>

                </div>

                <div class="film">
                    <?php echo $review['film']['name']; ?> (<?php echo $review['film']['rating']; ?>)
                </div>
            </div>

        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type='application/javascript' src='/js/vendor/boxfit.js'></script>
        <script>
            $('#review-body').boxfit({multiline: true, align_middle: false, align_center: false});
        </script>

    </body>
</html>
