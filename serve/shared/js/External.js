/**
 * Make an external XHR call 
 * @param call XHR function
 * @param data XHR data package
 */
function External( call, data ) {
        this.call = call;
        this.data = data;
}
External.prototype.xhrPath = '';
External.prototype.prependURL = true;
External.prototype.setCustomResponse = function( response ) {
        this.response = response;
}
/**
 * Expects one parameter 
 * @param callback
 * @param silent With, or without loading screen
 */
External.prototype.get = function( callback, silent ) {
        
        // Loading screen, or silent load?
        silent = typeof silent === 'undefined' ? false : silent;
        
        // Allow for foreign domain calls based on flag setting
        var method = this.prependURL === true ? URL+this.xhrPath+this.call : this.call;
        
        if (!silent)
                this.loading(true);
        
        var hook = this;
        
        $.post( method, this.data, function(json) {

                // Loading is done
                hook.loading(false);

                var r = Response.get(json.type, json );
                if (json.type !== 'success') {
                        r.handle();
                } else {
                        r.handle(function() {
                                callback(json);        
                        });
                }
                
        }, "json").error(
                function() {
                        // Loading is done
                        if (!silent)
                                hook.loading(false);
                        
                        var r = Response.get('fatal');
                        r.handle();
                });
}

/* When a transaction is in progress */
External.prototype.loading = function( isOn) {
        
        console.log("loading ", isOn );
        return;
        
        var e = $('#editorLoading');
        var body = $('body');
        if (e.length==0) {
                var image = PROFILE_DATA.brandplain;
                var style = Style.getBgCover( image );
               
                $('body').append(
                        '<div id="editorLoading" class="aniBg">'+
                        '<div class="editorLoadingText"><div style="'+style+'"></div></div>'+
                        '</div>');
                
                //Eén momentje...
                e = $('#editorLoading');
        }
        if (isOn) {
                if (!body.hasClass('busy')) {
                        body.addClass('busy');
                }
                if (!e.hasClass('busy')) {
                        e.addClass('busy');
                }
        } else {
                body.removeClass('busy');
                e.removeClass('busy');
        }
}


