/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "../proprietary/main-utillites.js":
/*!****************************************!*\
  !*** ../proprietary/main-utillites.js ***!
  \****************************************/
/***/ ((module) => {

mainUtilites ={
    ajaxTemplate: function (url, data, callbackLoad) {
        let response = {};

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success:
                function(answer){
                    response.data = answer;
                    callbackLoad.fire(response.data, callbackLoad);
                    return;
                }
        });
        return response;
    },

    setCookie: function (name, value, options = {}) {
        options = {
            path: '/',
        };
        if (options.expires instanceof Date) {
            options.expires = options.expires.toUTCString();
        }
        let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
        for (let optionKey in options) {
            updatedCookie += "; " + optionKey;
            let optionValue = options[optionKey];
            if (optionValue !== true) {
                updatedCookie += "=" + optionValue;
            }
        }
        document.cookie = updatedCookie;
    },

    getCookie: function (name) {
        let dc = document.cookie;
        let prefix = name + "=";
        let begin = dc.indexOf("; " + prefix);
        if (begin === -1) {
            begin = dc.indexOf(prefix);
            if (begin !== 0) return null;
        } else {
            begin += 2;
            let end = document.cookie.indexOf(";", begin);
            if (end === -1) {
                end = dc.length;
            }
        }
        return decodeURI(dc.substring(begin + prefix.length, end));
    },

    waitForElementToDisplay: function (selector, callback, checkFrequencyInMs, timeoutInMs) {
    let startTimeInMs = Date.now();
    (function loopSearch() {
        if (document.querySelector(selector) !== null) {
            callback();
            return;
        }
        else {
            setTimeout(function () {
                if (timeoutInMs && Date.now() - startTimeInMs > timeoutInMs)
                    return;
                loopSearch();
            }, checkFrequencyInMs);
        }
    })();
    },

    mustasheItemABuild: function (blockID, templateID, data, partTemplateID = null) {
        $.ajax({
            url: templateID,
            type: 'GET',
            success:
                function (template) {
                    document.getElementById(blockID).innerHTML = Mustache.render(template, data, {partial: partTemplateID});
                }
        });
    },

    mustasheItemBuild: function (templateID, data, partTemplateID = null) {
        let template = document.getElementById(templateID).innerHTML;

        return Mustache.render(template, data, { partial: partTemplateID });
    },

    chunkCache: function (chunckDOM, data) {
        let dataCache = JSON.stringify(data);
        document.getElementById(chunckDOM).removeAttribute('params');
        document.getElementById(chunckDOM).setAttribute('params', dataCache)
    },

    chunkCacheRead: function (chunckDOM) {
        let elem = document.getElementById(chunckDOM);
        if (elem.hasAttribute('params')) {
            return JSON.parse(elem.getAttribute('params'));
        } else {
            return false;
        }
    },

    chunkCacheSelectArray: function (chunckDOM, selector, value) {
        let index;
         mainUtilites.chunkCacheRead(chunckDOM).forEach(function (item, i, arr) {
            console.log(item[selector]);
            console.log(value);
            if(item[selector] === value) {
                console.log(i);
                index = i;
                return;
            }
        });
        return index;
    },

    chunkCacheReadArray: function (chunckDOM, index) {
        let itemData;
        mainUtilites.chunkCacheRead(chunckDOM).forEach(function (item, i, arr) {
            if(i === index) {
                itemData = item;
                return;
            }
        });
        return itemData;
    },

    chunkCacheWrite: function (chunckDOM, param) {
        let cacheArr = mainUtilites.chunkCacheRead(chunckDOM);
        document.getElementById(chunckDOM).removeAttribute('params');

        cacheArr.push(param);

        console.log(cacheArr);
        mainUtilites.chunkCache(chunckDOM, cacheArr);
    },

    chunkCacheWriteArray: function (chunckDOM, arr) {
        let cacheArr = mainUtilites.chunkCacheRead(chunckDOM);
        document.getElementById(chunckDOM).removeAttribute('params');

        for (let i = 0; i < arr.length; i++) {
            cacheArr.push(arr[i]);
        }

        console.log(cacheArr);
        mainUtilites.chunkCache(chunckDOM, cacheArr);
    },

    chunkCacheDeleteArray: function (chunckDOM, index) {
        let newCashe = [];
        mainUtilites.chunkCacheRead(chunckDOM).forEach(function (item, i, arr) {
            if(i !== index) {
                newCashe.push(item);
            }
        });
        mainUtilites.chunkCache(chunckDOM, newCashe);
    },

    /*chunkCacheWrite: function (chunckDOM, paramName, paramValue) {
        let dataCache = JSON.stringify(data);

        document.getElementById(chunckDOM).getAttribute('params');
        dataCache = JSON.parse(dataCache);
        if (!(paramName in dataCache)) {
            dataCache.paramName = paramValue;
            dataCache = JSON.stringify(data);
            document.getElementById(chunckDOM).setAttribute('params', dataCache);
        } else {
            return false;
        }
    },*/
    getGetParams: function (raw) {
        let params;
            raw.search.replace('?','').split('&').reduce(
                function(p,e){
                    let a = e.split('=');
                    p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);

                    return p;
                },
            );
        return params;
    },
    unixTimeFormat: function(unix_timestamp) {
        // Create a new JavaScript Date object based on the timestamp
        // multiplied by 1000 so that the argument is in milliseconds, not seconds.
        let date = new Date(unix_timestamp * 1000);
        // Hours part from the timestamp
        let hours = date.getHours();
        // Minutes part from the timestamp
        let minutes = "0" + date.getMinutes();
        // Seconds part from the timestamp
        let seconds = "0" + date.getSeconds();

        // Will display time in 10:30:23 format
        if (hours === 0) {
            return minutes.substr(-2) + ':' + seconds.substr(-2);
        } else {
            return hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
        }
    }
};

module.exports = mainUtilites;





/***/ }),

/***/ "./quest-module.js":
/*!*************************!*\
  !*** ./quest-module.js ***!
  \*************************/
/***/ ((module) => {

quest_basic = {
    run: function (callbackLoad) {
        jQuery(document).ready(function () {

            let socket = new WebSocket("ws://212.193.59.94:49101"); //49101

            //инициализация UI меню
            userUI.topUIMenuInit(socket, callbackLoad);
            userUI.gameUIMenuInit();
            userUI.gameUImenuBtnBlock();

            userUI.authorizUIBtnBlock(socket);

            socket.onopen = function () {
                quest_basic.message("Соединение установлено");
            };

            socket.onerror = function (error) {
                quest_basic.message('Ошибка при соединении' + (error.message ? error.message : ""));
            };

            socket.onclose = function () {
                quest_basic.message("Соединение разорвано");
                quest_basic.message("Обратитесь к организаторам");
            };

            socket.onmessage = function (event) {
                let transmitting = JSON.parse(event.data);

                if (transmitting.type === 'system') {
                    switch (transmitting.action) {
                        case 'message':
                            localStorage.setItem('timerOffset', parseInt(Date.now() / 1000) - transmitting.timestamp);

                            quest_basic.message(transmitting.data);
                            break;
                        case 'cookie':
                            for (let key in transmitting.data.message) {
                                if (!transmitting.data.message.hasOwnProperty(key)) continue;
                                mainUtilites.setCookie(key, transmitting.data.message[key], {secure: true, 'max-age': 3600});
                            }
                            break;
                        case 'static':
                            setTimeout(quest_basic.timeServerSync, 1000);
                            localStorage.setItem('timerOffset', parseInt(Date.now() / 1000) - transmitting.timestamp);

                            quest_basic.dataTransferFormed(transmitting.data);
                            timeLoop = true;

                            document.getElementById('stage').classList.remove("infinity");
                            let sendUserInputDom = document.getElementById('send-auth');
                            //sendUserInputDom.removeEventListener('click', listener, false);

                            userUI.mainUIBtnBlock(socket);

                            document.getElementById('task').click();
                            break;
                        case 'items':
                            localStorage.setItem('timerOffset', parseInt(Date.now() / 1000) - transmitting.timestamp);
                            console.log(transmitting.data.items);
                            mainUtilites.chunkCacheWrite('inventory', transmitting.data.items);
                            document.getElementById('inventory').click();
                            break;
                    }
                }
                if (transmitting.type === 'server') {
                    switch (transmitting.action) {
                        case 'message':
                            console.log(transmitting);
                            quest_basic.message(transmitting.data);
                            break;
                    }
                }
            };
        });
    },

    timersFormat: function(timestamp) {
        return Math.floor(timestamp / 60) + ' : ' + timestamp % 60;
    },

    message: function(text) {
        let div = document.createElement('div');
        div.innerHTML = text;
        document.getElementById('game-message').prepend(div);
    },

    timeServerSync: function() {
        let timerOffset = 0;
        if (localStorage.getItem('timerOffset') !== null) {
            timerOffset = localStorage.getItem('timerOffset');
        }

        let startstage = JSON.parse(localStorage.getItem("startstage"));
        let stageLimitTime = JSON.parse(localStorage.getItem("stageLimitTime"));
        let realTime = (stageLimitTime - (Math.round(Date.now() / 1000) - startstage)) + parseInt(timerOffset);
        if (realTime >= 0) {
            document.getElementById('timerSync').innerHTML = (quest_basic.timersFormat(realTime));
            setTimeout(quest_basic.timeServerSync, 1000);
        } else {
            document.getElementById('timerSync').innerHTML = '00 : 00'
        }
    },

    dataTransferFormed: function(dataTransferArray) {
        document.getElementById('stage').innerHTML = dataTransferArray.stage;
        document.getElementById('startTime').innerHTML = mainUtilites.unixTimeFormat(dataTransferArray.stageStartTime);
        startStageTime = JSON.stringify(dataTransferArray.stageStartTime);
        stageLimitTime = JSON.stringify(dataTransferArray.stageData.stageLimitTime);
        localStorage.setItem('startstage', startStageTime);
        localStorage.setItem('stageLimitTime', stageLimitTime);
        mainUtilites.chunkCache('task', dataTransferArray.stageData);
        mainUtilites.chunkCache('paramstask', dataTransferArray.stageData);
        mainUtilites.chunkCache('inventory', dataTransferArray.itemData);
        console.log(dataTransferArray.itemPromptData);
        if (dataTransferArray.itemPromptData.length > 0) {
            mainUtilites.chunkCacheWriteArray('inventory', dataTransferArray.itemPromptData);
        }
    },

    chunkParamsRender: function (data) {
        let chunckDOM = 'parameters';
        let template = 'parameters-template';

        document.getElementById(chunckDOM).innerHTML = mainUtilites.mustasheItemBuild(template, data);

        mainUtilites.chunkCache(chunckDOM, data);
    },
};

module.exports = quest_basic;

/***/ }),

/***/ "./userUI.js":
/*!*******************!*\
  !*** ./userUI.js ***!
  \*******************/
/***/ ((module) => {

userUI = {
    topUIMenuInit: function (socket, callbackLoad) {
        document.getElementById('topUI_box').addEventListener('click', function (event) {
            event.preventDefault();

            let target = event.target;
            while (target.id !== 'topUI_box') {
                if (target.classList.contains('nav__item-ico')) {
                    switch (target.id) {
                        case 'statistic':
                            let UserInput = {
                                action: 'statistic',
                            };
                            socket.send(JSON.stringify(UserInput));
                            let event = new Event('click');
                            console.log(document.getElementById('nav__btn'));
                            document.getElementById('nav__btn').dispatchEvent(new Event('click'));
                            break;
                        case 'change_nick':
                            userUI.chnNickUIBtnBlock(socket);
                            break;
                        case 'FAQ':
                            document.getElementById('hd__modal_container').innerHTML = '';
                            mainUtilites.ajaxTemplate('/site/helpdesk', null, callbackLoad);
                            callbackLoad.add(userUI.helpdeskCreate);
                            break;
                        case 'sos_alert':
                            UserInput = {
                                action: 'sos_alert',
                            };
                            socket.send(JSON.stringify(UserInput));
                            break;
                    }
                }
                target = target.parentNode;
            }
        });
    },
    gameUIMenuInit: function () {
        let $activeWidth, $defaultMarginLeft, $defaultPaddingLeft, $firstChild, $line, $navListItem;
        $line = $('#line');
        $navListItem = $('.selector');
        $activeWidth = $('.active-nav').width();
        $firstChild = $('.selector:first-child');
        $defaultMarginLeft = parseInt($('.selector:first-child').next().css('marginLeft').replace(/\D/g, ''));
        $defaultPaddingLeft = parseInt($('#gamemenu > div').css('padding-left').replace(/\D/g, ''));
        $line.width($activeWidth + 'px');
        $line.css('marginLeft', $defaultPaddingLeft + 'px');
        $navListItem.click(function () {
            let $activeNav, $currentIndex, $currentOffset, $currentWidth, $initWidth, $marginLeftToSet, $this;
            $this = $(this);
            $activeNav = $('.active-nav');
            $currentWidth = $activeNav.width();
            $currentOffset = $activeNav.position().left;
            $currentIndex = $activeNav.index();
            $activeNav.removeClass('active-nav');
            $this.addClass('active-nav');
            if ($this.is($activeNav)) {
                return 0;
            } else {
                if ($this.index() > $currentIndex) {
                    if ($activeNav.is($firstChild)) {
                        $initWidth = $defaultMarginLeft + $this.width() + $this.position().left - $defaultPaddingLeft;
                    } else {
                        $initWidth = $this.position().left + $this.width() - $currentOffset;
                    }
                    $marginLeftToSet = $this.position().left + $defaultMarginLeft + 'px';
                    $line.width($initWidth + 'px');
                    return setTimeout(function () {
                        $line.css('marginLeft', $marginLeftToSet);
                        return $line.width($this.width() + 'px');
                    }, 175);
                } else {
                    if ($this.is($firstChild)) {
                        $initWidth = $currentOffset - $defaultPaddingLeft + $defaultMarginLeft + $currentWidth;
                        $marginLeftToSet = $this.position().left;
                    } else {
                        $initWidth = $currentWidth + $currentOffset - $this.position().left;
                        $marginLeftToSet = $this.position().left + $defaultMarginLeft;
                    }
                    $line.css('marginLeft', $marginLeftToSet);
                    $line.width($initWidth + 'px');
                    return setTimeout(function () {
                        return $line.width($this.width() + 'px');
                    }, 175);
                }
            }
        });
    },
    gameUImenuBtnBlock: function () {
        document.getElementById('gamemenu').addEventListener('click', function (event) {
            event.preventDefault();
            let target = event.target;
            while (target.id !== 'gamemenu') {
                if (target.classList.contains('selector')) {
                    document.getElementById('gamemenu').getElementsByClassName('active');
                    target.classList.add('active');

                    let data = mainUtilites.chunkCacheRead(target.id);
                    document.getElementById('selectorView').innerHTML = mainUtilites.mustasheItemBuild(target.id + '-template', data);
                    if (target.id === 'inventory') {
                        let itemRender = '';
                        data.forEach(function (item, i, arr) {
                            itemRender += mainUtilites.mustasheItemBuild(item.type + '_ItemInventory-template', item);
                        });
                        document.getElementById('inventory-grid').innerHTML = itemRender;
                    }
                    userUI.modalUIInit('inventory-grid');
                    return;
                }
                target = target.parentNode;
            }
        });
    },
    modalUIInit: function (DOM_id) {
        document.getElementById(DOM_id).addEventListener('click', function (event) {
            event.preventDefault();

            let target = event.target;
            while (target.id !== DOM_id) {
                console.log(target);
                if (target.classList.contains('js-click-modal')) {
                    document.getElementById(target.id+'__modal').classList.add('modal_box_open');
                }
                if (target.classList.contains('js-close-modal')) {
                    document.getElementById(target.id).classList.remove('modal_box_open');
                }
                target = target.parentNode;
            }
        });
    },
    authorizUIBtnBlock: function (socket) {
        document.getElementById('send-auth').addEventListener('click', function (event) {
            event.preventDefault();

            let UserInput = {
                action: 'authorize',
                data: {
                    user_id: document.getElementById('form-input').value,
                }
            };

            document.getElementById('form-input').value = '';

            socket.send(JSON.stringify(UserInput));
        });
    },
    mainUIBtnBlock: function (socket) {
        document.getElementById('btn-block').innerHTML = mainUtilites.mustasheItemBuild('btn-template', null);

        let sendUserAnswerDom = document.getElementById('send-answer');
        let sendUserMessageDom = document.getElementById('send-message');
        sendUserAnswerDom.addEventListener('click', function (event) {
            event.preventDefault();

            let UserInput = {
                action: 'stage_answer',
                data: {
                    userAnswer: document.getElementById('form-input').value,
                }
            };

            document.getElementById('form-input').value = '';

            socket.send(JSON.stringify(UserInput));
        });
        sendUserMessageDom.addEventListener('click', function (event) {
            event.preventDefault();

            let UserInput = {
                action: 'chat_message',
                data: {
                    message: document.getElementById('form-input').value,
                }
            };

            document.getElementById('form-input').value = '';

            socket.send(JSON.stringify(UserInput));
        });
    },
    chnNickUIBtnBlock: function (socket) {
        document.getElementById('btn-block').innerHTML = mainUtilites.mustasheItemBuild('btn_chng_nick-template', null);

        document.getElementById('send-nick').addEventListener('click', function (event) {
            event.preventDefault();
            let UserInput = {
                action: 'change_nick',
                data: {
                    user_nick: document.getElementById('form-input').value,
                }
            };
            document.getElementById('form-input').value = '';
            socket.send(JSON.stringify(UserInput));
            userUI.mainUIBtnBlock(socket);
        });
        document.getElementById('cancel').addEventListener('click', function (event) {
            event.preventDefault();

            userUI.mainUIBtnBlock(socket);
        });
    },
    helpdeskCreate: function (data, callbackLoad) {
        let dataBuild = {
            id: 'helpdesk',
            title: 'FAQ',
            description: data
        };
        mainUtilites.mustasheItemABuild('hd__modal_container', '/site/get-template/?tmp=modal', dataBuild);
        //инициализация модального окна
        userUI.modalUIInit('hd__modal_container');
        mainUtilites.waitForElementToDisplay("#helpdesk",function(){document.getElementById('helpdesk').click();},10,900);
        callbackLoad.empty();
    },
};

module.exports = userUI;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src_build.js ***!
  \**********************/
generalUtility = __webpack_require__(/*! ../proprietary/main-utillites.js */ "../proprietary/main-utillites.js");
userUI = __webpack_require__(/*! ./userUI.js */ "./userUI.js");
__webpack_require__(/*! ./quest-module.js */ "./quest-module.js");

let callbackLoad = $.Callbacks();

quest_basic.run(callbackLoad);
})();

/******/ })()
;
//# sourceMappingURL=build.js.map