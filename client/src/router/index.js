import Vue from "vue";
import Router from "vue-router";
import Home from "@/components/pages/Home";
import Register from "@/components/pages/auth/Register";
import Login from "@/components/pages/auth/Login";
import ShowAd from "@/components/pages/ads/ShowAd";

Vue.use(Router);

export default new Router({
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
            path: "/ad/:id",
            name: "ShowAd",
            component: ShowAd
        }
    ]
});
