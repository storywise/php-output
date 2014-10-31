function AssetCss( path ) {
        $.extend( this, Asset.prototype, {
                path:path,
                load:this.load
        });
}
AssetCss.prototype.load = function() {
        
        $("<link/>", {
                rel: "stylesheet",
                type: "text/css",
                href: this.path,
                'class':'assetCss'
        }).appendTo("head");
        
        // Not a neat way in sync with actual load
        this.ready();
}
