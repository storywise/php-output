function Instance() {}
Instance.create = function( abs, options, param ) {
        console.log("Instance.create", abs, options, param);
        var str = '';
        if (typeof abs === 'string')
                abs = [ abs ];
        if (typeof options === 'string')
                options = [ options ];
        var hasOptions = typeof options === 'object';
        if (!hasOptions)
                options = [];
        
        var i, inst, absClass;
        
        // Compose abstract
        for( i = 0; i < abs.length; i++) {
                str += abs[i].toLowerCase().ucFirst();
        }
        
        absClass = str;
        
        // Compose types and check availability
        for( i = 0; i < options.length; i++) {
                var option = options[ i ];
                str += hasOptions ? option.toLowerCase().ucFirst() : option;
                inst = Instance.get( str, param );
                if (inst !== false)
                        return inst;
        }
        
        // Create abstract if no type was found
        inst = Instance.get( absClass, param );
        if (inst !== false)
                return inst;
        
        return false;
}
Instance.get = function( className, param ) {
        if (typeof window[ className ] === 'function') {
                if (typeof param !== 'undefined')
                        return new window[ className ]( param );
                return new window[ className ]();
        }
        console.log("Instance.get", className, param );
        return false;
}