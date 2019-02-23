import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const Home = () => import('~/pages/home').then(m => m.default || m)
const Welcome = () => import('~/pages/FrontPage').then(m => m.default || m)

const Login = () => import('~/pages/auth/login').then(m => m.default || m)
const Register = () => import('~/pages/auth/register').then(m => m.default || m)
const PasswordReset = () => import('~/pages/auth/password/reset').then(m => m.default || m)
const PasswordRequest = () => import('~/pages/auth/password/email').then(m => m.default || m)

const Settings = () => import('~/pages/user/settings/settings').then(m => m.default || m)
const SettingsProfile = () => import('~/pages/user/settings/profile').then(m => m.default || m)
const SettingsPassword = () => import('~/pages/user/settings/password').then(m => m.default || m)

const MarkersIndex = () => import('~/pages/marker/index').then(m => m.default || m)
const ToursIndex = () => import('~/pages/tour/index').then(m => m.default || m)

const MarkerShow = () => import('~/pages/marker/_show').then(m => m.default || m)
const TourShow= () => import('~/pages/tour/_show').then(m => m.default || m)


const About = () => import('~/pages/aboutus').then(m => m.default || m)
const Contact = () => import('~/pages/contact').then(m => m.default || m)

// User cart
const UserShoppingCart = () => import('~/pages/user/cart/cart').then(m => m.default || m)
const CartWish = () => import('~/pages/user/cart/wish').then(m => m.default || m)
const CartProcessing = () => import('~/pages/user/cart/processing').then(m => m.default || m)
const CartChecked = () => import('~/pages/user/cart/checked').then(m => m.default || m)
const CartHistory = () => import('~/pages/user/cart/history').then(m => m.default || m)

const Favorites = () => import('~/pages/user/favorites').then(m => m.default || m)
// admin Test
const AdminIndex = () => import('~/pages/admin/index').then(m => m.default || m)


const routes = [

  { path: '/', name: 'welcome', component: Welcome },
  { path: '/home', name: 'home', component: Home },

    //
  { path: '/sights', name: 'sights', component: MarkersIndex },
  { path: '/sight/:slug', name: 'sight.show', component: MarkerShow },
  { path: '/tours', name: 'tours', component: ToursIndex },
  { path: '/tour/:slug', name: 'tour.show', component: TourShow },
  { path: '/about', name: 'about', component: About },
  { path: '/contact', name: 'contact', component: Contact },
    //
    //User
    { path: '/user', component: UserShoppingCart,
      children: [
        { path: '', redirect: { name: 'cart.cart' } },
        { path: 'cart/cart', name: 'cart.cart', component: CartWish },
        { path: 'cart/processing', name: 'cart.processing', component: CartProcessing },
        { path: 'cart/checked', name: 'cart.checked', component: CartChecked },
        { path: 'cart/history', name: 'cart.history', component: CartHistory },

        { path: 'favorites', name: 'user.favorites', component: Favorites },
      ] },




  // User login/register/reset
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },
  { path: '/password/reset', name: 'password.request', component: PasswordRequest },
  { path: '/password/reset/:token', name: 'password.reset', component: PasswordReset },

    // User profile
  { path: '/settings',
    component: Settings,
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: SettingsProfile },
      { path: 'password', name: 'settings.password', component: SettingsPassword }
    ] },
    // {path:'/404', name: '404', component: { template: '<p>Page Not Found</p>'  }}

 // Admin test
  { path: '/admin', name: 'admin.index', component: AdminIndex }
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
