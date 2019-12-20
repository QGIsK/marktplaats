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
    editAd({ commit }, { ad }) {
        return new Promise((resolve, reject) => {
            // const token = localStorage.getItem("token");
            const token = VueCookies.get("token");

            // console.log(ad);

            axios({
                url: `/api/ads/${ad.id}`,
                data: ad,
                headers: {
                    Authorization: `Bearer ${token}`
                },
                method: "PUT"
            })
                .then(res => {
                    resolve(res);
                })
                .catch(e => {
                    reject(e);
                });

            // axios
            //     .put(`/api/ads/${ad.id}`, ad, {
            //         headers: {
            //             Authorization: `Bearer ${token}`
            //         }
            //     })
            //     .then(res => {
            //         resolve(res);
            //     })
            //     .catch(e => {
            //         reject(e);
            //     });
        });
    },
    deleteAd({ commit }, { ad }) {
        return new Promise((resolve, reject) => {
            // const token = localStorage.getItem("token");
            const token = VueCookies.get("token");

            const url = `/api/ads/${ad.id}`;
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
                .then(async res => {
                    let ads = res.data.data;
                    for (let i = 0; ads.length > i; i++) {
                        let img = ads[i].image;
                        if (img === null) continue;
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
    getCategories({ commit }) {
        axios({
            url: "/api/category",
            method: "GET",
            headers: {
                Authorization: `Bearer ${VueCookies.get("token")}`
            }
        })
            .then(res => {
                commit("storeCategories", { categories: res.data.data });
            })
            .catch(e => console.log(e));
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
                    const token = resp.data.bearer_token;
                    const user = resp.data.user;

                    VueCookies.set("token", token, "24h");

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

                    VueCookies.remove("token");
                    localStorage.removeItem("user");

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
                    const token = resp.data.bearer_token;
                    const user = resp.data.user;

                    VueCookies.set("token", token, "24h");

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
                    VueCookies.remove("token");
                    localStorage.removeItem("user");

                    reject(err);
                });
        });
    },
    logout({ commit }) {
        return new Promise((resolve, reject) => {
            VueCookies.remove("token");
            localStorage.removeItem("user");

            delete axios.defaults.headers.common["Authorization"];
            commit("logout");
            resolve();
        });
    }
};
