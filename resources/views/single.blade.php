<?php if ($result) { ?>

    <div class="single-header">
        <span class="type" data-type="<?php echo $result['type']; ?>"><?php echo $result['type_string']; ?></span>
        <span class="title"><?php echo $result['name']; ?></span>
    </div>

    <div class="social-header">

        <div class="fb-like" data-href="<?php echo $result['url']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

        <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $result['url']; ?>" data-text="<?php echo $result['text_twitter']; ?>" data-related="ojoven" data-lang="es">Twittear</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

    </div>

    <ul>

        <?php foreach ($result['films'] as $film) { ?>

            <li class="film">
                <div class="left">
                    <img src="<?php echo $film['image']; ?>">
                </div>
                <div class="right">
                    <span class="title">
                        <?php echo $film['name'] ?>
                        <span class="year">(<?php echo $film['year']; ?>)</span>
                    </span>
                    <span class="rating"><?php echo $film['rating'] ?></span>
                    <?php if ($result['type']=="director") { ?>

                        <span class="cast">
                            <?php
                            $cast = explode(", ", $film['cast_string']);
                            $cast = array_slice($cast, 0, 6);
                            foreach ($cast as $index=>$actor) { ?>
                            <a class="sub-result to-single" href="#" data-type="actor" data-name="<?php echo $actor; ?>">
                                <?php echo $actor; ?>
                            </a>
                            <?php } ?>
                        </span>

                    <?php } else { // We show director ?>

                        <span class="director">
                            <?php
                            $directors = explode(", ", $film['director_string']);
                            foreach ($directors as $index=>$director) { ?>
                                <a class="sub-result to-single" href="#" data-type="director" data-name="<?php echo $director; ?>">
                                    <?php echo $director; ?>
                                </a>
                            <?php } ?>
                        </span>

                    <?php } ?>

                    <a class="to-filmaffinity" href="http://www.filmaffinity.com/es/film<?php echo $film['id'];?>.html" target="_blank">
                        Ver película en Filmaffinity<i class="fa fa-external-link"></i>
                    </a>

                </div>
                <div class="clear"></div>
            </li>

        <?php } ?>

    </ul>

<?php } ?>