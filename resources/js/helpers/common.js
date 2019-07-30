module.exports = {
    setText(text, params = [], symbol = '?') {
        for (var i = 0; i < params.length; i++) {
            text = text.replace((symbol instanceof Array ? symbol[i] : symbol), params[i]);
        }

        return text;
    },
    createArrayKeyByObjectOrArray(object_array, key) {
        let result = [];
        if (object_array) {
            object_array.forEach(function (data) {
                result.push(data[key])
            });
        }

        return result;
    },
    checkArrayInArray(arrayA, arrayB) {
        let arrayC = [];
        arrayA.forEach(function (item, index) {
            if (arrayB.indexOf(item) > -1) {
                arrayC.push(item);
            }
        });

        return (arrayA.length > 0 && arrayB.length > 0) && (arrayC.length == arrayA.length);
    },
    replaceArray(arrayA, arrayB, fieldCompare, fieldReplace) {
        for (i = 0; i < arrayA.length; i++) {
            for (j = 0; j < arrayB.length; j++) {
                if (arrayA[i][fieldCompare] == arrayB[j][fieldCompare]) {
                    arrayA[i][fieldReplace] = arrayB[j][fieldReplace]
                }
            }
        }

        return arrayA;
    },
    formatNumber(number) {
        let num = parseInt(number) || 0;
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    },
    formatMoney(amount, decimalCount = 0, decimal = ".", thousands = ",") {
        decimalCount = isNaN(decimalCount = Math.abs(decimalCount)) ? 0 : decimalCount;
        var negativeSign = amount < 0 ? "-" : "";
        let num = parseInt(amount = Math.abs(Number(amount) || 0)).toString();

        return negativeSign + num.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - num).toFixed(decimalCount).slice(2) : "");
    },
    formatFee(number) {
        return typeof(number) !== 'undefined' && number !== '' ? parseInt(number).toLocaleString() : ''
    },
    inputNumber: (evt, regex, item, maxLength) => {
        var keyChar = String.fromCharCode(evt.keyCode);
        if (!regex.test(keyChar)) {
            evt.preventDefault();
            return false;
        }
        if (item !== undefined && maxLength != undefined && item.toString().length == maxLength) {
            evt.preventDefault();
            return false;
        }

        return true;
    },
    subStringText(str, maxLengthText) {
        return (str && str.length > maxLengthText) ? str.substring(0, maxLengthText) + '...' : str;
    },
    sanitizeHtml(dirtyValue) {
        if (dirtyValue) {
            return sanitizeHTML(dirtyValue, {
                allowedTags: sanitizeHTML.defaults.allowedTags.concat(['img', 'span']),
                allowedAttributes: {
                    '*': [ 'href', 'align', 'alt', 'center', 'bgcolor', 'name', 'target', 'src', 'style']
                }
            });
        }
    },
}