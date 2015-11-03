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
                        <span class="cast"><?php echo $film['cast_string']; ?></span>
                    <?php } else { // Actors ?>
                        <span class="director"><?php echo $film['director_string']; ?></span>
                    <?php } ?>
                </div>
                <div class="clear"></div>
                <a class="to-filmaffinity" href="http://www.filmaffinity.com/es/film<?php echo $film['id'];?>.html" target="_blank">
                    Ver película en Filmaffinity<i class="fa fa-external-link"></i>
                </a>
            </li>

        <?php } ?>

    </ul>

<?php } ?>