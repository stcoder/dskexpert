(function()
{
CKEDITOR.config.jqueryOverrideVal = typeof CKEDITOR.config.jqueryOverrideVal == 'undefined'
? true : CKEDITOR.config.jqueryOverrideVal;

var jQuery = window.jQuery;

if ( typeof jQuery == 'undefined' )
return;

// jQuery object methods.
jQuery.extend( jQuery.fn,
/** @lends jQuery.fn */
{
/**
* Return existing CKEditor instance for first matched element.
* Allows to easily use internal API. Doesn't return jQuery object.
*
* Raised exception if editor doesn't exist or isn't ready yet.
*
* @name jQuery.ckeditorGet
* @return CKEDITOR.editor
* @see CKEDITOR.editor
*/
ckeditorGet: function()
{
var instance = this.eq( 0 ).data( 'ckeditorInstance' );
if ( !instance )
throw "CKEditor not yet initialized, use ckeditor() with callback.";
return instance;
},

ckeditor: function( callback, config )
{
if ( !CKEDITOR.env.isCompatible )
return this;

if ( !jQuery.isFunction( callback ))
{
var tmp = config;
config = callback;
callback = tmp;
}
config = config || {};

this.filter( 'textarea, div, p' ).each( function()
{
var $element = jQuery( this ),
editor = $element.data( 'ckeditorInstance' ),
instanceLock = $element.data( '_ckeditorInstanceLock' ),
element = this;

if ( editor && !instanceLock )
{
if ( callback )
callback.apply( editor, [ this ] );
}
else if ( !instanceLock )
{
// CREATE NEW INSTANCE

// Handle config.autoUpdateElement inside this plugin if desired.
if ( config.autoUpdateElement
|| ( typeof config.autoUpdateElement == 'undefined' && CKEDITOR.config.autoUpdateElement ) )
{
config.autoUpdateElementJquery = true;
}

// Always disable config.autoUpdateElement.
config.autoUpdateElement = false;
$element.data( '_ckeditorInstanceLock', true );

// Set instance reference in element's data.
editor = CKEDITOR.replace( element, config );
$element.data( 'ckeditorInstance', editor );

// Register callback.
editor.on( 'instanceReady', function( event )
{
var editor = event.editor;
setTimeout( function()
{
// Delay bit more if editor is still not ready.
if ( !editor.element )
{
setTimeout( arguments.callee, 100 );
return;
}

// Remove this listener.
event.removeListener( 'instanceReady', this.callee );

// Forward setData on dataReady.
editor.on( 'dataReady', function()
{
$element.trigger( 'setData' + '.ckeditor', [ editor ] );
});

// Forward getData.
editor.on( 'getData', function( event ) {
$element.trigger( 'getData' + '.ckeditor', [ editor, event.data ] );
}, 999 );

// Forward destroy event.
editor.on( 'destroy', function()
{
$element.trigger( 'destroy.ckeditor', [ editor ] );
});

// Integrate with form submit.
if ( editor.config.autoUpdateElementJquery && $element.is( 'textarea' ) && $element.parents( 'form' ).length )
{
var onSubmit = function()
{
$element.ckeditor( function()
{
editor.updateElement();
});
};

// Bind to submit event.
$element.parents( 'form' ).submit( onSubmit );

// Bind to form-pre-serialize from jQuery Forms plugin.
$element.parents( 'form' ).bind( 'form-pre-serialize', onSubmit );

// Unbind when editor destroyed.
$element.bind( 'destroy.ckeditor', function()
{
$element.parents( 'form' ).unbind( 'submit', onSubmit );
$element.parents( 'form' ).unbind( 'form-pre-serialize', onSubmit );
});
}

// Garbage collect on destroy.
editor.on( 'destroy', function()
{
$element.data( 'ckeditorInstance', null );
});

// Remove lock.
$element.data( '_ckeditorInstanceLock', null );

// Fire instanceReady event.
$element.trigger( 'instanceReady.ckeditor', [ editor ] );

// Run given (first) code.
if ( callback )
callback.apply( editor, [ element ] );
}, 0 );
}, null, null, 9999);
}
else
{
// Editor is already during creation process, bind our code to the event.
CKEDITOR.on( 'instanceReady', function( event )
{
var editor = event.editor;
setTimeout( function()
{
// Delay bit more if editor is still not ready.
if ( !editor.element )
{
setTimeout( arguments.callee, 100 );
return;
}

if ( editor.element.$ == element )
{
// Run given code.
if ( callback )
callback.apply( editor, [ element ] );
}
}, 0 );
}, null, null, 9999);
}
});
return this;
}
});

// New val() method for objects.
if ( CKEDITOR.config.jqueryOverrideVal )
{
jQuery.fn.val = CKEDITOR.tools.override( jQuery.fn.val, function( oldValMethod )
{
return function( newValue, forceNative )
{
var isSetter = typeof newValue != 'undefined',
result;

this.each( function()
{
var $this = jQuery( this ),
editor = $this.data( 'ckeditorInstance' );

if ( !forceNative && $this.is( 'textarea' ) && editor )
{
if ( isSetter )
editor.setData( newValue );
else
{
result = editor.getData();
// break;
return null;
}
}
else
{
if ( isSetter )
oldValMethod.call( $this, newValue );
else
{
result = oldValMethod.call( $this );
// break;
return null;
}
}

return true;
});
return isSetter ? this : result;
 			};
 		});
	}
})();