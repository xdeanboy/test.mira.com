<?php include __DIR__ . '/../header.php' ?>
    <div class="container signIn">

        <form action="/registration" method="post" id="formRegistration" class="form">

            <h2>Реєстрація</h2>

            <? if (!empty($error)): ?>
                <div class="error"><?= $error ?></div>
            <? endif; ?>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="registrationEmail" name="email" value="<?= $_POST['email'] ?? ''?>">
            </div>
            <div class="md-3">
                <select name="position" class="form-select" aria-label="Default select example">
                    <option disabled selected>Вибрати посаду</option>
                    <option value="Директор">Директор</option>
                    <option value="Менеджер">Менеджер</option>
                    <option value="Виконавець">Виконавець</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="registrationPassword" name="password" value="<?= $_POST['password'] ?? ''?>">
            </div>
            <button type="submit" class="btn btn-primary" id="registrationBtnSubmit">Реєстрація</button>
        </form>
    </div>
<?php include __DIR__ . '/../footer.php' ?>