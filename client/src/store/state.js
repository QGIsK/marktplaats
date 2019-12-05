import VueCookies from "vue-cookies";

/* eslint-disable*/

export default {
    status: "",
    token: `Bearer ${VueCookies.get("token")}` || "",
    user: JSON.parse(localStorage.getItem("user")) || {},
    ads: []
};
