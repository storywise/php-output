function ResponseSuccess( json ) {
        $.extend( this, Response.prototype, {
                json:json, 
                handle:this.handle
        });
}
ResponseSuccess.prototype.expect = 0;
ResponseSuccess.prototype.getAssets = function( type ) {
        var i, path, obj;
        if (this.json.hasOwnProperty('assets') && this.json.assets.hasOwnProperty(type)) {
                var max = this.json.assets[type].length;
                this.expect += max;
                for ( i = 0; i < max; i++) {
                        path = this.json.assets[type][i];
                        obj = Asset.get(type, path);
                        obj.onReady( $.proxy( this.onAssetLoad, this ));
                        obj.load();
                }
        }
}
ResponseSuccess.prototype.onAssetLoad = function( asset ) {
        this.expect--;
        if (this.expect === 0) {
                this.callback();
                this.callback = null;
        }
}
/**
 * Handle the response, override by implementation
 */
ResponseSuccess.prototype.handle = function( callback ) {
        if (this.json.hasOwnProperty('assets')) {
                this.callback = callback;
                // Get required assets if exist
                this.getAssets('js');
                this.getAssets('css');
        } else
                callback();
}