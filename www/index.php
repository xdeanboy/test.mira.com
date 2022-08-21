<?php

try {
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/../src/' . $className . '.php';
    });

    $routes = include __DIR__ . '/../src/routes.php';
    $route = $_GET['route'] ?? '';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    unset($matches[0]);

    if (!$isRouteFound) {
        throw new \TestProject\Exceptions\NotFoundException('Сторінку не знайдено');
    }

    $controllerName = $controllerAndAction[0];
    $controllerAction = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$controllerAction(...$matches);
} catch (\TestProject\Exceptions\NotFoundException $e) {
    $view = new \TestProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php',
        ['title' => 'Не знайдено',
            'user' => \TestProject\Services\UserAuthServices::getUserByToken(),
            'error' => $e->getMessage()],
        404);
} catch (\TestProject\Exceptions\DbException $e) {
    $view = new \TestProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php',
        ['title' => 'Помилка ДБ',
            'error' => $e->getMessage()],
        500);
} catch (\TestProject\Exceptions\UnauthorizedException $e) {
    $view = new \TestProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php',
        ['title' => 'Авторизуйтеся',
            'error' => $e->getMessage()],
        500);
} catch (\TestProject\Exceptions\ForbiddenException $e) {
    $view = new \TestProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php',
        ['title' => 'Доступ заборонено',
            'error' => $e->getMessage()],
        403);
}
