SBK.PleaseWait = SBK.Class.extend({
	init: function (parent_container) {
		var self = this;

		self.container = jQuery('<div class="pleasewait"></div>').appendTo(parent_container);
		self.hide();
	},
	
	show: function () {
		var self = this;

		self.container.show();
	},
	
	hide: function () {
		var self = this;

		self.container.hide();
	}
});