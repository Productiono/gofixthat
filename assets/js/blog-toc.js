( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var toc = document.querySelector( '.mbf-entry__toc' );
		var tocList = document.querySelector( '.mbf-entry__toc-list' );
		var contentArea = document.querySelector( '.mbf-entry__content-wrap .entry-content' );
		var tocInner = document.querySelector( '.mbf-entry__toc-inner' );
		var mobileQuery = window.matchMedia( '(max-width: 991.98px)' );
		var tocMobileAnchor = document.querySelector( '.mbf-entry__toc-mobile-anchor' );
		var tocOriginalParent = toc ? toc.parentElement : null;
		var tocNextSibling = toc ? toc.nextElementSibling : null;

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

		var moveTocToMobile = function () {
			if ( ! tocMobileAnchor ) {
				return;
			}

			tocMobileAnchor.insertAdjacentElement( 'afterend', toc );
			toc.classList.add( 'is-mobile' );
		};

		var moveTocToDesktop = function () {
			if ( ! tocOriginalParent ) {
				return;
			}

			if ( tocNextSibling && tocNextSibling.parentElement === tocOriginalParent ) {
				tocOriginalParent.insertBefore( toc, tocNextSibling );
			} else {
				tocOriginalParent.insertBefore( toc, tocOriginalParent.firstChild );
			}

			toc.classList.remove( 'is-mobile' );
		};

		var handleBreakpointChange = function () {
			if ( mobileQuery.matches ) {
				moveTocToMobile();
				if ( tocInner ) {
					tocInner.setAttribute( 'hidden', 'hidden' );
				}
			} else {
				moveTocToDesktop();
				if ( tocInner ) {
					tocInner.removeAttribute( 'hidden' );
				}
			}
		};

		handleBreakpointChange();

		if ( mobileQuery.addEventListener ) {
			mobileQuery.addEventListener( 'change', handleBreakpointChange );
		} else if ( mobileQuery.addListener ) {
			mobileQuery.addListener( handleBreakpointChange );
		}
	} );
}() );
