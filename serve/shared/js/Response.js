function Response( json ) {
        this.json = json;
}
Response.get = function( type, arg ) {
        var inst, arg;
        inst = Instance.create('Response', type, arg );
        if (inst !== false)
                return inst;
        return false;
}
/**
 * Handle the response, override by implementation
 */
Response.prototype.handle = function() {
        
}

function Response2( json ) {
        this.json = json;
}
/**
 * Dont log fatal, causes looping
 */
Response2.prototype.fatal = function() {
        console.log("[Response2: malformed]");
}
Response2.prototype.handle = function() {
        
        var m = false;
        var isErrorHandle = false;
        
        if (this.json == undefined || this.json == null ) {
                Evt.log('externalFailed','noResponse2', this.json );
                m = new Message('There was no response given.');
                isErrorHandle = true;
                
        } else if ( typeof this.json.result === 'undefined'){
                Evt.log('externalFailed','malformed', this.json );
                m = new Message( 'Malformed result' );
                isErrorHandle =true;
                
        } else if (this.json.result.hasOwnProperty('exception')) {
                Evt.log('externalFailed','exception', this.json );
                m = new Message( this.json.result.exception );
                isErrorHandle = true;
                
        } else if (this.json.result.hasOwnProperty('error')) {
                
                if (this.json.result.error.hasOwnProperty('validation')) {
                        m = new Message( this.json.result.error.validation );
                        isErrorHandle = true;
                        
                } else if (this.json.result.error.hasOwnProperty('message')) {
                        
                        m = new Message( this.json.result.error.message );
                        isErrorHandle = true;
                        
                } else if (this.json.result.error.hasOwnProperty('login')) {
                        
                        m = new Message( this.json.result.error.message );
                        isErrorHandle = false;
                        
                } else if (this.json.result.error.hasOwnProperty('custom')) {
                        // Handled in custom receiver
                        isErrorHandle = false;
                } else if (this.json.result.error.length > 0) {
                        m = new Message(this.json.result.error);
                        isErrorHandle = true;
                } else {
                        m = new Message('Unknown error occured');
                        isErrorHandle = true;
                }
        }
        if (m !== false)
                m.output();
        return isErrorHandle;
}
