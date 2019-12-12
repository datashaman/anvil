import Vue from "vue";
import Base from "./base";
import axios from "axios";
import Routes from "./routes";
import VueRouter from "vue-router";
import VueJsonPretty from "vue-json-pretty";
import moment from "moment-timezone";
import Echo from "laravel-echo";

require("bootstrap");

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}

Vue.use(VueRouter);

window.Popper = require("popper.js").default;

moment.tz.setDefault(window.Anvil.timezone);

window.Anvil.basePath = "/" + window.Anvil.path;

let routerBasePath = window.Anvil.basePath + "/";

if (window.Anvil.path === "" || window.Anvil.path === "/") {
    routerBasePath = "/";
    window.Anvil.basePath = "";
}

const router = new VueRouter({
    routes: Routes,
    mode: "history",
    base: routerBasePath
});

Vue.component("vue-json-pretty", VueJsonPretty);
Vue.component("index-screen", require("./components/IndexScreen.vue").default);
Vue.component(
    "preview-screen",
    require("./components/PreviewScreen.vue").default
);
Vue.component("alert", require("./components/Alert.vue").default);

Vue.mixin(Base);

new Vue({
    el: "#anvil",

    router,

    data() {
        return {
            alert: {
                type: null,
                autoClose: 0,
                message: "",
                confirmationProceed: null,
                confirmationCancel: null
            }
        };
    }
});

console.log("here");

window.io = require("socket.io-client");

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ":6001"
});
