/**
 * backbone model definitions for Certificados FAROL
 */

/**
 * Use emulated HTTP if the server doesn't support PUT/DELETE or application/json requests
 */
Backbone.emulateHTTP = false;
Backbone.emulateJSON = false;

var model = {};

/**
 * long polling duration in miliseconds.  (5000 = recommended, 0 = disabled)
 * warning: setting this to a low number will increase server load
 */
model.longPollDuration = 0;

/**
 * whether to refresh the collection immediately after a model is updated
 */
model.reloadCollectionOnModelUpdate = true;


/**
 * a default sort method for sorting collection items.  this will sort the collection
 * based on the orderBy and orderDesc property that was used on the last fetch call
 * to the server. 
 */
model.AbstractCollection = Backbone.Collection.extend({
	totalResults: 0,
	totalPages: 0,
	currentPage: 0,
	pageSize: 0,
	orderBy: '',
	orderDesc: false,
	lastResponseText: null,
	lastRequestParams: null,
	collectionHasChanged: true,
	
	/**
	 * fetch the collection from the server using the same options and 
	 * parameters as the previous fetch
	 */
	refetch: function() {
		this.fetch({ data: this.lastRequestParams })
	},
	
	/* uncomment to debug fetch event triggers
	fetch: function(options) {
            this.constructor.__super__.fetch.apply(this, arguments);
	},
	// */
	
	/**
	 * client-side sorting baesd on the orderBy and orderDesc parameters that
	 * were used to fetch the data from the server.  Backbone ignores the
	 * order of records coming from the server so we have to sort them ourselves
	 */
	comparator: function(a,b) {
		
		var result = 0;
		var options = this.lastRequestParams;
		
		if (options && options.orderBy) {
			
			// lcase the first letter of the property name
			var propName = options.orderBy.charAt(0).toLowerCase() + options.orderBy.slice(1);
			var aVal = a.get(propName);
			var bVal = b.get(propName);
			
			if (isNaN(aVal) || isNaN(bVal)) {
				// treat comparison as case-insensitive strings
				aVal = aVal ? aVal.toLowerCase() : '';
				bVal = bVal ? bVal.toLowerCase() : '';
			} else {
				// treat comparision as a number
				aVal = Number(aVal);
				bVal = Number(bVal);
			}
			
			if (aVal < bVal) {
				result = options.orderDesc ? 1 : -1;
			} else if (aVal > bVal) {
				result = options.orderDesc ? -1 : 1;
			}
		}
		
		return result;

	},
	/**
	 * override parse to track changes and handle pagination
	 * if the server call has returned page data
	 */
	parse: function(response, options) {

		// the response is already decoded into object form, but it's easier to
		// compary the stringified version.  some earlier versions of backbone did
		// not include the raw response so there is some legacy support here
		var responseText = options && options.xhr ? options.xhr.responseText : JSON.stringify(response);
		this.collectionHasChanged = (this.lastResponseText != responseText);
		this.lastRequestParams = options ? options.data : undefined;
		
		// if the collection has changed then we need to force a re-sort because backbone will
		// only resort the data if a property in the model has changed
		if (this.lastResponseText && this.collectionHasChanged) this.sort({ silent:true });
		
		this.lastResponseText = responseText;
		
		var rows;

		if (response.currentPage) {
			rows = response.rows;
			this.totalResults = response.totalResults;
			this.totalPages = response.totalPages;
			this.currentPage = response.currentPage;
			this.pageSize = response.pageSize;
			this.orderBy = response.orderBy;
			this.orderDesc = response.orderDesc;
		} else {
			rows = response;
			this.totalResults = rows.length;
			this.totalPages = 1;
			this.currentPage = 1;
			this.pageSize = this.totalResults;
			this.orderBy = response.orderBy;
			this.orderDesc = response.orderDesc;
		}

		return rows;
	}
});

/**
 * Certificado Backbone Model
 */
model.CertificadoModel = Backbone.Model.extend({
	urlRoot: 'api/certificado',
	idAttribute: 'idCertificado',
	idCertificado: '',
	dataEmissao: '',
	livro: '',
	folha: '',
	codigo: '',
	idUsuario: '',
	defaults: {
		'idCertificado': null,
		'dataEmissao': '',
		'livro': '',
		'folha': '',
		'codigo': '',
		'idUsuario': ''
	}
});

/**
 * Certificado Backbone Collection
 */
model.CertificadoCollection = model.AbstractCollection.extend({
	url: 'api/certificados',
	model: model.CertificadoModel
});

/**
 * Configuracao Backbone Model
 */
model.ConfiguracaoModel = Backbone.Model.extend({
	urlRoot: 'api/configuracao',
	idAttribute: 'idConfiguracao',
	idConfiguracao: '',
	nomeInstituicao: '',
	imagemLogo: '',
	cnpj: '',
	telefone: '',
	defaults: {
		'idConfiguracao': null,
		'nomeInstituicao': '',
		'imagemLogo': '',
		'cnpj': '',
		'telefone': ''
	}
});

/**
 * Configuracao Backbone Collection
 */
model.ConfiguracaoCollection = model.AbstractCollection.extend({
	url: 'api/configuracoes',
	model: model.ConfiguracaoModel
});

/**
 * Evento Backbone Model
 */
model.EventoModel = Backbone.Model.extend({
	urlRoot: 'api/evento',
	idAttribute: 'idEvento',
	idEvento: '',
	nome: '',
	local: '',
	data: '',
	duracao: '',
	defaults: {
		'idEvento': null,
		'nome': '',
		'local': '',
		'data': new Date(),
		'duracao': ''
	}
});

/**
 * Evento Backbone Collection
 */
model.EventoCollection = model.AbstractCollection.extend({
	url: 'api/eventos',
	model: model.EventoModel
});

/**
 * ModeloCertificado Backbone Model
 */
model.ModeloCertificadoModel = Backbone.Model.extend({
	urlRoot: 'api/modelocertificado',
	idAttribute: 'idModeloCertificado',
	idModeloCertificado: '',
	nome: '',
	textoParticipante: '',
	textoPalestrante: '',
	arquivoCss: '',
	elementos: '',
	defaults: {
		'idModeloCertificado': null,
		'nome': '',
		'textoParticipante': '',
		'textoPalestrante': '',
		'arquivoCss': '',
		'elementos': ''
	}
});

/**
 * ModeloCertificado Backbone Collection
 */
model.ModeloCertificadoCollection = model.AbstractCollection.extend({
	url: 'api/modelocertificados',
	model: model.ModeloCertificadoModel
});

/**
 * Palestra Backbone Model
 */
model.PalestraModel = Backbone.Model.extend({
	urlRoot: 'api/palestra',
	idAttribute: 'idPalestra',
	idPalestra: '',
	nome: '',
	data: '',
	cargaHoraria: '',
	proprioEvento: '',
	idEvento: '',
	idModeloCertificado: '',
	defaults: {
		'idPalestra': null,
		'nome': '',
		'data': new Date(),
		'cargaHoraria': '',
		'proprioEvento': '',
		'idEvento': '',
		'idModeloCertificado': ''
	}
});

/**
 * Palestra Backbone Collection
 */
model.PalestraCollection = model.AbstractCollection.extend({
	url: 'api/palestras',
	model: model.PalestraModel
});

/**
 * PalestraPalestrante Backbone Model
 */
model.PalestraPalestranteModel = Backbone.Model.extend({
	urlRoot: 'api/palestrapalestrante',
	idAttribute: 'id',
	id: '',
	idPalestrante: '',
	idPalestra: '',
	idCertificado: '',
	defaults: {
		'id': null,
		'idPalestrante': '',
		'idPalestra': '',
		'idCertificado': ''
	}
});

/**
 * PalestraPalestrante Backbone Collection
 */
model.PalestraPalestranteCollection = model.AbstractCollection.extend({
	url: 'api/palestrapalestrantes',
	model: model.PalestraPalestranteModel
});

/**
 * PalestraParticipante Backbone Model
 */
model.PalestraParticipanteModel = Backbone.Model.extend({
	urlRoot: 'api/palestraparticipante',
	idAttribute: 'id',
	id: '',
	presenca: '',
	idParticipante: '',
	idPalestra: '',
	idCertificado: '',
	defaults: {
		'id': null,
		'presenca': '',
		'idParticipante': '',
		'idPalestra': '',
		'idCertificado': ''
	}
});

/**
 * PalestraParticipante Backbone Collection
 */
model.PalestraParticipanteCollection = model.AbstractCollection.extend({
	url: 'api/palestraparticipantes',
	model: model.PalestraParticipanteModel
});

/**
 * Palestrante Backbone Model
 */
model.PalestranteModel = Backbone.Model.extend({
	urlRoot: 'api/palestrante',
	idAttribute: 'idPalestrante',
	idPalestrante: '',
	nome: '',
	email: '',
	cpf: '',
	cargo: '',
	imagemAssinatura: '',
	defaults: {
		'idPalestrante': null,
		'nome': '',
		'email': '',
		'cpf': '',
		'cargo': '',
		'imagemAssinatura': ''
	}
});

/**
 * Palestrante Backbone Collection
 */
model.PalestranteCollection = model.AbstractCollection.extend({
	url: 'api/palestrantes',
	model: model.PalestranteModel
});

/**
 * Participante Backbone Model
 */
model.ParticipanteModel = Backbone.Model.extend({
	urlRoot: 'api/participante',
	idAttribute: 'idParticipante',
	idParticipante: '',
	nome: '',
	email: '',
	cpf: '',
	defaults: {
		'idParticipante': null,
		'nome': '',
		'email': '',
		'cpf': ''
	}
});

/**
 * Participante Backbone Collection
 */
model.ParticipanteCollection = model.AbstractCollection.extend({
	url: 'api/participantes',
	model: model.ParticipanteModel
});

/**
 * Usuario Backbone Model
 */
model.UsuarioModel = Backbone.Model.extend({
	urlRoot: 'api/usuario',
	idAttribute: 'idUsuario',
	idUsuario: '',
	nome: '',
	email: '',
	login: '',
	senha: '',
	tipoUsuario: '',
	defaults: {
		'idUsuario': null,
		'nome': '',
		'email': '',
		'login': '',
		'senha': '',
		'tipoUsuario': ''
	}
});

/**
 * Usuario Backbone Collection
 */
model.UsuarioCollection = model.AbstractCollection.extend({
	url: 'api/usuarios',
	model: model.UsuarioModel
});

