(function ($) {
	//custom scroll replacement to allow for interval-based 'polling'
	//rather than checking on every pixel.
	var uniqueCntr = 0;
	$.fn.scrolled = function (waitTime, fn) {
		if (typeof waitTime === "function") {
			fn = waitTime;
			waitTime = 200;
		}
		var tag = "scrollTimer" + uniqueCntr++;
		this.scroll(function () {
			var self = $(this);
			clearTimeout(self.data(tag));
			self.data(
				tag,
				setTimeout(function () {
					self.removeData(tag);
					fn.call(self[0]);
				}, waitTime)
			);
			//add opacity to 1
		});
	};

	$.fn.AniView = function (options) {
		//some default settings. animateThreshold controls the trigger point
		//for animation and is subtracted from the bottom of the viewport.
		var settings = $.extend(
			{
				animateThreshold: 0,
				scrollPollInterval: 20,
			},
			options
		);

		//keep the matched elements in a variable for easy reference
		var collection = this;

		//cycle through each matched element and wrap it in a block/div
		//and then proceed to fade out the inner contents of each matched element
		$(collection).each(function (index, element) {
			$(element).wrap('<div class="av-container"></div>');
			$(element).css("opacity", 0);
		});

    /**
     * returns boolean representing whether element's top is coming into bottom of viewport
     *
     * @param HTMLDOMElement element the current element to check
     */
		function EnteringViewport(element) {
			var elementTop = $(element).offset().top;
			var viewportBottom = $(window).scrollTop() + $(window).height();
			return elementTop < viewportBottom - settings.animateThreshold
				? true
				: false;
		}

    /**
     * cycle through each element in the collection to make sure that any
     * elements which should be animated into view, are...
     *
     * @param collection of elements to check
     */
		function RederElementorun() {
			$(".is_scroll_done span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 30);
			});
		}
		function RederElementorunY() {
			$(".is_scroll_doney span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 50);
			});
		}
		function RederElementorunfast() {
			$(".is_scroll_done_fast span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 15);
			});
		}
		function RederElementorunfastx() {
			$(".is_scroll_done_fastx span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 15);
			});
		}
		function ScrollDoneM() {
			$(".is_scroll_done_fast_m span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 28);
			});
		}
		function ScrollDoneN() {
			$(".is_scroll_done_fast_n span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 28);
			});
		}
		function ScrollDoneO() {
			$(".is_scroll_done_fast_o span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 30);
			});
		}
		function ScrollDoneP() {
			$(".is_scroll_done_fast_p span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 30);
			});
		}
		function ScrollDoneW() {
			$(".is_scroll_done_fast_w span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 15);
			});
		}
		function ScrollDoneOP() {
			$(".is_scroll_done_fast_op span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 15);
			});
		}
		function ScrollDoneAP() {
			$(".is_scroll_done_ap span").each(function (index) {
				var self = this;
				setTimeout(function () {
					$(self).css({ opacity: "1" });
				}, index * 15);
			});
		}

		function RenderElementsCurrentlyInViewport(collection) {
			$(collection).each(function (index, element) {
				var elementParentContainer = $(element).parent(".av-container");
				if (
					$(element).is("[data-ani]") &&
					!$(elementParentContainer).hasClass("av-visible") &&
					EnteringViewport(elementParentContainer)
				) {
					$(element).css("opacity", 1);
					$(elementParentContainer).addClass("av-visible");
					$(element).addClass("animated " + $(element).attr("data-ani"));
					RederElementorun();
					RederElementorunY();
					RederElementorunfast();
					RederElementorunfastx();
					ScrollDoneM();
					ScrollDoneN();
					ScrollDoneO();
					ScrollDoneP();
					ScrollDoneW();
					ScrollDoneOP();
					ScrollDoneAP();
				}
			});
		}

		//on page load, render any elements that are currently/already in view
		RenderElementsCurrentlyInViewport(collection);

		//enable the scrolled event timer to watch for elements coming into the viewport
		//from the bottom. default polling time is 20 ms. This can be changed using
		//'scrollPollInterval' from the user visible options
		$(window).scrolled(settings.scrollPollInterval, function () {
			RenderElementsCurrentlyInViewport(collection);
			RederElementorun();
			RederElementorunY();
			RederElementorunfast();
			RederElementorunfastx();
			ScrollDoneM();
			ScrollDoneN();
			ScrollDoneO();
			ScrollDoneP();
			ScrollDoneW();
			ScrollDoneOP();
			ScrollDoneAP();
		});
	};
})(jQuery);
