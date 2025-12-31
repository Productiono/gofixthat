( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var toc = document.querySelector( '.mbf-entry__toc' );
		var tocList = document.querySelector( '.mbf-entry__toc-list' );
		var contentArea = document.querySelector( '.mbf-entry__content-wrap .entry-content' );

		if ( ! toc || ! tocList || ! contentArea ) {
			return;
		}

		var headings = contentArea.querySelectorAll( 'h2' );

		if ( ! headings.length ) {
			toc.classList.add( 'is-hidden' );
			return;
		}

		var slugCounts = {};
		var tocItems = [];

		headings.forEach( function ( heading ) {
			var text = heading.textContent.trim();
			var existingId = heading.id.trim();
			var baseSlug = existingId ? existingId : text.toLowerCase().replace( /[^a-z0-9]+/g, '-' ).replace( /^-+|-+$/g, '' );

			if ( ! slugCounts[ baseSlug ] ) {
				slugCounts[ baseSlug ] = 0;
			}

			var slug = baseSlug;

			if ( slugCounts[ baseSlug ] > 0 && ! existingId ) {
				slug = baseSlug + '-' + slugCounts[ baseSlug ];
			}

			slugCounts[ baseSlug ] += 1;

			if ( ! existingId ) {
				heading.id = slug;
			}

			var listItem = document.createElement( 'li' );
			var link = document.createElement( 'a' );

			link.href = '#' + slug;
			link.textContent = text;

			link.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				var target = document.getElementById( slug );
				if ( target ) {
					target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				}
			} );

			listItem.appendChild( link );
			tocList.appendChild( listItem );

			tocItems.push( {
				heading: heading,
				item: listItem,
			} );
		} );

		if ( 'IntersectionObserver' in window ) {
			var observer = new IntersectionObserver(
				function ( entries ) {
					entries.forEach( function ( entry ) {
						tocItems.forEach( function ( tocItem ) {
							if ( tocItem.heading === entry.target ) {
								if ( entry.isIntersecting ) {
									tocItem.item.classList.add( 'is-active' );
								} else {
									tocItem.item.classList.remove( 'is-active' );
								}
							}
						} );
					} );
				},
				{
					rootMargin: '0px 0px -65% 0px',
					threshold: [ 0, 0.2, 1 ],
				}
			);

			tocItems.forEach( function ( tocItem ) {
				observer.observe( tocItem.heading );
			} );
		}
	} );
}() );
