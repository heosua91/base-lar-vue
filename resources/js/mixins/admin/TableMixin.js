import {commonFunctionMixin} from '../commonFunctionMixin'
import { EventBus } from '../../events/eventBus'

export const tableMixin = {
    mixins: [commonFunctionMixin],
    data() {
        return {
            previousComponent: '',
            searchInputIgnoreStore: [],
            componentType: 'page',
            previousPage: 1,
            current: 0,
            searchInput: {},
            sortOrders: {},
            searchLength: 'default',
            perPage: [20, 50, 100, 200],
            payload: {
                tableData: {
                    isValidateSearchInput: 0,
                    router: 2,
                    draw: 0,
                    length: 'default',
                    search: {},
                    column: '',
                    dir: 'desc',
                    url: ''
                },
                url: ''
            },
            storeActionGetData: '',
            itemChecked: [],
            objDataValueCheckBox: [],
            storeName: '',
            sortKey: 'created_by',
            listDealers: []
        }
    },
    methods: {
        restoreSearchInput() {
            let _this = this
            if (!this.isRestoreSearchInputFromStorage) {
                localStorage.removeItem(this.componentType + '_search_input_' + this.$route.name);
                localStorage.removeItem(this.componentType + '_sort_paginate_' + this.$route.name);
                return
            }

            let oldSearchInput = JSON.parse(localStorage.getItem(this.componentType + '_search_input_' + this.$route.name));
            let oldSortPaginate = JSON.parse(localStorage.getItem(this.componentType + '_sort_paginate_' + this.$route.name));
            if (oldSearchInput) {
                Object.keys(_this.searchInput).forEach(function (key) {
                    if (_this.searchInputIgnoreStore.indexOf(key) === -1) {
                        _this.payload.tableData.search[key] = oldSearchInput.searchInput[key];
                        _this.searchInput[key] = oldSearchInput.searchInput[key]
                    }
                })
                this.payload.tableData.length = oldSearchInput.length;
                this.searchLength = oldSearchInput.length;
            }
            if (oldSortPaginate) {
                this.payload.tableData.column = oldSortPaginate.column;
                this.payload.tableData.dir = oldSortPaginate.dir;
                this.sortOrders = oldSortPaginate.sortOrders;
                this.previousPage = oldSortPaginate.previousPage;
            }
        },
        getList(url) {
            EventBus.$emit('dataChanged');
            this.payload.tableData.search['business_code'] = this.businessCode;
            this.$store.dispatch(this.storeActionGetData, {
                url: (url ? url : this.payload.url),
                params: this.payload.tableData
            })
            window.scrollTo(0,0);
        },
        changePage(number) {
            this.previousPage = number;
            this.saveSortAndPaginateToStorage();
            this.$store.dispatch(this.storeActionGetData, {
                url: this.payload.url + '?page=' + number,
                params: this.payload.tableData
            })
        },
        sortBy(key) {
            this.payload.tableData.isValidateSearchInput = 0;
            this.$commonHelper.sortBy(this.sortKey, this.sortOrders, this.payload.tableData, key)
            this.sortKey = key;
            this.$store.dispatch(this.storeActionGetData, {
                url: this.payload.url + '?page=' + this.pagination.currentPage,
                params: this.payload.tableData
            })
            this.saveSortAndPaginateToStorage();
        },
        checkAll() {
            this.itemCheckAll = !this.itemCheckAll
        },
        instantEdit(index) {
            this.items[index].instant_edit = !this.items[index].instant_edit
        },
        search() {
            let _this = this;
            let keysNeedCheckLength = ['dealer_code'];
            this.previousPage = 1;
            Object.keys(_this.searchInput).forEach(function (key) {
                if (keysNeedCheckLength.includes(key)) {
                    if (_this.searchInput[key].length >= 0) {
                        _this.payload.tableData.search[key] = _this.searchInput[key];
                    }
                } else {
                    _this.payload.tableData.search[key] = _this.searchInput[key];
                }
            });
            _this.payload.tableData.length = _this.searchLength;
            _this.payload.tableData.search['business_code'] = _this.businessCode;
            this.payload.tableData.isValidateSearchInput = 1;
            this.getList();
            this.saveSearchInputToStorage();
        },
        getListDealer() {
            let _this = this
            axios.get(this.$laroutes.route('010.dealers.get_list_select_box', {business_code: this.businessCode}))
                .then(response => {
                    _this.listDealers = response.data.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        saveSearchInputToStorage() {
            localStorage[this.componentType + '_search_input_' + this.$route.name] = JSON.stringify({
                searchInput: this.searchInput,
                length: this.searchLength,
            });
            this.saveSortAndPaginateToStorage();
        },
        saveSortAndPaginateToStorage() {
            localStorage[this.componentType + '_sort_paginate_' + this.$route.name] = JSON.stringify({
                column: this.payload.tableData.column,
                dir: this.payload.tableData.dir,
                sortOrders: this.sortOrders,
                previousPage: this.previousPage,
            });
        },
        clearSearchInput(componentType = 'page') {
            localStorage.removeItem(componentType + '_search_input_' + this.$route.name);
            localStorage.removeItem(componentType + '_sort_paginate_' + this.$route.name);
        },
    },
    computed: {
        isRestoreSearchInputFromStorage() {
            return this.componentToRestoreSearchInput ? this.componentToRestoreSearchInput.indexOf(this.previousComponent) !== -1 : false
        },
        businessCode() {
            return this.$store.state.commonStore.business_code
        },
        items() {
            return this.$store.state[this.storeName].items
        },
        errorsSearch() {
            return this.$store.state[this.storeName].errorsSearch ? this.$store.state[this.storeName].errorsSearch : {}
        },
        errorsAdd() {
            return this.$store.state[this.storeName].errorsAdd ? this.$store.state[this.storeName].errorsAdd : {}
        },
        itemCheckAll: {
            get: function () {
                let listItem = this.createArrayKeyByObjectOrArray(this.items, this.objDataValueCheckBox);
                return this.items.length > 0 ? this.checkArrayInArray(listItem, this.itemChecked) : false;
            },
            set: function (value) {
                let listItem = this.createArrayKeyByObjectOrArray(this.items, this.objDataValueCheckBox);
                this.itemChecked = this.removeArrayInArray(listItem, this.itemChecked);
                if (value)
                    this.itemChecked = this.itemChecked.concat(listItem);
            }
        },
        pagination() {
            let fullDataStore = this.$store.state[this.storeName].fullData;
            this.current = fullDataStore.current_page;
            return fullDataStore.total > 0 ? {
                lastPage: fullDataStore.last_page,
                currentPage: fullDataStore.current_page,
                total: fullDataStore.total,
                lastPageUrl: fullDataStore.last_page_url,
                nextPageUrl: fullDataStore.next_page_url,
                prevPageUrl: fullDataStore.prev_page_url,
                from: fullDataStore.from,
                to: fullDataStore.to
            } : {
                lastPage: 0,
                currentPage: 0,
                total: 0,
                lastPageUrl: '',
                nextPageUrl: '',
                prevPageUrl: '',
                from: 0,
                to: 0
            }
        }
    }
};
