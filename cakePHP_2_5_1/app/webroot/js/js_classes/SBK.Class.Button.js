SBK.Button = SBK.Class.extend({
	init: function (parent_container, classname, text, onclick) {
		var self = this;

		self.container = jQuery('<a class="button ' + classname + '"></a>').appendTo(parent_container);
		self.container.html(text);
        if(typeof(onclick) === 'function') {
            self.click(onclick);
        }
        self.container.bind('click', function () {self.indicate_button_press()});
        if(typeof(classname) === 'string' && classname !== '') {
            self.container.addClass(classname);
        }
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

        self.container.bind('click', function () {
            setTimeout(function () {
            onclick();
            }, 100);
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