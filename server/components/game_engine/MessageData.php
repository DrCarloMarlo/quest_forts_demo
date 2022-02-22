<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 19.01.2022
 * Time: 19:23
 */

class MessageData
{
    private static $gameMessage = [
        'terminateAuthConnMessage' =>
            [
                'type' => 'system',
                'action' => 'message',
                'params' => false,
                'message' =>'Время ожидания авторизации превышено, соединение уничтожено',
            ],
        'nonAuthStartMessage' =>
            [
                'type' => 'system',
                'action' => 'message',
                'params' => false,
                'message' =>'Для входа в игру введите ключ игрока:',
            ],
        'gameNotStart' =>
            [
                'type' => 'system',
                'action' => 'message',
                'params' => true,
                'message' =>'Игра {param[0]}, еще не началась, старт в {param[1]}',
            ],
        'gameGreetings' =>
            [
                'type' => 'system',
                'action' => 'message',
                'params' => true,
                'message' =>'Добро пожаловать в {param[0]} Вы в игре!',
            ],
        'activationTimeLoss' =>
            [
                'type' => 'system',
                'action' => 'message',
                'params' => false,
                'message' =>'Вы опоздали! Время активации в игре вышло! Свяжитесь с организаторами!',
            ],
        'completeGame' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => true,
                'message' =>'Ваша команда успешно завершила игру! Ваш счет: {param[0]}!',
            ],
        'completeStage' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => false,
                'message' =>'Ваша команда успешно завершила этап! Приступайте к следующему!',
            ],
        'wrongCode' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => false,
                'message' =>'Введен неверный код!',
            ],
        'validCode' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => true,
                'message' =>'Введен верный код, {param[0]}! Введено: {param[1]}.',
            ],
        'repeatCode' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => true,
                'message' =>'Код: {param[0]} уже был найден!',
            ],
        'lossStageTime' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => false,
                'message' =>'Игроки время на этап вышло! Приступайте к следующему!',
            ],
        'secureItemAvailable' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => false,
                'message' =>'Доступен новый предмет!',
            ],
        'changeNickname' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => true,
                'message' =>'Ваш ник изменен на: {param[0]}.',
            ],
        'changeNicknameFails' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => false,
                'message' =>'Ваш ник должен быть не короче, 3-х и не длиннее 16-ти символов.',
            ],
        'alertPlayer' =>
            [
                'type' => 'server',
                'action' => 'message',
                'params' => true,
                'message' =>'SOS! Игрок {param[0]} запрашивает помощь.',
            ],
    ];

    public function getMessageByName($messageName) {
        return self::$gameMessage[$messageName];
    }
}