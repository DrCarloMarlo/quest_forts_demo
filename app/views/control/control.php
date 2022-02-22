<?php
/**
 * Created by PhpStorm.
 * User: carly
 * Date: 14.02.2022
 * Time: 18:29
 */
?>
<div>
    <div class="row">
        <div id="status_connect"></div>
        <div class="flow-control">
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="row form-container">
        <form id="send-global">
            <div><p>Глобальное сообщение</p></div>
            <input type="text" placeholder="сообщение">
            <button class="btn btn-success" type="submit">Отправить</button>
        </form>
        <form id="send-team">
            <div><p>Сообщение команде</p></div>
            <input type="text" placeholder="команда">
            <input type="text" placeholder="сообщение">
            <button class="btn btn-success" type="submit">Отправить</button>
        </form>
    </div>
    <div id="container_team" class="row">

    </div>
    <div class="hidden-template">
        <div class="container" id="command-template">
            {{#.}}
            <div class="col-lg-4 col-md-4 col-xs-4 card">
                <div>
                    <div class="params">
                        <div>
                            <p>Команда</p>
                        </div>
                        <div>
                            {{team_id}}
                        </div>
                    </div>
                    <div class="params">
                        <div>
                            <p>Активность</p>
                        </div>
                        <div>
                            {{game}}
                        </div>
                    </div>
                    <div class="params">
                        <div>
                            <p>Игроки</p>
                        </div>
                        {{#players}}
                        <div>
                            {{name}}
                        </div>
                        <div>
                            {{active}}
                        </div>
                        {{/players}}
                    </div>
                    <div class="params">
                        <div>
                            <p>Этап</p>
                        </div>
                        <div>
                            {{stage}}
                        </div>
                    </div>
                    <div class="params">
                        <div>
                            <p>Начали этап</p>
                        </div>
                        <div>
                            {{stageStartTime}}
                        </div>
                    </div>
                    <div class="params">
                        <div>
                            <p>Валидный ввод </p>
                        </div>
                        <div>
                            {{valid_enter}}
                        </div>
                    </div>
                    <div class="params">
                        <div>
                            <p>Счет</p>
                        </div>
                        <div>
                            {{team_scores}}
                        </div>
                    </div>
                    <div class="params">
                        <div>
                            <p>Завершили игру</p>
                        </div>
                        <div>
                            {{gameComplete}}
                        </div>
                    </div>
                </div>
            </div>
            {{/.}}
        </div>
    </div>
</div>
