<!-- General JS Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script>
	(function(){
		if (!window.jQuery) return;
		$(function(){
			var sel = "[data-toggle='sidebar']";
			if ($(sel).length) {
				// bind a safe fallback handler without removing existing ones
				$(sel).off('click.copilot').on('click.copilot', function(e){
					e.preventDefault();
					var body = $('body'), w = $(window);

					if(w.outerWidth() <= 1024) {
						body.removeClass('search-show search-gone');
						if(body.hasClass('sidebar-gone')) {
							body.removeClass('sidebar-gone');
							body.addClass('sidebar-show');
						} else {
							body.addClass('sidebar-gone');
							body.removeClass('sidebar-show');
						}
					} else {
						body.removeClass('search-show search-gone');
						if(body.hasClass('sidebar-mini')) {
							// expand
							body.removeClass('sidebar-mini');
							try{ $('.main-sidebar').niceScroll(); }catch(e){}
						} else {
							// collapse to mini
							body.addClass('sidebar-mini');
							body.removeClass('sidebar-show');
							try{ $('.main-sidebar').getNiceScroll().remove(); }catch(e){}
						}
					}

					return false;
				});
			}
		});
	})();
</script>

<!-- Custom lightweight dropdown (no Bootstrap JS) -->
<style>
	/* make the custom dropdown positioned relative to the nav item */
	.custom-dropdown { position: relative; }
	.custom-dropdown-menu {
		position: absolute;
		top: calc(100% + 6px);
		right: 0;
		min-width: 200px;
		display: none;
		z-index: 2000;
		background: #fff;
		border: 1px solid rgba(0,0,0,0.08);
		box-shadow: 0 6px 18px rgba(0,0,0,0.08);
		padding: .25rem 0;
		border-radius: .25rem;
	}
	.custom-dropdown-menu.open { display: block !important; }
	.custom-dropdown-menu .dropdown-title { padding: .5rem 1rem; font-weight:600; }
	.custom-dropdown-menu .dropdown-item { display:block; padding: .5rem 1rem; color:#333; text-decoration:none; }
	.custom-dropdown-menu .dropdown-item:hover { background:#f6f6f6; }
	.custom-dropdown-menu .dropdown-divider { height:1px; margin:.5rem 0; background:rgba(0,0,0,0.06); }
</style>

<script>
	(function(){
		// Move menu to body when opened to avoid ancestor clipping (overflow) and z-index issues.
		var origPlace = new WeakMap();

		function restoreMenu(menu) {
			try {
				if (!menu) return;
				if (menu.dataset.moved === 'true') {
					var info = origPlace.get(menu);
					if (info && info.parent) {
						if (info.next) info.parent.insertBefore(menu, info.next);
						else info.parent.appendChild(menu);
					}
					origPlace.delete(menu);
					delete menu.dataset.moved;
				}
				menu.classList.remove('open');
				menu.style.position = '';
				menu.style.left = '';
				menu.style.top = '';
				menu.style.visibility = '';
			} catch (err) {
				console.error('restoreMenu error', err, menu);
			}
		}

		function closeAll() {
			var menus = Array.prototype.slice.call(document.querySelectorAll('.custom-dropdown-menu'));
			menus.forEach(function(m){ restoreMenu(m); });
			var toggles = document.querySelectorAll('.custom-dropdown-toggle[aria-expanded]');
			toggles.forEach(function(t){ t.setAttribute('aria-expanded','false'); });
		}

		function openMenu(toggle, menu) {
			console.debug && console.debug('openMenu called', toggle, menu);
			try {
				if (!menu || !toggle) return;
				// ensure toggle has an id
				if (!toggle.id) toggle.id = 'customDropdownToggle_' + Math.random().toString(36).slice(2,9);

				// save original place
				if (!origPlace.has(menu)) {
					origPlace.set(menu, { parent: menu.parentElement, next: menu.nextSibling });
				}
				// mark owner so we can find the menu after it's moved
				menu.dataset.owner = toggle.id;

				// make visible to measure
				menu.classList.add('open');
				menu.style.visibility = 'hidden';

				// move to body
				document.body.appendChild(menu);
				menu.style.position = 'absolute';
				menu.style.zIndex = 3000;

				// measure and position aligned to toggle's right edge
				var rect = toggle.getBoundingClientRect();
				var mw = menu.offsetWidth;
				var top = Math.round(rect.bottom + 6 + window.scrollY);
				var left = Math.round(rect.right - mw + window.scrollX);
				// if left is off-screen, align to left edge of toggle
				if (left < 8) left = Math.round(rect.left + window.scrollX);

				menu.style.left = left + 'px';
				menu.style.top = top + 'px';
				menu.style.visibility = '';
				menu.dataset.moved = 'true';
				toggle.setAttribute('aria-expanded','true');
			} catch (err) {
				console.error('openMenu error', err, toggle, menu);
			}
		}

		function toggleMenu(toggle) {
			console.debug && console.debug('toggleMenu called', toggle);
			// try to find the menu in the original place
			var menu = toggle && toggle.parentElement ? toggle.parentElement.querySelector('.custom-dropdown-menu') : null;
			// if not found (moved to body), find by owner id
			if (!menu && toggle && toggle.id) {
				menu = document.querySelector('.custom-dropdown-menu[data-owner="' + toggle.id + '"]');
			}
			if (!menu) {
				console.warn('toggleMenu: menu not found for toggle', toggle);
				return;
			}
			var isOpen = menu.classList.contains('open') && menu.dataset.moved === 'true';
			if (isOpen) {
				// if currently open, close it
				restoreMenu(menu);
				toggle.setAttribute('aria-expanded','false');
				return;
			}
			closeAll();
			openMenu(toggle, menu);
		}

		// Bind click handlers
		document.addEventListener('click', function(e){
			var t = e.target;
			var toggle = t.closest && t.closest('.custom-dropdown-toggle');
			if (toggle) {
				e.preventDefault();
				toggleMenu(toggle);
				return;
			}

			// click outside -> close. Treat clicks inside moved menus as inside.
			if (!t.closest || (!t.closest('.custom-dropdown') && !t.closest('.custom-dropdown-menu'))) {
				closeAll();
			}
		});

		// Close on ESC
		document.addEventListener('keydown', function(e){
			if (e.key === 'Escape' || e.key === 'Esc') {
				closeAll();
			}
		});

		// Note: jQuery-based delegated toggle handler removed to avoid duplicate
		// event handling that could open then immediately close the menu.
	})();
</script>

<!-- Fallback: profile dropdown toggle (handles cases Bootstrap dropdown not initializing) -->
<script>
	(function(){
		if (window.jQuery) {
			$(function(){
				var $toggler = $('.nav-link-user[data-bs-toggle="dropdown"], .nav-link-user[data-toggle="dropdown"]');
				$toggler.off('click.profileFallback').on('click.profileFallback', function(e){
					e.preventDefault();
					var $menu = $(this).siblings('.dropdown-menu');
					if (!$menu.length) return;
					$menu.toggleClass('show');
					var expanded = $(this).attr('aria-expanded') === 'true';
					$(this).attr('aria-expanded', !expanded);
				});

				// close when clicking outside
				$(document).off('click.profileFallback').on('click.profileFallback', function(e){
					if ($(e.target).closest('.nav-link-user, .nav-link-user .dropdown-menu').length === 0) {
						$('.nav-link-user').attr('aria-expanded', 'false').siblings('.dropdown-menu.show').removeClass('show');
					}
				});
			});
		}
	})();
</script>
<!-- Using Bootstrap 5 via app bundle / sidebar includes; removed Bootstrap 4 and Popper 1 to avoid conflicts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="{{ asset('assets/js/stisla.js') }}"></script>

<!-- JS Libraies -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
	integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>

<!-- Fallback: bind sidebar dropdown toggles after jQuery and template scripts loaded -->
<script>
	(function(){
		if (window.jQuery) {
			$(function(){
				// only bind if not already handled by the custom sidebar script
				if (window.__sidebar_custom_initialized) return;
				var selector = '.main-sidebar .sidebar-menu li a.has-dropdown';
				if ($(selector).length) {
					$(selector).off('click.simpleFallback').on('click.simpleFallback', function(e){
						e.preventDefault();
						var li = $(this).parent();
						if (li.hasClass('active')) {
							li.removeClass('active');
							li.find('> .dropdown-menu').slideUp(200);
						} else {
							// close other open menus
							$('.main-sidebar .sidebar-menu li.active > .dropdown-menu').slideUp(200);
							$('.main-sidebar .sidebar-menu li.active').removeClass('active');
							li.addClass('active');
							li.find('> .dropdown-menu').slideDown(200);
						}
						return false;
					});
				}
			});
		}
	})();
</script>