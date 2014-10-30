function ResponseFatal( json ) {
        $.extend( this, Response.prototype, { json:json, handle:this.handle });
}
/**
 * Handle the response, override by implementation
 */
ResponseFatal.prototype.handle = function() {
        alert('Fatal error occurred');
}