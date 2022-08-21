<?php include __DIR__ . '/header.php' ?>
<div class="container main">
    <h2>Дякую, що увійшли <?= $user->getEmail() ?></h2>
    <a href="/sign-out" class="button">Вийти</a>

    <? if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <? endif; ?>


    <form action="/card-add" method="post" id="form-pressed">
        <div class="row col-12 mainContent">
            <div class="col-4 block-boss">
                <div class="email"><h3>Boss</h3></div>

                <button type="button" class="btn btn-primary" <? if (!$user->isBoss()): ?> disabled <? endif; ?>
                        id="btnBoss">Кнопка boss
                </button>

                <div id="boss-cards">
                    <!--For clicked boss-->

                    <? if (!empty($bossCards)): ?>
                        <? foreach ($bossCards as $bossCard): ?>
                            <div class="card-old">
                                <h4 class="title"><?= $bossCard->getTitle() ?></h4>
                                <div class="text"><?= $bossCard->getBody() ?></div>
                            </div>
                        <? endforeach; ?>
                    <? endif; ?>

                </div>
            </div>

            <div class="col-4 block-manager">
                <div class="email"><h3>Manager</h3></div>

                <button type="button" class="btn btn-primary" <? if ($user->isPerformer()): ?> disabled <? endif; ?>
                        id="btnManager">Кнопка manager
                </button>

                <div id="manager-cards">
                    <!--For clicked manager or boss-->

                    <? if (!empty($managerCards)): ?>
                        <? foreach ($managerCards as $managerCard): ?>
                            <div class="card-old">
                                <h4 class="title"><?= $managerCard->getTitle() ?></h4>
                                <div class="text"><?= $managerCard->getBody() ?></div>
                            </div>
                        <? endforeach; ?>
                    <? endif; ?>

                </div>
            </div>

            <div class="col-4 block-performer">
                <div class="email"><h3>Performer</h3></div>

                <button type="button" class="btn btn-primary" id="btnPerformer">Кнопка performer</button>

                <div id="performer-cards">
                    <!--For clicked performer or manager or boss-->

                    <? if (!empty($performerCards)): ?>
                        <? foreach ($performerCards as $performerCard): ?>
                            <div class="card-old">
                                <h4 class="title"><?= $performerCard->getTitle() ?></h4>
                                <div class="text"><?= $performerCard->getBody() ?></div>
                            </div>
                        <? endforeach; ?>
                    <? endif; ?>

                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="btnSaveAll">Зберегти всі записи</button>
    </form>
</div>

<script>
    var card = '<div class="card"><h4 class="title"></h4><div class="text"></div></div>'
    var i = 1;
    var url = 'https://jsonplaceholder.typicode.com/posts/';

    $("#btnBoss").click(function () {
        if (i <= 10) {
            $.ajax({
                url: url + i++,
                type: 'GET',
                cache: false,
                success: function (informCard) {
                    $("#boss-cards").append('<div class="card"><h4 class="title">' + informCard.title
                        + '</h4><div class="text">' + informCard.body
                        + '</div></div>');
                }
            })
        }
    });

    $("#btnManager").click(function () {
        if (i <= 10) {
            $.ajax({
                url: url + i++,
                type: 'GET',
                cache: false,
                success: function (informCard) {
                    $("#manager-cards").append('<div class="card"><h4 class="title">' + informCard.title
                        + '</h4><div class="text">' + informCard.body
                        + '</div></div>');
                }
            })
        }
    });

    $("#btnPerformer").click(function () {
        if (i <= 10) {
            $.ajax({
                url: url + i++,
                type: 'GET',
                cache: false,
                success: function (informCard) {
                    $("#performer-cards").append('<div class="card"><h4 class="title">' + informCard.title
                        + '</h4><div class="text">' + informCard.body
                        + '</div></div>');
                }
            })
        }
    });

    $("#btnSaveAll").click(function () {
        var bossCards = $("#boss-cards .card");
        var managerCards = $("#manager-cards .card");
        var performerCards = $("#performer-cards .card");

        if (bossCards !== null) {
            for (i = 0; i < bossCards.length; i++) {
                let title = bossCards[i].firstChild.firstChild;
                let body = bossCards[i].lastChild.firstChild
                let button = 'Кнопка boss';
                let formData = new FormData();
                formData.append('title', title.textContent);
                formData.append('body', body.textContent);
                formData.append('button', button);

                $.ajax({
                    url: 'card-add',
                    method: 'POST',
                    cache: false,
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                })

            }
        }

        if (managerCards !== null) {
            for (i = 0; i < managerCards.length; i++) {
                let title = managerCards[i].firstChild.firstChild;
                let body = managerCards[i].lastChild.firstChild
                let button = 'Кнопка manager';
                let formData = new FormData();
                console.log(title)
                formData.append('title', title.textContent);
                formData.append('body', body.textContent);
                formData.append('button', button);

                $.ajax({
                    url: 'card-add',
                    method: 'POST',
                    cache: false,
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                })

            }
        }

        if (performerCards !== null) {
            for (i = 0; i < performerCards.length; i++) {
                let title = performerCards[i].firstChild.firstChild;
                let body = performerCards[i].lastChild.firstChild
                let button = 'Кнопка performer';
                let formData = new FormData();
                formData.append('title', title.textContent);
                formData.append('body', body.textContent);
                formData.append('button', button);

                $.ajax({
                    url: 'card-add',
                    method: 'POST',
                    cache: false,
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                })

            }
        }
    })

</script>
<?php include __DIR__ . '/footer.php' ?>
