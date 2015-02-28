//http://ejohn.org/blog/simple-javascript-inheritance/
//Inspired by base2 and Prototype
(SBK.Class = function () {
 var initializing = false, fnTest = /xyz/.test(function () { xyz; }) ? /\bcall_super\b/ : /.*/, Class;

 // The base Class implementation (does nothing)
 Class = function () {};
 
 function copy_property(name, fn, call_super) {
     return function () {
         var tmp, ret;
         tmp = this.call_super;

         // Add a new .call_super() method that is the same method
         // but on the super-class
         this.call_super = call_super[name];

         // The method only need to be bound temporarily, so we
         // remove it when we're done executing
         ret = fn.apply(this, arguments);
         this.call_super = tmp;
        
         return ret;
     };
 }

 function copy_properties(prop, new_prototype, call_super) {
     var name;
     
     for (name in prop) {
         // Check if we're overwriting an existing function
         if (typeof prop[name] === "function" && typeof call_super[name] === "function" && fnTest.test(prop[name])) {
             new_prototype[name] = copy_property(name, prop[name], call_super);
         } else {
             new_prototype[name] = prop[name];
         }
     }
 }

 // Create a new Class that inherits from this class
 Class.extend = function (prop) {
     var call_super, new_prototype;
     call_super = this.prototype;

     // Instantiate a base class (but only create the instance,
     // don't run the init constructor)
     initializing = true;
     new_prototype = new this();
     initializing = false;

     // Copy the properties over onto the new prototype
     copy_properties(prop, new_prototype, call_super);

     // The dummy class constructor
     function Class() {
         // All construction is actually done in the init method
         if (!initializing && this.init) {
             this.init.apply(this, arguments);
         }
     }

     // Populate our constructed prototype object
     Class.prototype = new_prototype;
    
     // Enforce the constructor to be what we expect
     Class.constructor = Class;

     // And make this class extendable
     Class.extend = arguments.callee;
    
     return Class;
 };
 
 return Class;
}());