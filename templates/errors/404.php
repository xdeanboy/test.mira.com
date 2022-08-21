<?php include __DIR__ . '/../header.php' ?>
    <div class="container divError">
        <h2>Не знайдено</h2>
        <div class="text"><?= !empty($error) ? $error : 'Сторінку не знайдено' ?></div>
    </div>
<?php include __DIR__ . '/../footer.php' ?>