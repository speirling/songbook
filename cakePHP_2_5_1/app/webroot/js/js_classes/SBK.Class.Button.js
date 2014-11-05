SBK.Button = SBK.Class.extend({
	init: function (parent_container, classname, text, onclick) {
		var self = this;

		self.container = jQuery('<a class="button"></a>').appendTo(parent_container);
		self.set_text(text);
        self.click(onclick);
        self.addClass(classname);
	},
    
    set_text: function (text) {
        var self = this;
        
        self.container.html(text);
    },
    
	indicate_button_press: function (onclick) {
        var self = this;

        self.container.addClass('pressed');
        setTimeout(function () {
            self.container.removeClass('pressed');
        }, 1000);
    },
    
    click: function (onclick) {
        var self = this;

        if(typeof(onclick) === 'function') {
            self.container.unbind('click');
            self.container.bind('click', function () {
                setTimeout(function () {
                onclick();
                }, 100);
            });
            self.container.bind('click', function () {self.indicate_button_press()});
        }
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