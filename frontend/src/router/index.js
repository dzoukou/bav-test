import Vue from 'vue'
import VueRouter from 'vue-router'
import {canNavigate} from '@/libs/acl/routeProtection'
import {isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser} from '@/auth/utils'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    scrollBehavior() {
        return {x: 0, y: 0}
    },
    routes: [
        {
            path: '/',
            name: 'home',
            redirect: {name: 'products'}
        },
        {
            path: '/products',
            name: 'products',
            component: () => import('@/views/Products'),
            meta: {
                pageTitle: 'Products',
                resource: 'Product',
                breadcrumb: [
                    {
                        text: 'List',
                        active: true,
                    },
                ],
            },
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('@/views/Login.vue'),
            meta: {
                layout: 'full',
                redirectIfLoggedIn: true,
                resource: 'Auth'
            },
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('@/views/Register'),
            meta: {
                layout: 'full',
                redirectIfLoggedIn: true,
                resource: 'Auth'
            },
        },
        {
            path: '/error-404',
            name: 'error-404',
            component: () => import('@/views/error/Error404.vue'),
            meta: {
                layout: 'full',
            },
        },
        {
            path: '*',
            redirect: 'error-404',
        },
    ],
})

router.beforeEach((to, _, next) => {
    const isLoggedIn = isUserLoggedIn()
    console.log('loggedin', isLoggedIn, to.name)
    if (!canNavigate(to)) {
        // Redirect to login if not logged in
        if (!isLoggedIn) return next({name: 'login'})

        // If logged in => not authorized
        return next({name: 'misc-not-authorized'})
    }

    // Redirect if logged in
    if (to.meta.redirectIfLoggedIn && isLoggedIn) {
        const userData = getUserData()
        next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
    }

    return next()
})

export default router
