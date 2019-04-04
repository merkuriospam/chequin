var token_demo = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
//var ENTORNO = 'notebook';
//var ENTORNO = 'desktop';
var ENTORNO = 'server';

switch(ENTORNO) {
    case 'notebook':
		var BASE_URL = 'http://chequin.local';
		var APP_DEBUG = true;
		var token = token_demo;
        break;
    case 'desktop':
		var BASE_URL = 'http://rserver.local';
		var APP_DEBUG = true;
		var token = token_demo;
        break;
    default:
		var BASE_URL = 'http://chequin.ga';
		var APP_DEBUG = true;
		var token = null;
}

var MAX_HIST = 50;
var FB_APP_ID = 'xxxxxxxxxxxxxxxxxx';