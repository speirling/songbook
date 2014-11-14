/*global CYLON, jQuery, window, document */


SBK.CallbackList = SBK.Class.extend({

    init: function () {
        var self = this;
        
        self.callbacks = [];
    },
    
    register: function (callback) {
        var self = this;
        
        self.callbacks.push(callback);
    },
    
    run: function (changed_parameters) {
        var self = this, index;
        
        for (index = 0; index < self.callbacks.length; index = index + 1) {
            self.callbacks[index](changed_parameters);
        }
    }
});