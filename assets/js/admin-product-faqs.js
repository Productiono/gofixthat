/* global wp */
jQuery( function( $ ) {
	const $faqList = $( '.mbf-product-faqs-list' );
	const template = wp.template( 'mbf-product-faq-row' );

	if ( ! $faqList.length ) {
		return;
	}

	function toggleEmptyState() {
		const isEmpty = 0 === $faqList.find( '.mbf-product-faq-row' ).length;

		$faqList.toggleClass( 'is-empty', isEmpty );
	}

	function renumberFaqs() {
		$faqList.find( '.mbf-product-faq-row' ).each( function( index ) {
			$( this )
				.attr( 'data-index', index )
				.find( 'input, textarea, label' )
				.each( function() {
					const $field = $( this );
					const currentId = $field.attr( 'id' );
					const currentName = $field.attr( 'name' );

					if ( currentId ) {
						$field.attr( 'id', currentId.replace( /\d+$/, index ) );
					}

					if ( currentName ) {
						$field.attr( 'name', currentName.replace( /\d+](?=\[)/, `${ index }]` ) );
					}

					if ( $field.is( 'label' ) ) {
						const currentFor = $field.attr( 'for' );

						if ( currentFor ) {
							$field.attr( 'for', currentFor.replace( /\d+$/, index ) );
						}
					}
				} );
		} );
	}

	function addFaqRow() {
		const index = $faqList.find( '.mbf-product-faq-row' ).length;
		const newRow = $( template( { index } ) );

		$faqList.append( newRow );
		toggleEmptyState();
	}

	$( document ).on( 'click', '.mbf-add-product-faq', function( event ) {
		event.preventDefault();
		addFaqRow();
	} );

	$faqList.on( 'click', '.mbf-remove-product-faq', function( event ) {
		event.preventDefault();
		$( this ).closest( '.mbf-product-faq-row' ).remove();
		renumberFaqs();
		toggleEmptyState();
	} );

	$faqList.sortable( {
		items: '.mbf-product-faq-row',
		handle: '.mbf-product-faq-row-handle',
		axis: 'y',
		placeholder: 'mbf-product-faq-row-placeholder',
		update: renumberFaqs,
	} );

	toggleEmptyState();
} );
