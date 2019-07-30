import Vue from 'vue'
import Router from 'vue-router'

import ExampleComponent from '../components/ExampleComponent'

Vue.use(Router)

const router = new Router({
    scrollBehavior() {
        return {x: 0, y: 0};
    },
    mode: 'history',
    routes: [
        {
            path: '/admin/index',
            name: 'ExampleComponent',
            component: ExampleComponent,
            meta: {
                title: 'admin.title_page.index'
            },
        },
    ],
})


router.beforeEach((to, from, next) => {

    next()
});
export default router
