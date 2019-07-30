import Vue from 'vue'
import VueI18n from 'vue-i18n'
import enLang from './en'
import jaLang from './ja'
const lang = {
    en: enLang,
    jp: jaLang,
}
Vue.use(VueI18n)
const dateTimeFormats = {
    'en-US': {
        short: {
            year: 'numeric', month: 'short', day: 'numeric'
        },
        long: {
            year: 'numeric', month: 'short', day: 'numeric',
            weekday: 'short', hour: 'numeric', minute: 'numeric'
        },
    },
    'ja-JP': {
        short: {
            year: 'numeric', month: 'short', day: 'numeric'
        },
        long: {
            year: 'numeric', month: 'short', day: 'numeric',
            weekday: 'short', hour: 'numeric', minute: 'numeric', hour12: true
        },
    }
}
const i18n = new VueI18n({
    dateTimeFormats,
    locale: 'jp', // set locale
    messages: lang,
    fallbackLocale: 'jp',
})

export default i18n