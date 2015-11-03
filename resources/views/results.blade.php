<?php if ($results) { ?>

    <ul>

        <?php foreach ($results as $index=>$result) { ?>

            <li class="result">
                <a class="to-single" href="#" data-type="<?php echo $result['type']; ?>" data-name="<?php echo $result['name']; ?>">
                    <?php if (isset($result['image'])) { ?>
                        <img src="<?php echo $result['image']; ?>">
                    <?php } ?>
                    <span class="type"><?php echo $result['type_string'] ?></span>
                    <span class="title"><?php echo $result['name'] ?></span>
                    <span class="total"><?php echo ($result['total']===1) ? $result['total'] . " película" : $result['total'] . " películas"; ?></span>
                </a>
            </li>

        <?php } ?>

    </ul>

<?php } else { ?>

    <div class="no-results">Oooops! No encontramos nada</div>

<?php }?>