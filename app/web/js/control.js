/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./control.js":
/*!********************!*\
  !*** ./control.js ***!
  \********************/
/***/ ((module) => {

control = {
    run: function (callbackLoad) {
        jQuery(document).ready(function () {

            let socket = new WebSocket("ws://212.193.59.94:49102");

            socket.onopen = function () {
                control.statusRender("Соединение установлено");

                let dataTransfer = {
                    action: 'get_data',
                };
                socket.send(JSON.stringify(dataTransfer));
            };

            socket.onerror = function (error) {
                control.statusRender('Ошибка при соединении' + (error.message ? error.message : ""));
            };

            socket.onclose = function () {
                control.statusRender("Соединение разорвано");
            };

            socket.onmessage = function (event) {
                let data = JSON.parse(event.data);

                switch (data.action) {
                    case 'putData':
                        control.teamDataRender(data.data);
                        break;
                }
            };
        });
    },
    statusRender: function (text) {
        document.getElementById('status_connect').innerHTML = '<p>' + text + '</p>';
    },

    teamDataRender: function (data) {
        document.getElementById('container_team').innerHTML = mainUtilites.mustasheItemBuild('command-template', data);
    }
};

module.exports = control;


/***/ }),

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
control = __webpack_require__(/*! ./control.js */ "./control.js");
let callbackLoad = $.Callbacks();

control.run(callbackLoad);
})();

/******/ })()
;
//# sourceMappingURL=control.js.map