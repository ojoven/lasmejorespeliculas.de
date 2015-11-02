<?php if ($results) { ?>

    <ul>

        <?php foreach ($results as $result) { ?>

            <li class="result">
                <a>
                    <?php if (isset($result['image'])) { ?>
                        <img src="<?php echo $result['image']; ?>">
                    <?php } ?>
                    <span class="type"><?php echo $result['type'] ?></span>
                    <span class="title"><?php echo $result['name'] ?></span>
                    <span class="total"><?php echo ($result['total']===1) ? $result['total'] . " película" : $result['total'] . " películas"; ?></span>
                </a>
            </li>

        <?php } ?>

    </ul>

<?php } ?>