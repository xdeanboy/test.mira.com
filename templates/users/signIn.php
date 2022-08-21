<?php include __DIR__ . '/../header.php' ?>
    <div class="container signIn">

        <form action="/sign-in" method="post" id="formSignIn" class="form">

            <h2>Авторизація</h2>

            <? if (!empty($error)): ?>
                <div class="error"><?= $error ?></div>
            <? endif; ?>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="signEmail" name="email"
                       value="<?= $_POST['email'] ?? '' ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="signPassword" name="password"
                       value="<?= $_POST['password'] ?? '' ?>">
            </div>
            <div class="md-3 divBtn">
                <button type="submit" class="btn btn-primary" id="signBtnSubmit">Авторизуватися</button>
                <button class="btn btn-primary" id="signBtnRegistration"><a href="/registration">Зареєструватися</a>
                </button>
            </div>
        </form>
    </div>
<?php include __DIR__ . '/../footer.php' ?>