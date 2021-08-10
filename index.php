<!-- iGallary - сервис для загрузки фотографий -->
<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/helpers/helper.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';

    // Обработка формы
    $downloadArray = $downloadFiles();
?>

<div class="container">
    <h1>iGallary - сервис для загрузки фотографий</h1>

    <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <span>Загрузите файл(ы)*:</span>

        <input type="file" name="myPictures[]" multiple />

        <small>*Не больше 5шт.</small>

        <input type="submit" name="upload" value="Загрузить">
    </form>

    <?php if (isset($downloadArray)) {?>
        <div class="<?=$downloadArray['class']?>">
        <?=$downloadArray['description']?>
        </div>
    <?php }?>


    <a class="link" href="/route/view/">Просмотр загруженных картинок ></a>
</div>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';
?>
