<?php

namespace TestProject\Controllers;

use TestProject\Exceptions\ForbiddenException;
use TestProject\Exceptions\InvalidArgumentException;
use TestProject\Exceptions\NotFoundException;
use TestProject\Models\Users\User;
use TestProject\Services\EmailSender;
use TestProject\Services\UserActivateServices;
use TestProject\Services\UserAuthServices;

class UserController extends AbstractController
{
    /**
     * @return void
     * @throws ForbiddenException
     */
    public function signIn(): void
    {
        if (!empty($this->user)) {
            throw new ForbiddenException('Ви вже авторизовані');
        }

        if (!empty($_POST)) {
            try {
                $user = User::signIn($_POST);

                if (!empty($user)) {

                    if (!$user->isConfirmed()) {
                        throw new ForbiddenException('Активуйте свій акаунт');
                    }

                    UserAuthServices::setAuthTokenForLogin($user);

                    header('Location: /', true, 302);
                    return;
                }
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signIn.php',
                    ['title' => 'Помилка авторизації',
                        'error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('users/signIn.php',
        ['title' => 'Авторизація']);
    }

    /**
     * @return void
     */
    public function signOut(): void
    {
        setcookie('token', '', -1, '', '');

        header('Location: /');
        return;
    }

    /**
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function register(): void
    {
        if (!empty($this->user)) {
            throw new ForbiddenException();
        }


        if (!empty($_POST)) {
            try {
                $user = User::create($_POST);

                if (!empty($user)) {
                    //Створення коду та відправлення email + view renderHTML
                    $activationCode = UserActivateServices::createActivationCode($user);

                    EmailSender::send(
                        $user,
                        'Активація аккаунту',
                        'registerConfirmation.php',
                        ['userId' => $user->getId(),
                            'code' => $activationCode]);

                    $this->view->renderHtml('users/registrationSuccessful.php',
                    ['title' => 'Успішна реєстрація',
                        'newUser' => $user]);
                    return;
                }
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/registration.php',
                    ['title' => 'Помилка реєстрації',
                        'error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('users/registration.php',
        ['title' => 'Реєстрація']);
    }

    /**
     * @param int $userId
     * @param string $code
     * @return void
     * @throws NotFoundException
     */
    public function activation(int $userId, string $code): void
    {
        if (empty($userId)) {
            throw new NotFoundException('Користувача не знайдено');
        }

        if (empty($code)) {
            throw new NotFoundException('Код не знайдено');
        }

        $checkUser = User::getById($userId);

        if ($checkUser === null) {
            throw new NotFoundException('Користувача не знайдено');
        }

        $checkCode = UserActivateServices::checkActivationCode($checkUser, $code);

        if (!$checkCode) {
            throw new NotFoundException('Не правильний код авторизації');
        }

        $checkUser->activate();
        UserActivateServices::deleteActivationCode($checkUser, $code);

        $this->view->renderHtml('users/activationSuccessful.php',
        ['title' => 'Успішна активація']);
    }
}