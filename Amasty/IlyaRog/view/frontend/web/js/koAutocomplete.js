define(['uiComponent', 'jquery', 'mage/url'], function (Component, $, urlBuilder) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchResult: [],
            availableSku: ['24-MB', '24-MB01'],
            searchUrl: ('ForAjaxSku/AjaxCollection'),
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

            if (searchValue.length > 2) {
                $.ajax({
                    url: this.searchUrl,
                    type: 'POST',
                    context: this,
                    data: {
                        sku : searchValue
                    }})
                    .done(function (response){
                        this.availableSku = response;

                        var filteredSearch = this.availableSku.filter(function (item) {
                                return item.indexOf(searchValue) !== -1;
                            });
                        this.searchResult(filteredSearch);
                    }.bind(this));
            } else {
                this.searchResult([]);
            }
        }
    });
})
