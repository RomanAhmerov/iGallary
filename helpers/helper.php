<?php

// Объявление переменной для дальнейшей её работы в различных функциях
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/';


/**
 * Функция, которая проверяет формат загружаемых файлов и их вес, и в зависимости от этого загружает файлы на сервер или выдаёт ошибку
 * @return array данная функция возвращает массив с текстом ('description') об успешной отправки или об произошедшей ошибки и наименования класса ('class') для дальнейшей стилизации блока с сообщением
 */
$downloadFiles = function() use ($uploadPath) {
    // Объявление переменных для дальнейшей работы с ними внутри функции
    $success = null;
    $error = null;
    $description = '';
    $class = '';

    // Загрузка файлов (не более 5 штук) на сервер и определние класса ошибки при её возникновении
    if (isset($_POST['upload'])) {
        // Данная переменная отвечает за количество итераций последующего блока кода (количества файлов, которое нужно обработать)
        $iterationIndex = 0;
        // Почему я использую дополнительную итерационную переменную в цикле foreach?
        // Из-за того, что типы и размеры загражаемых файлов на сервер, находятся в разных массивах соответственно

        foreach ($_FILES['myPictures']['tmp_name'] as $itemProcessing) {
            // Прекращение всего цикла по загрузке файлов на сервер, если количество файлов больше 5
            if ($iterationIndex > 4) {
                break;
            }

            if (is_uploaded_file($_FILES['myPictures']['tmp_name'][$iterationIndex])) {
                $countPicturesDownload = $iterationIndex + 1;
    
                $nextFileIndex = intval(end(scandir($uploadPath))) + 1;

                if (
                    $_FILES['myPictures']['type'][$iterationIndex] == "image/jpeg" || 
                    $_FILES['myPictures']['type'][$iterationIndex] == "image/png"
                ) {
                    if ($_FILES['myPictures']['size'][$iterationIndex] <= 5242880) {
                        if (!empty($_FILES['myPictures']['error'][$iterationIndex])) {
                            // 'Произошла ошибка при загрузке картинки'
                            $error = 1;
                        } else {
                            $fileExpansion = $_FILES['myPictures']['type'][$iterationIndex] == "image/jpeg" ? '.jpg' : '.png';
                            
                            // Загрузка файлов в папку /upload с новым именем (добавление хеша в имя файла для безопасности данных)
                            move_uploaded_file($itemProcessing, $uploadPath . $nextFileIndex . 'F' .  hash('md5', $nextFileIndex) . $fileExpansion);
                            
                            // 'Ваш файл успешно загружен'
                            $success = true;
                        }
                    } else {
                        // 'Максимальный размер файла 5 Мб'
                        $error = 2;
                    }
                } else {
                    // 'Загрузить можно только картинки формата (jpeg, png, jpg)'
                    $error = 3;
                }
            }

            $iterationIndex++;
        }
    }

    // Определение текста в зависимости от того какая произошла ошибка
    switch ($error) {
        case 1:
            $description = 'Произошла ошибка при загрузке картинки';
            break;
        case 2:
            $description = 'Максимальный размер файла 5 Мб';
            break;
        case 3:
            $description = 'Загрузить можно только картинки формата (jpeg, png, jpg)';
            break;
    }

    // Определение текста об успешной загрузки файлов в зависимости от количества загруженных файлов
    if ($success) {
        if ($countPicturesDownload == 1) {
            $description = 'Ваш файл успешно загружен';
        } else {
            $description = 'Ваши файлы успешно загружены';
        }
    }

    // Определение класса выводимого сообщения для дальнешей его стилизации
    $class = $success ? 'description-green' : 'description-red';

    // Возвращаемый массив
    $downloadFilesArray = [
        'description' => $description,
        'class' => $class
    ];

    return $downloadFilesArray;
};


/**
 * Функция для сортировки (по убыванию) имён загруженных на сервер файлов (вначале будут новозагруженные файлы и так до старозагруженных вниз)
 * @return array данная функция возвращает отсортированный (по убыванию) массив имён файлов находящихся в папке /upload
 */
$sortPictures = function() use ($uploadPath) {
    if (count(scandir($uploadPath)) > 2) {
        // Объявление переменных для дальнейшей работы с ними внутри функции
        $picturesArray = scandir($uploadPath);
        $sortPicturesArray = [];
    
        for ($i = 2; $i < count($picturesArray); $i++) {
            if (!isset(scandir($uploadPath)[$i])) {
                continue;
            }
    
            $sortPicturesArray[intval($picturesArray[$i])] = $picturesArray[$i];
        }
    
        krsort($sortPicturesArray);

        return $sortPicturesArray;
    }
};


/**
 * Функция для обработки файлов в папке (удаление файлов) в данном случае папка (/upload)
 * @param array входным параметром является массив c именами файлов в папке /upload
 * @return array данная функция возвращает новый массив имен файлов в папке /upload 
 */
$fileHandling = function($sortArray = []) use ($uploadPath) {
    if (isset($_POST['delete'])) {
        foreach ($_POST as $key => $value) {
            if ($key == 'delete') {
                continue;
            }
    
            $removableFileName =  $sortArray[intval($key)];
            unset($sortArray[intval($key)]);
            unlink($uploadPath . $removableFileName);
        } 

        return $sortArray;
    } else {
        return $sortArray;
    }
};
