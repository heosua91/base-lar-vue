require('./bootstrap');

/*Import*/
import Vue from 'vue'
import commonStore from './stores/commonStore'
import router from './router/admin'
import App from './Admin.vue'
import Breadcrumb from './components/commons/Breadcrumb'
import Pagination from './components/commons/Pagination'
import DataTable from './components/commons/DataTable'
import DatePicker from './components/commons/DatePicker'

import i18n from './lang/lang';
import VueCookie from 'vue-cookies'
import Paginate from 'vuejs-paginate'
import VueLaroute from 'vue-laroute'

import routes from './laroute'

import { loadProgressBar } from 'axios-progress-bar'
import 'axios-progress-bar/dist/nprogress.css'
loadProgressBar({ showSpinner: true });

import {EventBus} from './events/eventBus'
Vue.use(require('vue-scrollto'))

/* Helpers */
import CommonHelper from './helpers/common'
/* Configs */
import Constant from './configs/constant'

i18n.locale = VueCookie.get('locale') || 'jp'

Vue.prototype.$commonHelper = CommonHelper;
Vue.prototype.$constant = Constant;
Vue.prototype.$eventBus = EventBus;

/*Define, Use*/
const moment = require('moment')

Vue.use(require('vue-moment'), {
    moment
});
Vue.use(router)
Vue.use(VueLaroute, {
    routes,
    accessor: '$laroutes'
})
Vue.component('paginate', Paginate);
Vue.component('breadcrumb', Breadcrumb);
Vue.component('datatable', DataTable);
Vue.component('pagination', Pagination);
Vue.component('date-picker', DatePicker);

import * as filters from './filters';
// register global utility filters.
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key]);
});

window.axios.interceptors.response.use(null, function (error) {
    let appDebug = process.env.MIX_APP_DEBUG ? (process.env.MIX_APP_DEBUG === 'true') : false;
    if (!appDebug) {
        if (!error.response) {
            window.location = '/admin/index'
        }
        let arrayErrorCode = [401, 403, 404, 419, 500];
        if (arrayErrorCode.includes(error.response.status)) {
            window.location = '/admin/error/' + error.response.status
        }
    }

    return Promise.reject(error);
});

/*Instance*/
const appAdmin = new Vue({
    el: '#app-admin',
    i18n,
    commonStore,
    router,
    components: {App},
    template: '<App/>'
});

export default appAdmin;