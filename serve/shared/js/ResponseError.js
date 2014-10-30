function ResponseError( json ) {
        $.extend( this, Response.prototype, { json:json, handle:this.handle });
        console.log("ResponseError json received", json );
}
/**
 * Handle the response, override by implementation
 */
ResponseError.prototype.handle = function() {
        alert("Error report: "+this.json.result[0]);
}