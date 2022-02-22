<?php
/**
 * Created by PhpStorm.
 * User: DrMarlo
 * Date: 09.12.2020
 * Time: 21:02
 */

class GameData
{
    private static $gameTitleData = [
        'title' => 'Ночной дозор',
        'gameStartDate' => 1643575380,
        'answer_points' => 4,
        'divider_points_promt' => 2
    ];


    private static $stages = [
        [
            'location' => [
                'zone' => 'Ижевск',
                'loc_lat' => '56.8518',
                'loc_long' => '53.2407',
            ],
            'public' => [
                'difficult' => '1',
                'stageLimitTime' => 60,//1080
                'type_challenger' => 'Головоломка',
                'type_answer' => 'Коды',
                'count_answer' => 2,
                'count_prompt' => 2,
                'stage_challenge_text' => 'В обычный день когда ничего не предвещало беды, "Сис. админ" запутался 
                в проводах, как девочка в заплетенных волосах. И поэтому произошел сбой в системе. 
                А он не увидел сбой и изменения в природе.',
            ],
            'secret' => [
                'start_items' => [
                    [
                        'id' => 103456,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'title' => 'Распутайте',
                        'description' => '',
                        'src' => './images/src/scale_2400.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'prompt_items' => [
                    [
                        'id' => 103458,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 15,//480
                        'description' => '',
                        'src' => './images/src/scale_2400.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                    [
                        'id' => 103457,
                        'type' => 'text', //img|text|audio|in_game_obj
                        'timer' => 40,//720
                        'body' => 'Играют столбы у входа!', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'stage_answer' => '1T80N2|1T54N2',
            ],
        ],
        [
            'location' => [
                'zone' => 'Ижевск',
                'loc_lat' => '56.8518',
                'loc_long' => '53.2407',
            ],
            'public' => [
                'difficult' => '1',
                'stageLimitTime' => 60,//1200
                'type_challenger' => 'Головоломка',
                'type_answer' => 'Коды',
                'count_answer' => 10,
                'count_prompt' => 1,
                'prompt_timer' => '40',
                'stage_challenge_text' => 'Начинаются непредсказуемые осадки, с неба сыплется град 
                размером с теннисный мяч. Не надо медлить надо решиться и спасти мирных жителей 
                оставшихся на мосту. Координаты 56.851825, 53.240721.',
            ],
            'secret' => [
                'start_items' => [
                ],
                'prompt_items' => [
                    [
                        'id' => 103458,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 15,//480
                        'title' => 'Фото с вертолета',
                        'description' => 'Вам прислали фотографию снятую с вертолета быстрее летите на место жители ждут вашей помощи!',
                        'src' => './images/src/Scr_shot_2019-11-18-08-41-03-824.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'stage_answer' => '1T12N3|1T27N3|1T95N3|1Т98N3|1T57N3|1T67N3|1T73N3|1T65N3|1T32N3|1N48N3|
                1N32N3|1N08N3|1N33N3|1N45N3',
            ],
        ],
        [
            'location' => [
                'zone' => 'Ижевск',
                'loc_lat' => '56.8518',
                'loc_long' => '53.2407',
            ],
            'public' => [
                'difficult' => '1',
                'stageLimitTime' => 60,//1200
                'type_challenger' => 'Головоломка',
                'type_answer' => 'Коды',
                'count_answer' => 8,
                'count_prompt' => 2,
                'prompt_timer' => '600',
                'stage_challenge_text' => 'У вас получилось спасти большую часть жителей своего города, 
                но ваш товарищ В.И Кудинов не может найти выход в снежном сквере из за большого обилия 
                осадков над городом начинают формироваться  большие торнадо. Поспешите спасти ведь каждая 
                минута на счету.',
            ],
            'secret' => [
                'start_items' => [
                ],
                'prompt_items' => [
                    [
                        'id' => 103458,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 15,//480
                        'title' => 'Наводка',
                        'descryption' => 'Все замело остались видны только мусорки и части скамеек.',
                        'src' => './images/src/Scr_shot_2019-11-18-09-18-03-248.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'stage_answer' => '1T21N4|1T72N4|1T59N4|1Т84N4|1T73N4|1T66N4|1T31N4|1T56N4|1T27N4|1N80N4|
                1N16N4|1N01N4|1N42N4|1N83N4',
            ],
        ],
        [
            'location' => [
                'zone' => 'Ижевск',
                'loc_lat' => '56.8518',
                'loc_long' => '53.2407',
            ],
            'public' => [
                'difficult' => '1',
                'stageLimitTime' => 60,
                'type_challenger' => 'Головоломка',
                'type_answer' => 'Коды',
                'count_answer' => 6,
                'count_prompt' => 2,
                'prompt_timer' => '600',
                'stage_challenge_text' => 'Ваша команда смогла спасти товарища, но в больнице остался мальчик, 
                которому сделали операцию и он никак не может выбраться из нее и ждет вашей помощи. 
                Двигайтесь в указанное место.',
            ],
            'secret' => [
                'start_items' => [
                    [
                        'id' => 103456,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'prompt_items' => [
                    [
                        'id' => 103458,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 15,//480
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                    [
                        'id' => 103457,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 40,//720
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'stage_answer' => '1T12N5|1T93N5|1T57N5|1Т78N5|1Т42N5|1T83N5',
            ],
        ],
        [
            'location' => [
                'zone' => 'Ижевск',
                'loc_lat' => '56.8518',
                'loc_long' => '53.2407',
            ],
            'public' => [
                'difficult' => '1',
                'stageLimitTime' => 60,
                'type_challenger' => 'Головоломка',
                'type_answer' => 'Коды',
                'count_answer' => 1,
                'count_prompt' => 2,
                'prompt_timer' => '480',
                'stage_challenge_text' => 'Ваш сын находиться в это время в области которую на данный момент
                не могут эвакуировать. Он и его друзья застряли в ледовом дворце(курсивом). 
                Молодежь(курсивом) решает там остаться до выяснения дальнейших обстоятельств найдите того 
                кто вам поможет создавая шум что бы связаться с детьми ведь они вас не видят. 
                Дайте им надежду что вы за ними идете. (играет Агент).',
            ],
            'secret' => [
                'start_items' => [
                    [
                        'id' => 103456,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'prompt_items' => [
                    [
                        'id' => 103458,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 15,//480
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                    [
                        'id' => 103457,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 40,//720
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'stage_answer' => '1T67N6',
            ],
        ],
        [
            'location' => [
                'zone' => 'Ижевск',
                'loc_lat' => '56.8518',
                'loc_long' => '53.2407',
            ],
            'public' => [
                'difficult' => '1',
                'stageLimitTime' => 60,
                'type_challenger' => 'Головоломка',
                'type_answer' => 'Коды',
                'count_answer' => 3,
                'count_prompt' => 1,
                'prompt_timer' => '300',
                'stage_challenge_text' => 'Дети остались ждать дальнейших указаний. А у вас проблема ваш 
                товарищ провалился в торговый центр. Надо что то предпринять пока можно его вытащить
                моПед(2); патрон(3); ижевск(3); кий(2)',
            ],
            'secret' => [
                'start_items' => [
                    [
                        'id' => 103456,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'prompt_items' => [
                    [
                        'id' => 103458,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 15,//480
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                    [
                        'id' => 103457,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 40,//720
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'stage_answer' => '1T42N7|1T20N7|1T67N7',
            ],
        ],
        [
            'location' => [
                'zone' => 'Ижевск',
                'loc_lat' => '56.8518',
                'loc_long' => '53.2407',
            ],
            'public' => [
                'difficult' => '1',
                'stageLimitTime' => 60,
                'type_challenger' => 'Головоломка',
                'type_answer' => 'Слова',
                'count_answer' => 1,
                'count_prompt' => 0,
                'prompt_timer' => '0',
                'stage_challenge_text' => 'Вы потеряли одного своего товарища, брата. 
                Вы много знали о нем. Он начинал карьеру на автозаводе и занимался спортом. 
                Пока разгадываете загадки двигайтесь в сторону автозавода. Послушайте!
                Ведь, если …….
                зажигают —
                ……... — это кому-нибудь нужно?
                ………. — это необходимо,
                чтобы каждый ……..
                над …….
                загоралась хоть одна ……..?!',
            ],
            'secret' => [
                'start_items' => [
                    [
                        'id' => 103456,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'prompt_items' => [
                    [
                        'id' => 103458,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 15,//480
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                    [
                        'id' => 103457,
                        'type' => 'img', //img|text|audio|in_game_obj
                        'timer' => 40,//720
                        'body' => '/images/src/fewfwewf.png', //'/src/img3.png'|'Text'|'/src/audio3.mp3'|'js-module'
                    ],
                ],
                'stage_answer' => 'звезды|значит|вечер|крышами',
            ],
        ],
    ];

    public function getTitle()
    {
        return self::$gameTitleData['title'];
    }

    public function getStartDate()
    {
        return self::$gameTitleData['gameStartDate'];
    }

    public function getFormatStartDate()
    {
        return date('l jS \of F Y h:i:s A', self::$gameTitleData['gameStartDate']);
    }

    public function getPointsAnswer()
    {
        return self::$gameTitleData['answer_points'];
    }



    public function getStageCount()
    {
        return count(self::$stages);
    }

    public function getStaticStageData($stage)
    {
        return self::$stages[$stage-1]['public'];
    }

    public function getCodesStageData($stage)
    {
        return self::$stages[$stage-1]['secret']['stage_answer'];
    }

    public function getCodesStageCount($stage)
    {
        return self::$stages[$stage-1]['public']['count_answer'];
    }

    public function getTimeLimit($stage)
    {
        return self::$stages[$stage-1]['public']['stageLimitTime'];
    }

    public function getStageStartItem($stage)
    {
        return self::$stages[$stage-1]['secret']['start_items'];
    }

    public function getStagePromptItem($stage)
    {
        return self::$stages[$stage-1]['secret']['prompt_items'];
    }

}