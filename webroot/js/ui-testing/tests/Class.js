/*global SBK, jQuery, document, module, deepEqual, ok, test, asyncTest, start */

jQuery(document).ready(function () {
    module('SBK.Class');
    
    test('Example', 8, function () {
        var Person, Ninja, p, n;
        
        Person = SBK.Class.extend({
            init: function (isDancing) {
                this.dancing = isDancing;
            },
            dance: function () {
                return this.dancing;
            }
        });
        
        Ninja = Person.extend({
            init: function () {
                this.call_super(false);
            },
            dance: function () {
                // Call the inherited version of dance()
                return this.call_super();
            },
            swingSword: function () {
                return true;
            }
        });

        p = new Person(true);
        deepEqual(p.dance(), true);

        n = new Ninja();
        deepEqual(n.dance(), false);
        deepEqual(n.swingSword(), true);

        // Should all be true
        deepEqual(p instanceof Person, true);
        deepEqual(p instanceof SBK.Class, true);
        deepEqual(n instanceof Ninja, true);
        deepEqual(n instanceof Person, true);
        deepEqual(n instanceof SBK.Class, true);
    });
});
