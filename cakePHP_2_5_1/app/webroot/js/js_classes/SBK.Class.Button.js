SBK.Button = SBK.Class.extend({
	init: function (parent_container, classname, text, onclick) {
		var self = this;

		self.container = jQuery('<a class="button ' + classname + '"></a>').appendTo(parent_container);
		self.container.html(text);
        if(typeof(onclick) === 'function') {
            self.container.bind('click', onclick);
        }
        if(typeof(classname) === 'string' && classname !== '') {
            self.container.addClass(classname);
        }
	},
    
    click: function (onclick) {
        var self = this;

        self.container.click(function() {
            //onclick();
        });
    },
    
    enable: function () {
        var self = this;
        
        self.container.removeClass('disabled');
    },
    
    disable: function () {
        var self = this;

        self.container.addClass('disabled');
    },
    
    hasClass: function (class_name) {
        var self = this;

        return self.container.hasClass(class_name);
    },
    
    addClass: function (class_name) {
        var self = this;

        return self.container.addClass(class_name);
    },
    
    removeClass: function (class_name) {
        var self = this;

        return self.container.removeClass(class_name);
    }
});