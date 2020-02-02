import Vue from "vue";
import Router from "vue-router";

import Home from "@/components/pages/Home";

import Register from "@/components/pages/auth/Register";
import Login from "@/components/pages/auth/Login";

import MyAds from "@/components/pages/ads/MyAds";
import ShowAd from "@/components/pages/ads/ShowAd";
import NewAd from "@/components/pages/ads/NewAd";
import BoostAd from "@/components/pages/ads/BoostAd";
import EditAd from "@/components/pages/ads/EditAd";

import MessagesIndex from "@/components/pages/messages/Index";

import error from "@/components/pages/error/index";

import store from "../store/";

Vue.use(Router);

let router = new Router({
    routes: [
        {
            path: "/",
            name: "Home",
            component: Home
        },
        {
            path: "/auth/register",
            name: "register",
            component: Register
        },
        {
            path: "/auth/login",
            name: "login",
            component: Login
        },
        {
            path: "/ad/new",
            name: "NewAd",
            component: NewAd,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: "/ad/my",
            name: "MyAds",
            component: MyAds,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: "/ad/:id/edit",
            name: "EditAd",
            component: EditAd,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: "/messages",
            name: "MessagesIndex",
            component: MessagesIndex,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: "/ad/:id",
            name: "ShowAd",
            component: ShowAd
        },
        {
            path: "/ad/:id/boost",
            name: "BoostAd",
            component: BoostAd
        },
        {
            path: "*",
            name: "error",
            component: error
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isAuthenticated) {
            next();
            return;
        }
        next("/auth/login");
    } else {
        next();
    }
});

export default router;
