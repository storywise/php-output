function AssetJs( path ) {
        $.extend( this, Asset.prototype, {
                path:path,
                load:this.load
        });
}
AssetJs.prototype.load = function() {
        var hook = this;
        $.getScript( this.path, function(data, textStatus, jqxhr ) {
                //console.log( data ); // Data returned
                //console.log( textStatus ); // Success
                //console.log( jqxhr.status ); // 200
                hook.ready();
        });
}
