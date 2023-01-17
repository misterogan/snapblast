import { createWebHistory, createRouter } from "vue-router";
import Dashboard from "../component/Dashboard";
import Login from "../component/Login";
import Campaign from '../component/Campaign'
import CampaignCreate from '../component/CampaignCreate'
import CampaignEdit from "../component/CampaignEdit";
import CampaignDetail from "../component/CampaignDetail";
import Datatable from "../component/Datatable";
import DatatableDetail from "../component/DatatableDetail";
import Postback from "../component/Postback";
import PostbackCreate from "../component/PostbackCreate";
import PostbackEdit from "../component/PostbackEdit";
import SettingTeam from "../component/SettingTeam";
import SettingAccount from "../component/SettingAccount";
import Notification from "../component/Notification";
import Company from "../component/Company";
import CompanyCreate from "../component/CompanyCreate";
import CompanyEdit from "../component/CompanyEdit";
import User from '../component/User'
import UserCreate from "../component/UserCreate";
import UserEdit from "../component/UserEdit";
import Main from "../component/Main";
import api from "../api";
import UserInvitation from "../component/UserInvitation";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Main,
        children : [
            {
                path: "/dashboard",
                name: "Dashboard",
                component: Dashboard,
            },
            {
                path: "/campaign",
                name: "Campaign",
                component: Campaign,
            },
            {
                path: "/campaign/create",
                name: "CampaignCreate",
                component: CampaignCreate,
            },
            {
                path: "/campaign/edit/:ad_id",
                name: "CampaignEdit",
                component: CampaignEdit,
            },
            {
                path: "/campaign/detail/:ad_id",
                name: "CampaignDetail",
                component: CampaignDetail,
            },
            {
                path: "/datatable",
                name: "Datatable",
                component: Datatable,
            },
            {
                path: "/datatable/:data_code",
                name: "DatatableDetail",
                component: DatatableDetail,
            },
            {
                path: "/postback",
                name: "Postback",
                component: Postback,
            },
            {
                path: "/postback/create",
                name: "PostbackCreate",
                component: PostbackCreate,
            },
            {
                path: "/postback/edit/:code",
                name: "PostbackEdit",
                component: PostbackEdit,
            },
            {
                path: "/notification",
                name: "Notification",
                component: Notification,
            },
            {
                path: "/setting/team",
                name: "SettingTeam",
                component: SettingTeam,
            },
            {
                path: "/setting/account",
                name: "SettingAccount",
                component: SettingAccount,
            },
            {
                path: "/company",
                name: "Company",
                component: Company,
            },
            {
                path: "/company/create",
                name: "CompanyCreate",
                component: CompanyCreate,
            },
            {
                path: "/company/edit/:code",
                name: "CompanyEdit",
                component: CompanyEdit,
            },
            {
                path: "/user",
                name: "User",
                component: User,
            },
            {
                path: "/user/create",
                name: "UserCreate",
                component: UserCreate,
            },
            {
                path: "/user/edit/:email",
                name: "UserEdit",
                component: UserEdit,
            },
        ],
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/invitation/:invitation_token",
        name: "UserInvitation",
        component: UserInvitation,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        // always scroll to top
        if(to.name && to.name === 'CampaignDetail' && to.query.action){
            return { top: 600 }
        }
        return { top: 0 }
        // if(to.name !== "CampaignDetail"){
        //
        // }
    },
});
router.beforeEach((to, from, next) => {
    $('body').removeClass('overflow-hidden');
    $('.router-link-active').removeClass('router-link-active router-link-exact-active');
    let isAuthenticated = api.is_login();
    // if (to.name !== 'Login' && !isAuthenticated) next({ name: 'Login' })
    // else next()
    if (to.name !== 'Login' && to.name !== 'UserInvitation' && !isAuthenticated) {
        next({ name: 'Login',query: { redirect: to.fullPath }, })
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        // return {
        //     path: '/login',
        //     // save the location we were at to come back later
        //     query: { redirect: to.fullPath },
        // }
    }else next()
})
export default router;
