<?php
$title = "Las mejores películas de " . $result['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="/css/style.css" rel="stylesheet">

    <style>

        @font-face {
            font-family: 'Raleway';
            src: url(http://filmaffinity.ojoven.es/css/fonts/Raleway/Raleway-Regular.woff) format('woff');
        }

    </style>

</head>
<body>
<div class="container">
    <div class="content">

        <div id="results" style="width: 490px;padding-right: 20px;margin-top:-10px;">

            <div class="single-header">
                <span class="type"
                      data-type="<?php echo $result['type']; ?>"><?php echo $result['type_string']; ?></span>
                <span class="title"><?php echo $result['name']; ?></span>
            </div>

            <ul>

                <?php
                $result['films'] = array_slice($result['films'], 0, 5);
                foreach ($result['films'] as $index=>$film) { ?>

                <li class="film">
                    <div class="position"><?php echo $index+1; ?></div>
                    <div class="left" style="width:75px;">
                        <img src="<?php echo $film['image']; ?>" style="width: 80%;">
                    </div>
                    <div class="right">
                    <span class="title" style="max-width: 250px;">
                        <?php echo $film['name'] ?>
                        <span class="year">(<?php echo $film['year']; ?>)</span>
                    </span>
                        <span class="rating" style="font-size:28px;"><?php echo $film['rating'] ?></span>
                        <?php if ($result['type'] == "director") { ?>

                        <span class="cast">
                            <?php
                            $cast = explode(", ", $film['cast_string']);
                            $cast = array_slice($cast, 0, 6);
                            foreach ($cast as $index=>$actor) { ?>
                            <a class="sub-result to-single" href="#" data-type="actor"
                               data-name="<?php echo $actor; ?>">
                                <?php echo $actor; ?>
                            </a>
                            <?php } ?>
                        </span>

                        <?php } else { // We show director ?>

                        <span class="director">
                            <?php
                            $directors = explode(", ", $film['director_string']);
                            foreach ($directors as $index=>$director) { ?>
                            <a class="sub-result to-single" href="#" data-type="director"
                               data-name="<?php echo $director; ?>">
                                <?php echo $director; ?>
                            </a>
                            <?php } ?>
                        </span>

                        <?php } ?>

                    </div>
                    <div class="clear"></div>
                </li>

                <?php } ?>

            </ul>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type='application/javascript' src='/js/vendor/fastclick.js'></script>
<script type='application/javascript' src='/js/vendor/addclear.min.js'></script>
<script type="text/javascript" src="/js/app.js"></script>

<script>

    var urlBase = "<?php echo url() . "/"; ?>";

</script>

</body>
</html>
