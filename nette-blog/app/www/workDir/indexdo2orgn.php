<?php include "_partials/header.php" ?>

    <div class="page-header">
        <h1>VERY MUCH TODO LIST</h1>
    </div>

    <?php $data = $database->select('items', 'text'); ?>

    <ul class="list-group col-sm-6">
        <?php
            foreach ( $data as $item ) {
                echo '<li class="list-group-item">' . $item . '</li>';
            }
        ?>
    </ul>

    <form class="col-sm-6" action="_inc/add-new.php" method="post">
        <p class="form-group">
            <textarea class="form-control" name="message" id="text" rows="3" placeholder="is there [ /watch?v=GO3wwqikkF0 ] ?"></textarea>
        </p>
        <p class="form-group">
            <input class="btn-sm btn-danger" type="submit" value="add new thing">
        </p>
    </form>

<?php include "_partials/footer.php" ?>