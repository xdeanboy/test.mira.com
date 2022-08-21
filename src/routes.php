<?php

return [
    '~^$~' => [\TestProject\Controllers\MainController::class, 'main'],
    '~^sign-in$~' => [\TestProject\Controllers\UserController::class, 'signIn'],
    '~^sign-out$~' => [\TestProject\Controllers\UserController::class, 'signOut'],
    '~^registration$~' => [\TestProject\Controllers\UserController::class, 'register'],
    '~^activation-user-(\d+)/activation-code-(.*)~' => [\TestProject\Controllers\UserController::class, 'activation'],
    '~^card-add$~' => [\TestProject\Controllers\MainController::class, 'addCard'],
];
