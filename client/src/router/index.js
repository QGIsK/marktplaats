import Vue from "vue";
import Router from "vue-router";

import Home from "@/components/pages/Home";

import Register from "@/components/pages/auth/Register";
import Login from "@/components/pages/auth/Login";

import MyAds from "@/components/pages/ads/MyAds";
import ShowAd from "@/components/pages/ads/ShowAd";
import NewAd from "@/components/pages/ads/NewAd";

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
            path: "/ad/:id",
            name: "ShowAd",
            component: ShowAd
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            next();
            return;
        }
        next("/auth/login");
    } else {
        next();
    }
});

export default router;
