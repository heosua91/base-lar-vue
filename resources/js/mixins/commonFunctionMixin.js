export const commonFunctionMixin = {
    methods: {
        createArrayKeyByObjectOrArray(object_array, key) {
            let result = [];
            if (object_array && object_array.length > 0) {
                object_array.forEach(function (data) {
                    result.push(data[key])
                });
            }

            return result;
        },
        checkArrayInArray(arrayA, arrayB) {
            let arrayC = [];
            arrayA.forEach(function(item, index) {
                if (arrayB.indexOf(item) > -1) {
                    arrayC.push(item);
                }
            });

            return (arrayA.length > 0 && arrayB.length > 0) && (arrayC.length == arrayA.length);
        },
        removeArrayInArray(arrayA, arrayB) {
            return arrayB.filter(function (value, index) {
                return arrayA.indexOf(value) == -1;
            });
        },
        sortBy(key) {
            if (this.payload.tableData.column != key) {
                this.payload.tableData.dir = 'asc';
                this.sortOrders[key] = 1;
                this.payload.tableData.column = key;
            } else {
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.payload.tableData.column = key;
                this.payload.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
            }
        },
        setValueSearchForTargetSearchAndDateFromTo(select, key, val) {
            let _this = this;
            this[select].forEach(function(item){
                if (item.value == _this.currentSearchKey[key]) {
                    if (Array.isArray(val)) {
                        let objTemp = {}
                        val.forEach(function(itemVal){
                            objTemp[itemVal] = _this.currentSearchVal[itemVal]
                        })
                        _this.searchInput[item.value] = objTemp

                    } else {
                        _this.searchInput[item.value] = _this.currentSearchVal[val]
                    }
                } else {
                    _this.searchInput[item.value] = ''
                }
            });
        }
    },
};