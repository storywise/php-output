function Asset( path ) {
        this.path = path;
}
Asset.get = function( type, arg ) {
        var inst = Instance.create('Asset', type, arg );
        if (inst !== false)
                return inst;
        return false;
}
Asset.prototype.load = function() {
        
        }
Asset.prototype.ready = function() {
        console.log("Asset", this.path, 'ready!', typeof this.onReady);
        if (typeof this.onReady === 'function') {
                this.onReady( this.path );
                this.onReady = null;
        }
}
Asset.prototype.onReady = function( func ) {
        this.onReady = func;
}