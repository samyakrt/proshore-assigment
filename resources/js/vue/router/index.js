import * as VueRouter from 'vue-router'

const routes = new VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes: [
        {
            path: '/employees',
            name: 'employees.index',
            component: () => import('../pages/employees/index.vue')
        },
        {
            path: '/employees/create',
            name: 'employees.create',
            component: () => import('../pages/employees/form.vue')
        },
        {
            path: '/employees/:employee',
            name: 'employees.show',
            component: () => import('../pages/employees/show.vue')
        },
        {
            path: '/employees/:employee/edit',
            name: 'employees.edit',
            component: () => import('../pages/employees/form.vue')
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'NotFound',
            component: () => import('../pages/404.vue')
        },
    ]
})

export default routes