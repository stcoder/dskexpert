CKEDITOR.editorConfig = function( config ) {	
	config.removeButtons = 'Anchor,Save,NewPage,Preview,Print,Flash,Smiley,ShowBlocks';
	
	config.toolbarGroups = [
		{ name: 'document', groups: ['mode']},
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'insert', groups: ['image', 'table']},
		{ name: 'links' },
		{ name: 'tools' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'align' ] },
		{ name: 'styles' },
		{ name: 'colors' }
	];
};
