define([], function () {
    return function (koAutocomplete){
        return koAutocomplete.extend({
            handleAutocomplete: function () {
                this._super();
                this.minChars = 5;
            }
        })

    }
})
