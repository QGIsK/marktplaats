import Vue from "vue";
import moment from "moment";
import Axios from "axios";
import App from "./App.vue";
import store from "./store";
import router from "./router";
import wysiwyg from "vue-wysiwyg";
import VueCookies from "vue-cookies";
import BootstrapVue from "bootstrap-vue";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

Vue.use(VueCookies);
Vue.use(BootstrapVue);
Vue.use(wysiwyg, {
    hideModules: { image: true }
});

Vue.config.productionTip = false;

Vue.filter("formatDate", function(value) {
    console.log(value);
    if (value) {
        return moment(String(value)).format("MM/DD/YYYY");
    }
});

// const token = localStorage.getItem("token");
const token = VueCookies.get("token");

Vue.prototype.$http = Axios;

Vue.prototype.$http.defaults.baseURL = "http://localhost:8000";
Vue.prototype.$http.defaults.headers.common["Content-Type"] =
    "application/json";

if (token) {
    Vue.prototype.$http.defaults.headers.common[
        "Authorization"
    ] = `Bearer ${token}`;
}

new Vue({
    store,
    router,
    data: {
        interval: null
    },
    methods: {
        loadData: () => {
            store.dispatch("getAds");
        }
    },
    mounted: function() {
        console.log("[LOADING] DATA");
        this.loadData();

        this.interval = setInterval(
            function() {
                console.log("[UPDATING] DATA");
                this.loadData();
            }.bind(this),
            50000
        );
    },
    beforeDestroy: function() {
        clearInterval(this.interval);
    },
    render: function(h) {
        return h(App);
    }
}).$mount("#app");
