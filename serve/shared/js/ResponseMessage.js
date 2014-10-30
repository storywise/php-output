function Message( message ) {
        this.message = '';
        if ( message instanceof Object ) {
                this.fromObject( message );
        } else {
                this.message = message;
        }
}
Message.prototype.fromObject = function( obj ) {

        var mType;
        for( var key in obj ) {
                
                mType = typeof obj[ key ];
                
                if ( mType ===  'string' || mType ===  'boolean' ) {
                        // Sometimes we get an index, sometimes a string
                        var keyIndex = parseInt( key );
                        // Only show key when its a string
                        this.message +=  ( isNaN( keyIndex ) ? key+' = ' : '' )+''+obj[ key ]+"\n";
                        
                } else if ( mType !== 'function' ) {
                        if (obj[key].hasOwnProperty('text')) {
                                this.message +=  '<div class="snippet"><b>'+obj[key].field+'</b><br>'+obj[key].text+"</div>";
                        } else
                                this.message +=  'Please check this value in your input: '+obj[key]+"<br>";
                        
                }
        }
}
Message.prototype.output = function() {
        ClientOutput.alert( this.message );
}