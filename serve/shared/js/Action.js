$(function() {
        var a = new Actions();
});
function Actions() {
        this.bind();
}
Actions.prototype.bind = function() {
        var hook = this;
        
        $(document).on('click', '.x', function( e ) {
                var $this = $(this);
                
                var type = $this.attr('data-type');
                var vars = $this.attr('data-vars');
                var confirm = $this.attr('data-confirm');
                
                var hasConfirm = typeof confirm !== 'undefined' ? true : false;
                if (hasConfirm) {
                        if (window.confirm(confirm)) {
                                hook.call( $this, type, vars );
                        }
                } else
                        hook.call( $this, type, vars );
                
                e.preventDefault();
                e.stopPropagation();
        });
}
Actions.prototype.call = function( $element, type, vars ) {
        
        var e = new External('xhr/a', {
                type:type,
                vars:vars
        });
        
        var hook = this;
        e.get(function(json) {
                
                var $view;

                if (json.result.hasOwnProperty('view')) {
                        
                        // Create jquery object from view
                        $view = $(json.result.view);
                        $('body').append($view);

                } else {
                        $view = $element;
                }

                if (json.hasOwnProperty('handler')) {
                        // Create instance that will handle itself and desired dom object
                        var inst = Instance.create( ['Action', 'Handler'], json.handler, $view );
                }                
        });
}