/*
	"help":                     "help",    			// #help
    "search/:query":            "search",  			// #search/kiwis
    "search/:query/page/:page": "search"   			// #search/kiwis/page/7
	"tag/:tagid/p:page": 		"muestraEtiqueta",  // #tag/perro/p5
	"download/*file": 			"descargar"        	// #download/path/to/file.txt
*/
var enrutador;
var url_actual;

Enrutador = Backbone.Router.extend({
	routes:{
		""                  : "inicio",
        "inicio"            : "inicio",
		/*"perfil"            : "perfil",
		"rutas/:id"         : "ruta",
        "registro"          : "registro",
        "ficha"             : "ficha",
        "puntos"            : "puntos",*/
	},
    inicio: function() { 
    	app.inicio_render();
    },
    /*perfil: function(){ 
    	app.perfil_render();
        API.close();
    },
    registro: function(){ 
        app.registro_render();
        API.close();
    },
    ruta: function(id){ 
    	app.ruta_render(id);
        API.close();
    },
    ficha: function(){ 
        app.ficha_render();
        API.close();
    },
    puntos: function(){ 
        app.puntos_render();
        API.close();
    },
    muestraEtiqueta: function(tagid, page){ 
    	debug('capture tag ' + tagid); 
    },
    descargar: function(file){ 
    	debug('capture descargar'); 
    },*/
 });

_.extend(Backbone.Router.prototype, {
    refresh: function () { var tmp = Backbone.history.fragment; this.navigate(tmp + (new Date).getTime()); this.navigate(tmp, { trigger: true }); }
});