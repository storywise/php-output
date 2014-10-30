function ActionHandlerAbra( $element ) {
        console.log("ActionHandlerAbra", $element);
        this.$element = $element;
        this.bind();
}
ActionHandlerAbra.prototype.bind = function() {
        this.$element.on('click', function() {
                $(this).text('yes!');
        });       
}