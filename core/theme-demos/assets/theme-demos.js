
( function( $ ) {
	"use strict";

	/** ----------------------------------------------------------------------------
	 * Theme Demos */

	var cscoThemeDemos = {};

	( function() {
		var $this;

		cscoThemeDemos = {

			/** Initialize */
			init: function( e ) {

				$this = cscoThemeDemos;

				// Init events.
				$this.events( e );
			},

			/** Events */
			events: function( e ) {
				$( document ).on( 'click', '.mbf-demo-import-open', function( e ) {
					$this.openImportDemo( e, this );
				});
				$( document ).on( 'click', '.mbf-demo-import-close, .mbf-import-overlay', function( e ) {
					$this.closeImportDemo( e, this );
				});
				$( document ).on( 'click', '.mbf-demo-import-start', function( e ) {
					$this.startImportDemo( e, this );
				});
				$( document ).on( 'click', '.mbf-demo-item', function( e ) {
					$this.openPreviewDemo( e, this );
				});
				$( document ).on( 'click', '.mbf-prev-demo', function( e ) {
					$this.openPreviewPrevDemo( e, this );
				});
				$( document ).on( 'click', '.mbf-next-demo', function( e ) {
					$this.openPreviewNextDemo( e, this );
				});
				$( document ).on( 'click', '.mbf-preview-cancel a', function( e ) {
					$this.closePreviewDemo( e, this );
				});
			},

			/** Open import demo */
			openImportDemo: function( e, object ) {

				// Get demo id.
				var $demo_id = $( object ).data( 'id' );

				// Body import.
				$( 'body' ).addClass( 'mbf-import-theme-active' );

				// Variables.
				var data = {
					'action': 'mbf_html_import_data',
					'nonce': cscoThemeDemosConfig.nonce,
					'demo_id': $demo_id,
				};

				// Reset current step.
				$( '.mbf-import-step' ).removeClass( 'mbf-import-step-active' );
				$( '.mbf-import-step' ).first().addClass( 'mbf-import-step-active' );

				// Remove warning.
				$( '.mbf-import-theme .mbf-msg-warning' ).remove();

				// Reset variables.
				$( '.mbf-import-start .mbf-import-output' ).html( '' );

				$( '.mbf-import-start .mbf-import-output' ).addClass( 'mbf-import-load' );

				$( '.mbf-import-process .mbf-import-progress-label' ).html( '' );

				$( '.mbf-import-process .mbf-import-progress-indicator' ).attr( 'style', '--mbf-indicator: 0%;' );

				$( '.mbf-import-process .mbf-import-progress-sublabel' ).html( '0%' );

				// Send Request.
				$.post( cscoThemeDemosConfig.ajax_url, data, function( response ) {

					$( '.mbf-import-start .mbf-import-output' ).removeClass( 'mbf-import-load' );

					if ( response.success ) {

						$( '.mbf-import-start .mbf-import-output' ).html( response.data );

					} else if ( response.data ) {

						$( '.mbf-import-start .mbf-import-output' ).html( `<div class="mbf-msg-warning">${response.data}</div>` );
					} else {

						$( '.mbf-import-start .mbf-import-output' ).html( `<div class="mbf-msg-warning">${cscoThemeDemosConfig.failed_message}</div>` );
					}

				} ).fail( function( xhr, textStatus, e ) {
					$( '.mbf-import-start .mbf-import-output' ).removeClass( 'mbf-import-load' );

					$( '.mbf-import-start .mbf-import-output' ).html( `<div class="mbf-msg-warning">${cscoThemeDemosConfig.failed_message}</div>` );
				} );

				e.preventDefault();
			},

			/** Close import demo */
			closeImportDemo: function( e, object ) {

				// Remove import from body.
				$( 'body' ).removeClass( 'mbf-import-theme-active' );

				e.preventDefault();
			},

			/** Start import demo */
			startImportDemo: function( e, object ) {
				// Change process.
				$( '.mbf-import-step' ).removeClass( 'mbf-import-step-active' );
				$( '.mbf-import-process' ).addClass( 'mbf-import-step-active' );

				// Run Import.
				setTimeout( function() {
					$this.importContent( e, object );
				}, 10 );

				e.preventDefault();
			},

			/** Open preview demo */
			openPreviewDemo: function( e, object ) {
				if ( ! $( e.target ).is( '.mbf-demo-import-open, .mbf-demo-import-url' ) ) {
					$this.openPreview( e, object );

					e.preventDefault();
				}
			},

			/** Open preview prev demo */
			openPreviewPrevDemo: function( e, object ) {

				var prev = $( '.mbf-demo-item-open' ).prev( '.mbf-demo-item-active' );

				if ( prev.length > 0 ) {
					$this.openPreview( e, prev );
				}

				e.preventDefault();
			},

			/** Open preview next demo */
			openPreviewNextDemo: function( e, object ) {
				var next = $( '.mbf-demo-item-open' ).next( '.mbf-demo-item-active' );

				if ( next.length > 0 ) {
					$this.openPreview( e, next );
				}

				e.preventDefault();
			},

			/** Close preview */
			closePreviewDemo: function( e, object ) {
				// Remove current class from items.
				$( '.mbf-demo-item' ).removeClass( 'mbf-demo-item-open' );

				// Remove preview from body.
				$( 'body' ).removeClass( 'mbf-preview-active' );

				// Remove url from iframe.
				$( '.mbf-preview .mbf-preview-iframe' ).removeAttr( 'src' );

				e.preventDefault();
			},

			/** Import indicator */
			importIndicator: function( e, object, $data ) {
				// Set indicator.
				var indicator = Math.round( 100 / $data.steps * $data.index );

				// Change indicator.
				$( '.mbf-import-process .mbf-import-progress-indicator' ).attr( 'style', `--mbf-indicator: ${indicator}%;` );
				$( '.mbf-import-process .mbf-import-progress-sublabel' ).html( `${indicator}%` );
			},

			/** Import step */
			importStep: function( e, object, $data ) {

				if ( ! $( 'body' ).hasClass( 'mbf-import-theme-active' ) ) {
					return;
				}

				// Done.
				if ( $data.index >= $data.steps ) {
					// Change step.
					setTimeout(function(){
						$( '.mbf-import-step' ).removeClass( 'mbf-import-step-active' );
						$( '.mbf-import-finish' ).addClass( 'mbf-import-step-active' );

						$( document ).trigger( 'DOMImportFinish' );
					}, 200 );

					return;
				}

				// Set progress label.
				$( '.mbf-import-progress-label' ).html( $( $data.forms ).eq( $data.index ).find( 'input[name="step_name"]').val()  );

				// Send Request.
				$.post( {
					url: cscoThemeDemosConfig.ajax_url,
					type: 'POST',
					data: $( $data.forms ).eq( $data.index ).serialize(),
					timeout: 0,
				} ).done( function( response ) {

					if ( response.success ) {

						if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {

							$this.importStep( e, object, $data );

						} else {
							$data.index = $data.index + 1;

							$this.importIndicator( e, object, $data );
							$this.importStep( e, object, $data );
						}

					} else if ( response.data ) {

						$( '.mbf-import-progress' ).after( `<div class="mbf-msg-warning">${response.data}</div>` );
					} else {

						$( '.mbf-import-progress' ).after( `<div class="mbf-msg-warning">${cscoThemeDemosConfig.failed_message}</div>` );
					}

				} ).fail( function() {
					$( '.mbf-import-progress' ).after( `<div class="mbf-msg-warning">${cscoThemeDemosConfig.failed_message}</div>` )
				} );
			},

			/** Import content */
			importContent: function( e, object ) {
				var forms = $( '.mbf-import-start form' ).filter(function( index, element ){
					if ( $( element ).find( '.mbf-checkbox' ).prop( 'checked' ) ) {
						return true;
					} else {
						return false;
					}
				});

				var steps = forms.length;

				if ( steps <= 0 ) {
					return
				}

				$this.importStep( e, object, {
					'forms': forms,
					'steps': steps,
					'index': 0
				} );
			},

			/** Open preview */
			openPreview: function( e, object ) {
				let demo_id = $( object ).data( 'id' );
				let preview = $( object ).data( 'preview' );
				let name = $( object ).data( 'name' );
				let type = $( object ).data( 'type' );

				if ( 'false' === preview ) {
					return;
				}

				// Remove current class from siblings items.
				$( object ).siblings().removeClass( 'mbf-demo-item-open' );

				// Current item.
				$( object ).addClass( 'mbf-demo-item-open' );

				// Set demo id.
				$( '.mbf-preview .mbf-demo-import-open' ).attr( 'data-id', demo_id );

				// Prev Next Buttons.
				$( '.mbf-preview' ).find( '.mbf-prev-demo, .mbf-next-demo' ).removeClass( 'mbf-inactive' );

				let prev = $( object ).prev( '.mbf-demo-item-active' );
				if ( prev.length <= 0 ) {
					$( '.mbf-preview .mbf-prev-demo' ).addClass( 'mbf-inactive' );
				}

				let next = $( object ).next( '.mbf-demo-item-active' );
				if ( next.length <= 0 ) {
					$( '.mbf-preview .mbf-next-demo' ).addClass( 'mbf-inactive' );
				}

				// Reset header info.
				$( '.mbf-preview .mbf-header-info' ).html( '' );

				// Add name to info.
				if ( name ) {
					$( '.mbf-preview .mbf-header-info' ).prepend( `<div class="mbf-demo-name">${name}</div>` );
				}

				$( '.mbf-preview .mbf-preview-actions' ).html( $( object ).find( '.mbf-demo-actions' ).html() );

				// Set url in iframe.
				$( '.mbf-preview .mbf-preview-iframe' ).attr( 'src', preview );

				// Body preview.
				$( 'body' ).addClass( 'mbf-preview-active' );
			},
		};

	} )();

	// Initialize.
	cscoThemeDemos.init();

} )( jQuery );
