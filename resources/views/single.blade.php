<?php if ($result) { ?>

    <div class="single-header">
        <span class="type"><?php echo $result['type_string']; ?></span>
        <span class="title"><?php echo $result['name']; ?></span>
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