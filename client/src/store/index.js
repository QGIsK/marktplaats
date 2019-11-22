/* eslint-disable */
import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

if (!process.env.NODE_ENV || process.env.NODE_ENV == "development") {
    axios.defaults.baseURL = "http://localhost:8000";
}

const token = localStorage.getItem("token");

if (token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        status: "",
        token: localStorage.getItem("token") || "",
        user: JSON.parse(localStorage.getItem("user")) || {},
        ads: []
    },
    mutations: {
        auth_request(state) {
            state.status = "loading";
        },
        auth_success(state, { token, user }) {
            state.status = "success";
            state.token = token;
            state.user = user;
        },
        auth_error(state) {
            state.status = "error";
        },
        logout(state) {
            state.status = "";
            state.token = "";
            state.user = {};
        },
        storeAds(state, { ads }) {
            state.ads = ads;
        }
    },
    actions: {
        createAd({ commit }, { post }) {
            // axios.post("/api/ads", post).then(res => {
            //     console.log(res.data);
            // });
            // return;
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/ads`, post, {
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
        getAds({ commit }) {
            return new Promise((resolve, reject) => {
                axios({
                    url: "/api/ads",
                    method: "GET"
                })
                    .then(res => {
                        commit("storeAds", { ads: res.data.data });
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

                        localStorage.setItem("token", token);
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
                        localStorage.setItem("token", token);
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
                localStorage.removeItem("token");
                localStorage.removeItem("user");

                delete axios.defaults.headers.common["Authorization"];
                commit("logout");
                resolve();
            });
        }
    },
    getters: {
        isLoggedIn: state => !!state.token,
        user: state => state.user,
        userId: state => state.user.id,
        authStatus: state => state.status,
        ads: state => state.ads
    }
});
