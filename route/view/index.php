<?php
    // Подключение файлов
    include $_SERVER['DOCUMENT_ROOT'] . '/helpers/helper.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';

    // Обработка формы
    $pictureNamesArray = $fileHandling($sortPictures());
?>
<div class="container">
    <a href="/">< Назад</a>

    <div class="gallary-wrapper">
        <form class="gallary-wrapper_form form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <input class="form_submit" type="submit" value="Удалить выбранные изображения" name="delete">

            <?php 
                if (isset($pictureNamesArray)) {
                    foreach ($pictureNamesArray as $key => $value) {
            ?>

            <div class="gallary-item">
                <label class="gallary-item_label">
                    <img class="gallary-item_img" src="<?='/upload/' . $value?>" alt="Картинка<?=$key?>">
                    <input class="checkbox" type="checkbox" name="<?=$key?>_picture">
                </label>
            </div>

            <?php
                    }
                }
            ?>
        </form>      
    </div>
</div>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';
?>