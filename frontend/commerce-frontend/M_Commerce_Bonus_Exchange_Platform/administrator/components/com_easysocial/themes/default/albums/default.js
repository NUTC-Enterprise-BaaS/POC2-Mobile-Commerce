
EasySocial
.require()
.script( 'admin/grid/grid' )
.library( 'dialog' )
.done(function($){

	$( '[data-table-grid]' ).implement( EasySocial.Controller.Grid );

	<?php if( $this->tmpl == 'component' ){ ?>		
		$( '[data-album-insert]' ).on('click', function( event )
		{
			event.preventDefault();

			// Supply all the necessary info to the caller
			var id 		= $( this ).data( 'id' ),
				avatar 	= $( this ).data( 'avatar' ),
				title	= $( this ).data( 'title' ),
				alias	= $(this).data( 'alias' );

				obj 	= {
							"id"	: id,
							"title"	: title,
							"avatar" : avatar,
							"alias"	: alias
						  };

			window.parent["<?php echo JRequest::getCmd( 'jscallback' );?>" ]( obj );
		});
		
	<?php } else { ?>
		
		$.Joomla( 'submitbutton' , function( task )
		{
			if( task == 'remove' )
			{
				EasySocial.dialog(
				{
					content 	: EasySocial.ajax( "admin/views/albums/confirmDelete" ),
					bindings	: 
					{
						"{deleteButton} click" : function()
						{
							$.Joomla( 'submitform' , [task] );

							return false;
						}
					}
				});
				return false;
			}

			$.Joomla( 'submitform' , [task] );
		});
	<?php } ?>

});