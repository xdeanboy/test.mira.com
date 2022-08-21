<?php include __DIR__ . '/../header.php' ?>
<div class="container successful">
    <h2 class="title">Реєстрація пройшла успішно!</h2>
    <div class="text">
        На ваш email <strong><?= $newUser->getEmail() ?></strong> відправлено повідомлення з подальшими діями
        активації аккаунту.
    </div>
</div>
<?php include __DIR__ . '/../footer.php' ?>
