import Vue from "vue";
import App from "./App.vue";
import store from "./store";
import moment from "moment";
import router from "./router";
import BootstrapVue from "bootstrap-vue";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

Vue.use(BootstrapVue);

Vue.config.productionTip = false;

Vue.filter("formatDate", function(value) {
    console.log(value);
    if (value) {
        return moment(String(value)).format("MM/DD/YYYY");
    }
});

new Vue({
    store,
    router,
    render: function(h) {
        return h(App);
    }
}).$mount("#app");
