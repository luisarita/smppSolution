

  function addEvent(obj, evType, fn, useCapture){
   if (obj.addEventListener){
    obj.addEventListener(evType, fn, useCapture);
    return true;
   } else if (obj.attachEvent){
                var r = obj.attachEvent("on"+evType, fn);
                return r;
        }
        return false;
}
function removeEvent(obj, evType, fn, useCapture){
        if (obj.removeEventListener){
                obj.removeEventListener(evType, fn, useCapture);
                return true;
        } else if (obj.detachEvent){
                var r = obj.detachEvent("on"+evType, fn);
                return r;
        }
        return false;
}

// Declare the namespace
var fdTextareaController;

// Define anonymous function
(function() {

        // Create object private to the anonymous function
        function fdTextareaMaxlength(inp, maxlength) {
                this._inp       = inp;
                this._max       = Number(maxlength);
                var self        = this;

                self.maxlength = function() {
                        if(self._inp.disabled) return false;

                        if(self._inp.value.length > self._max) {
                                self._inp.value = self._inp.value.substring(0,self._max);
                                return false;
                        }

                        return true;
                }
                addEvent(self._inp, 'keypress', self.maxlength, false);
                addEvent(self._inp, 'blur',     self.maxlength, false);
                addEvent(self._inp, 'focus',    self.maxlength, false);

                // IE only event 'onpaste'

                // conditional compilation used to load only in IE win.

                /*@cc_on @*/
                /*@if (@_win32)
                addEvent(self._inp, 'paste', function(){ event.returnValue = false; self._inp.value = window.clipboardData.getData("Text").substring(0,self._max); }, true);
                /*@end @*/
        };

        // Construct the previously declared namespace
        fdTextareaController = {
                textareas: [],

                _construct: function( e ) {

                        var regExp_1 = /fd_max_([0-9]+){1}/ig;

                        var textareas = document.getElementsByTagName("textarea");

                        for(var i = 0, textarea; textarea = textareas[i]; i++) {
                                if(textarea.className && textarea.className.search(regExp_1) != -1) {
                                        max = parseInt(textarea.className.match(regExp_1)[0].replace(/fd_max_/ig, ''));
                                        if(max) fdTextareaController.textareas[fdTextareaController.textareas.length] = new fdTextareaMaxlength(textarea, max);
                                }
                        }

                },

                _deconstruct: function( e ) {
                        /* TODO: Clean up for IE memory leaks.. */
                }
        }
// Complete the anonymous function and call it immediately.
})();

// onload events
addEvent(window, 'load', fdTextareaController._construct, false);
addEvent(window, 'unload', fdTextareaController._deconstruct, false);