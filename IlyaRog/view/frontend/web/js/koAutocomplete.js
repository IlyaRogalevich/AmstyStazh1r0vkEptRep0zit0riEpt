define(['uiComponent', 'jquery', 'mage/url'], function (Component, $, urlBuilder) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchResult: [],
            minChars: 2,
            searchUrl: urlBuilder.build('IlyaRog/ForAjaxSku/AjaxCollection'),
            searchResultList: null
        },
        initObservable: function () {
            this._super();

            this.observe(
                ['searchText', 'searchResult']
            );
            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (searchValue) {

            if (searchValue.length > this.minChars) {
                $.ajax({
                    url: this.searchUrl,
                    type: 'POST',
                    context: this,
                    data: {
                        sku : searchValue
                    }})
                    .done(function (response){
                        this.searchResult(response);
                    }.bind(this));
            } else {
                this.searchResult([]);
            }
        }
    });
})
