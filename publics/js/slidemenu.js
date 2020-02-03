(function($){
	var settings, status;
	var Status = {
		CLOSE: 0,
		OPEN: 1,
		IN_PROGRESS: 2
	};
	var smY, sfY, mfY, moveY, startY, draggedY, startTime, diffTime;


	function _open() {
		status = Status.OPEN;
		$(settings.menu + ", " + settings.main_contents).addClass('show');
		$(settings.menu_contents).show();
		$("body").addClass("sp-nav-open");
		event.preventDefault();
	};

	function _close() {
		status = Status.IN_PROGRESS;
		$(settings.menu + ", " + settings.main_contents).removeClass('show');
		$("body").removeClass("sp-nav-open");
		event.preventDefault();
	};

	function _buttonTouchStart() {
		switch(status) {
			case Status.IN_PROGRESS:
				status = Status.CLOSE;
				break;

			case Status.OPEN:
				_close();
				break;

			case Status.CLOSE:
				break;
		}
	}

	function _buttonTouchEnd() {
		switch(status) {
			case Status.IN_PROGRESS:
				status = Status.CLOSE;
				break;

			case Status.OPEN:
				break;

			case Status.CLOSE:
				_open();
				break;
		}
	};

	function _bodyTouchStart() {
		switch(status) {
			case Status.IN_PROGRESS:
			case Status.CLOSE:
				break;

			case Status.OPEN:
				_close();
				break;
		}
	}

	$.fn.slideMenu = function(options) {
		settings = $.extend({}, $.fn.slideMenu.defaults, options);
		status = Status.CLOSE;
		var button_selector = this.selector;

		smY = 0;

		$(document).ready(function() {
			var menu_list_height;

			//menu button - touchstart
			$(button_selector).bind("touchstart.menu_button", function() {
				_buttonTouchStart();
			});
			$(button_selector).bind("mousedown.menu_button", function() {
				_buttonTouchStart();
			});

			//menu button - touchend
			$(button_selector).bind("touchend.menu_button", function() {
				_buttonTouchEnd();
			});
			$(button_selector).bind("mouseup.menu_button", function() {
				_buttonTouchEnd();
			});

			//main contents
			$(settings.main_contents).bind("touchstart.main_contents", function() {
				_bodyTouchStart();
			});
			$(settings.main_contents).bind("mousedown.main_contents", function() {
				_buttonTouchStart();
			});
			$('.close-btn').bind("touchstart.close-btn", function() {
				_bodyTouchStart();
			});

      $(settings.menu_contents + " a").bind("click", function(){
        if(window.location.pathname.match(/^\/$/) !== null &&
          event.target.getAttribute('href').match(/^\/#.*/) !== null) {
          location.href = event.target.getAttribute('href');
          _buttonTouchStart();
          _buttonTouchEnd();
        }
      });
		});
	};

	$.fn.slideMenu.defaults = {
		main_contents: "#container",
		menu: ".sp-nav",
		menu_contents: ".sp-nav__wrapper",
		menu_list: "#slidemenu_list",
		speed: 200,
		width: 100,
		bottom_margin: 100
	};

})(jQuery);