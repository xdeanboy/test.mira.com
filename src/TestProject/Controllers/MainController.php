<?php

namespace TestProject\Controllers;

use TestProject\Exceptions\InvalidArgumentException;
use TestProject\Exceptions\UnauthorizedException;
use TestProject\Models\Cards\Card;

class MainController extends AbstractController
{
    /**
     * @return void
     * @throws UnauthorizedException
     */
    public function main(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $bossCards = Card::findAllByBoss();
        $managerCards = Card::findAllByManager();
        $performerCards = Card::findAllByPerformer();

        $this->view->renderHtml('main.php',
        ['title' => 'Головна сторінка',
            'bossCards' => $bossCards,
            'managerCards' => $managerCards,
            'performerCards' => $performerCards,
            ]);
    }

    /**
     * @return void
     * @throws UnauthorizedException
     */
    public function addCard(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)) {
            try {
                Card::newCard($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('main.php',
                    ['title' => 'Помилка',
                        'error' => $e->getMessage()
                    ]);
                return;
            }
        }
    }
}