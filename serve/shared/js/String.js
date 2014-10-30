String.prototype.injectAt = function( str, pos ) {
        return [this.slice(0, pos), str, this.slice(pos)].join('');
}
String.prototype.findAndReplace = function( needle, replace ) {
        return this.split(needle).join( replace );
}
String.prototype.isUCFirst = function() {
        if( this[0].toUpperCase() === this[0]){
                return true;       
        }
        return false;
}
String.prototype.ucFirst = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
}
/*String.prototype.baseName = function(suffix) {
        var path = this;
        // http://kevin.vanzonneveld.net
        var b = path.replace(/^.*[\/\\]/g, '');
        if (typeof(suffix) == 'string' && b.substr(b.length - suffix.length) == suffix) {
                b = b.substr(0, b.length - suffix.length);
        }
        return b;
}*/
/*String.prototype.stripTags = function() {
        return this
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
String.prototype.period = function() {
        if (this.length == 0) 
                return this;
        return this.slice(-1) != '.' ? this+'.' : this;
}
String.prototype.squote = function() {
        if (this.length == 0) 
                return this;
        return '&lsquo;'+this+'&rsquo;';
}
String.prototype.dquote = function() {
        if (this.length == 0) 
                return this;
        return '&ldquo;'+this+'&rdquo;';
}*/
String.prototype.stripTags = function(){
        return this
        .replace(/(<(br[^>]*)>)/ig, '\n')
        .replace(/(<([^>]+)>)/ig,'\n');
//.replace(/(\[(br[^>]*)\])/ig, '<br>');
}

function Canonical() {}
Canonical.prototype.parse = function(str) {
        var s = str;
        s = s.toLowerCase().replace(/[^0-9a-z/-]/g,"-");
        return s;
}
Canonical.prototype.isValid = function(str) {
        var a = str.match(/[^\w\s]/gi);
        var b = str.match(/ /g);
        var c = str.match(/_/g);
        return !a && !b && !c;
}