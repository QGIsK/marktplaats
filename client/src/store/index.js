/* eslint-disable */
import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

if (!process.env.NODE_ENV || process.env.NODE_ENV == "development") {
    axios.defaults.baseURL = "http://localhost:8000";
}

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        status: "",
        token: localStorage.getItem("token") || "",
        user: JSON.parse(localStorage.getItem("user")) || {}
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
        }
    },
    actions: {
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
        userId: state => state.user.id,
        authStatus: state => state.status
    }
});
