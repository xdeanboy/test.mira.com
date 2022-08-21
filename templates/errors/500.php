<?php include __DIR__ . '/../header.php' ?>
    <div class="container divError">
        <h2>Помилка БД</h2>
        <div class="text"><?= !empty($error) ? $error : ''?></div>
    </div>
<?php include __DIR__ . '/../footer.php' ?>