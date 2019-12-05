import axios from "axios";
import VueCookies from "vue-cookies";

/* eslint-disable*/

if (!process.env.NODE_ENV || process.env.NODE_ENV == "development") {
    axios.defaults.baseURL = "http://localhost:8000";
}

export default {
    createAd({ commit }, { ad }) {
        return new Promise((resolve, reject) => {
            // const token = localStorage.getItem("token");
            const token = VueCookies.get("token");

            axios
                .post(`/api/ads`, ad, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                .then(res => {
                    resolve(res);
                })
                .catch(e => {
                    reject(e);
                });
        });
    },
    deleteAd({ commit }, { ad }) {
        return new Promise((resolve, reject) => {
            // const token = localStorage.getItem("token");
            const token = VueCookies.get("token");

            const url = `/api/ads/${ad.id}`;
            console.log(url);
            axios
                .delete(url, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                .then(res => resolve(res))
                .catch(err => reject(err));
        });
    },
    getAds({ commit }) {
        return new Promise((resolve, reject) => {
            axios({
                url: "/api/ads",
                method: "GET"
            })
                .then(res => {
                    let ads = res.data.data;
                    for (let i = 0; ads.length > i; i++) {
                        let img = ads[i].image;
                        img = img.replace('"', "").replace('"', "");
                        img = img.split(",");
                        ads[i].image = img;
                    }
                    commit("storeAds", { ads: ads });
                    resolve(res);
                })
                .catch(e => {
                    reject(e);
                });
        });
    },
    login({ commit }, user) {
        return new Promise((resolve, reject) => {
            commit("auth_request");
            axios({
                url: "/api/auth/login",
                data: {
                    email: user.email,
                    password: user.password
                },
                crossdomain: true,
                method: "POST"
            })
                .then(resp => {
                    const token = resp.data.access_token;
                    const user = resp.data.user;

                    VueCookies.set("token", token, "24h");
                    VueCookies.set("user", user, "24h");

                    // localStorage.setItem("token", token);
                    localStorage.setItem("user", JSON.stringify(user));
                    axios.defaults.headers.common["Authorization"] = token;

                    commit("auth_success", {
                        token,
                        user
                    });

                    resolve(resp);
                })
                .catch(err => {
                    commit("auth_error", err);
                    localStorage.removeItem("token");
                    reject(err);
                });
        });
    },
    register({ commit }, user) {
        return new Promise((resolve, reject) => {
            commit("auth_request");
            axios({
                url: "/api/auth/register",
                crossdomain: true,
                data: user,
                method: "POST"
            })
                .then(resp => {
                    const token = resp.data.access_token;
                    const user = resp.data.user;

                    VueCookies.set("token", token, "24h");
                    VueCookies.set("user", user, "24h");
                    // localStorage.setItem("token", token);
                    localStorage.setItem("user", JSON.stringify(user));

                    axios.defaults.headers.common["Authorization"] = token;
                    commit("auth_success", {
                        token,
                        user
                    });
                    resolve(resp);
                })
                .catch(err => {
                    commit("auth_error", err);
                    localStorage.removeItem("user");
                    localStorage.removeItem("token");
                    reject(err);
                });
        });
    },
    logout({ commit }) {
        return new Promise((resolve, reject) => {
            VueCookies.set("token", token, "24h");
            VueCookies.set("user", user, "24h");

            // localStorage.removeItem("token");
            localStorage.removeItem("user");

            delete axios.defaults.headers.common["Authorization"];
            commit("logout");
            resolve();
        });
    }
};
