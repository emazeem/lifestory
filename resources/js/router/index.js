import { createRouter, createWebHistory } from 'vue-router';

// frontend views
import Login from '../views/Login.vue';
import Dashboard from '../views/admin/Dashboard.vue';
import Calendar from '../views/admin/Calendar.vue';
import Landing from '../views/front-end/Landing.vue';
import CreateYourLifestory from '../views/front-end/CreateYourLifestory.vue';
import NotFound from '../views/NotFound.vue';
import Thankyou from '../views/front-end/Thankyou.vue';
import Error from '../views/Error.vue';
import InActive from '../views/InActive.vue';

// admin views
import TimeSlotList from '../views/admin/time-slot/TimeSlotList.vue';
import SessionList from '../views/admin/session/SessionList.vue';
import SessionView from '../views/admin/session/SessionView.vue';
import Profile from '../views/admin/Profile.vue';
import Payment from '../views/front-end/Payment.vue';
import ForgetPassword from '../views/front-end/ForgetPassword.vue';
import CreateNewPassword from '../views/front-end/CreateNewPassword.vue';
import UploadVideo from '../views/admin/session/UploadVideo.vue';
import TransactionList from '../views/admin/transaction/TransactionList.vue';
import CustomerList from '../views/admin/user/CustomerList.vue';
import SubUserList from '../views/admin/user/SubUserList.vue';
import UserView from '../views/admin/user/UserView.vue';
import SettingList from '../views/admin/configuration/SettingList.vue';
import AsCustomerProfile from '../views/admin/AsCustomerProfile.vue';
import AsCustomerGallery from '../views/admin/AsCustomerGallery.vue';

// Subsciber views
import Customer from '../views/customer/Customer.vue';
import Post from '../views/customer/Post.vue';
import Settings from '../views/customer/Settings.vue';
import Gallery from '../views/customer/Gallery.vue';
import FriendsAndFamily from '../views/customer/FNF.vue';
import Notifications from '../views/customer/Notifications.vue';
import Subscription from '../views/customer/Subscription.vue';
import SuccessSubscription from '../views/customer/SuccessSubscription.vue';
import FailedSubscription from '../views/customer/FailedSubscription.vue';

// Subuser views
import CreatePassword from "../views/subuser/CreatePassword.vue";

import Test from '../views/Test.vue';
import ZoomCallback from '../views/ZoomCallback.vue';
import SwitchAccount from '../views/front-end/SwitchAccount.vue';

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes: [

        // Admin routes
        { path: '/login', component: Login, name: Login, meta: { loginRequired: false } },
        { path: '/dashboard', component: Dashboard, name: Dashboard, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/time-slots', component: TimeSlotList, name: TimeSlotList, meta: { loginRequired: true, role: 'Super Admin' } },
        // {path: '/calendar', component: Calendar, name: Calendar, meta: {loginRequired: true,role: 'Super Admin'}},
        { path: '/sessions', component: SessionList, name: SessionList, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/session/view/:id', component: SessionView, name: SessionView, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/profile', component: Profile, name: Profile, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/upload-video/:id', component: UploadVideo, name: UploadVideo, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/transactions', component: TransactionList, name: TransactionList, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/customers', component: CustomerList, name: CustomerList, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/subusers', component: SubUserList, name: SubUserList, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/user/view/:id', component: UserView, name: UserView, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/Settings', component: SettingList, name: SettingList, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/customer-profile/:id', component: AsCustomerProfile, name: AsCustomerProfile, meta: { loginRequired: true, role: 'Super Admin' } },
        { path: '/customer-gallery/:id', component: AsCustomerGallery, name: AsCustomerGallery, meta: { loginRequired: true, role: 'Super Admin' } },

        // Frontend routes
        { path: '/', component: Landing, name: Landing, meta: { loginRequired: false } },
        { path: '/schedule-lifestory', component: CreateYourLifestory, name: CreateYourLifestory, meta: { loginRequired: false } },
        { path: '/payment', component: Payment, name: Payment, meta: { loginRequired: false } },
        { path: '/create-new-password/:id/:token', component: CreateNewPassword, name: CreateNewPassword, meta: { loginRequired: false } },
        { path: '/forget-password', component: ForgetPassword, name: ForgetPassword, meta: { loginRequired: false } },
        { path: '/thankyou', component: Thankyou, name: Thankyou, meta: { loginRequired: false } },

        // Customer routes
        { path: '/home', component: Customer, name: Customer, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Both' } },
        { path: '/setting', component: Settings, name: Settings, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Both' } },
        { path: '/gallery', component: Gallery, name: Gallery, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Both' } },
        { path: '/friends-and-family', component: FriendsAndFamily, name: FriendsAndFamily, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Customer' } },
        { path: '/post/:id', component: Post, name: Post, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Both' } },
        { path: '/notifications', component: Notifications, name: Notifications, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Both' } },
        { path: '/subscription', component: Subscription, name: Subscription, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Customer' } },
        { path: '/subscription/successfull', component: SuccessSubscription, name: SuccessSubscription, meta: { loginRequired: true } },
        { path: '/subscription/failed', component: FailedSubscription, name: FailedSubscription, meta: { loginRequired: true } },

        // Sub user routes
        { path: '/create-password/:token', component: CreatePassword, name: CreatePassword, meta: { loginRequired: false } },

        // not found route
        { path: '/test', component: Test, name: Test, meta: { loginRequired: false } },
        { path: '/:catchAll(.*)', component: NotFound, loginRequired: false },
        { path: '/error', component: Error, name: Error, meta: { loginRequired: false } },
        { path: '/in-active', component: InActive, name: InActive, meta: { loginRequired: false, role: 'Not Admin', visibility: 'Customer' } },

        { path: '/zoom/callback', component: ZoomCallback, name: ZoomCallback, meta: { loginRequired: false } },
        { path: '/switch-account', component: SwitchAccount, name: SwitchAccount, meta: { loginRequired: true, role: 'Not Admin', visibility: 'Both' } },
    ],
});
router.beforeEach((to, from, next) => {

    const user = JSON.parse(localStorage.getItem('user'));
    const isLoggedIn = localStorage.getItem('sanctum_token') !== null;

    if (to.path === '/login' && isLoggedIn && user && user.role !== 'Super Admin') {
        next('/home');
    } else if (to.path === '/login' && isLoggedIn && user && user.role === 'Super Admin') {
        next('/dashboard');
    } else if (to.meta.loginRequired) {
        if (user) {
            const userRole = user.role;
            if (to.meta.loginRequired && !isLoggedIn) {
                next('/login');
            } else if (to.meta.role && (to.meta.role === 'Not Admin' && (userRole === 'Customer' || userRole === 'Sub User'))) {
                if (user.disabled == null) {
                    if (to.meta.visibility == 'Customer' && userRole === 'Customer') {
                        next();
                    } else if (to.meta.visibility == 'Sub User' && userRole === 'Sub User') {
                        next();
                    } else if (to.meta.visibility == 'Both') {
                        next();
                    } else {
                        next('/error');
                    }
                } else {
                    next('/in-active');
                }
            } else if (to.meta.role && (to.meta.role !== userRole)) {
                next('/error');
            } else {
                next();
            }
        } else {
            next('/login')
        }
    } else {
        next();
    }
});




export default router;
