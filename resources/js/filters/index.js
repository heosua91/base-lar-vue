const moment = require('moment')

export function formatDate(value, format) {
    if (value) {
        return moment(String(value)).format(format)
    }
}