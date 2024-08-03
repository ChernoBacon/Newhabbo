var defaults = {
	defaultFile: '',
	maxFileSize: 0,
	minWidth: 25,
	maxWidth: 45,
	minHeight: 25,
	maxHeight: 45,
	showRemove: true,
	showLoader: true,
	showErrors: true,
	errorTimeout: 3000,
	errorsPosition: 'overlay',
	imgFileExtensions: ['gif'],
	maxFileSizePreview: "1M",
	allowedFormats: ['portrait', 'square', 'landscape'],
	allowedFileExtensions: ['gif'],
	messages: {
		'default': 'Arraste e solte um arquivo aqui ou clique aqui',
		'replace': 'Arraste e solte um arquivo aqui ou clique para substituir',
		'remove':  'Remover',
		'error':   'Opa, algo errado aconteceu.            '
	},
	error: {
		'fileSize': 'O tamanho do arquivo é muito grande. ({{ value }} max).',
		'minWidth': 'A largura da imagem é muito pequena ({{ value }}}px min).',
		'maxWidth': 'A largura da imagem é muito grande ({{ value }}}px max).',
		'minHeight': 'A altura da imagem é muito pequena ({{ value }}}px min).',
		'maxHeight': 'A altura da imagem é muito grande ({{ value }}px max).',
		'imageFormat': 'Esse tipo de imagem não é suportada, apenas ({{ value }}).',
		'fileExtension': 'Esse tipo de arquivo não é suportado, apenas ({{ value }}).'
	},
	tpl: {
		wrap:            '<div class="dropify-wrapper"></div>',
		loader:          '<div class="dropify-loader"></div>',
		message:         '<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',
		preview:         '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',
		filename:        '<p class="dropify-filename"><span class="dropify-filename-inner"></span></p>',
		clearButton:     '<button type="button" class="dropify-clear">{{ remove }}</button>',
		errorLine:       '<p class="dropify-error">{{ error }}</p>',
		errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
	}
};

$('.dropify').dropify(defaults);
	
