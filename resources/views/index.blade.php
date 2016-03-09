<?php
\App\Lib\Functions::redirectIfFacebook();
$title = (isset($result)) ? "Las mejores películas de " . $result['name'] : "Las mejores películas de...";
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?> | Powered by Filmaffinity</title>
        <?php if (isset($result)) {
            $description = \App\Lib\Functions::createDescriptionForResult($result);
        ?>

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@ojoven" />
            <meta name="twitter:title" content="<?php echo $title; ?>" />
            <meta name="twitter:description" content="<?php echo $description; ?>" />
            <?php if (isset($result['films'][0]['image'])) { ?>
                <meta name="twitter:image" content="<?php echo $result['films'][0]['image']; ?>" />
            <?php } ?>

            <meta property="og:type" content="website"/>
            <meta property="og:site_name" content="Project by @ojoven"/>
            <meta property="og:url" content="<?php echo $result['url']; ?>"/>
            <meta property="og:title" content="<?php echo $title; ?> | Powered by Filmaffinity"/>
            <?php if (isset($result['films'][0]['image'])) { ?>
                <meta property="og:image" content="<?php echo $result['films'][0]['image']; ?>"/>
            <?php } ?>
            <meta property="og:description" content="<?php echo $description; ?>">

        <?php } ?>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href='http://fonts.googleapis.com/css?family=Raleway:400,700,100' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="/css/style.css" rel="stylesheet">

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

    </head>
    <body>
        <div class="container">
            <div class="content">
                <div id="title"><?php echo $title; ?></div>
                <span id="subtitle">
                    Project by <a href="http://twitter.com/ojoven" target="_blank">@ojoven</a>, data by Filmaffinity
                </span>
                <input type="text" id="search" placeholder="Busca por actor o director..." value="">
                <span id="to-random-container">
                    <a id="to-random" href="#">
                        Sugiéreme al azar
                        <i class="fa fa-random"></i>
                    </a>
                </span>
                <div id="results">
                    <?php if (isset($result)) { ?>
                        @include('single')
                    <?php } elseif(isset($results)) { ?>
                        @include('results')
                    <?php } ?>
                </div>
                <div id="loader"></div>
                <div id="last">Datos recogidos de Filmaffinity: <?php echo \App\Lib\Functions::getLastCronDate(); ?></div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type='application/javascript' src='/js/vendor/fastclick.js'></script>
        <script type='application/javascript' src='/js/vendor/addclear.min.js'></script>
        <script type="text/javascript" src="/js/app.js"></script>

        <script>

            var urlBase = "<?php echo url() . "/"; ?>";

        </script>

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-74953604-1', 'auto');
            ga('send', 'pageview');

        </script>

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=187556868079585";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

    </body>
</html>
