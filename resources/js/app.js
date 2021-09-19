require("./bootstrap");

window.Vue = require("vue").default;

import App from "./components/App";
import Home from "./components/Home";
import Dashboard from "./components/Dashboard";
import NewProduct from "./components/NewProduct";
import EditProduct from "./components/EditProduct";
import ShowProduct from "./components/ShowProduct";
import Login from "./components/Login";
import Register from "./components/Register";
import Products from "./components/Products";

import VueRouter from "vue-router";

import Vuex from "vuex";

Vue.use(VueRouter).use(Vuex);

const routes = [{
        path: "/products",
        name: "Products",
        component: Products,
        components: { Products: Products }
    }, {
        path: "/products/show/:id",
        name: "ShowProduct",
        component: ShowProduct,
    },
    {
        path: "/",
        name: "Home",
        component: Home,
        components: { Home: Home }
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        beforeEnter: (to, from, next) => {
            if (!store.state.loggedin) {
                router.push({
                    name: "Login"
                });
            } else {
                next();
                return;
            }
        },
    },
    {
        path: "/dashboard/products/new",
        name: "NewProduct",
        component: NewProduct,
        components: { NewProduct: NewProduct },
        beforeEnter: (to, from, next) => {
            if (!store.state.loggedin) {
                router.push({
                    name: "Login"
                });
            } else {
                next();
                return;
            }
        },
    },
    {
        path: "/dashboard/products/edit/:id",
        name: "EditProduct",
        component: EditProduct,
        components: { EditProduct: EditProduct },
        beforeEnter: (to, from, next) => {
            if (!store.state.loggedin) {
                router.push({
                    name: "Login"
                });
            } else {
                next();
                return;
            }
        },
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        beforeEnter: (to, from, next) => {
            if (store.state.loggedin) {
                router.push({
                    name: "Home"
                });
            } else {
                next();
                return;
            }
        }
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        beforeEnter: (to, from, next) => {
            if (store.state.loggedin) {
                router.push({
                    name: "Home"
                });
            } else {
                next();
                return;
            }
        }
    }
];

const router = new VueRouter({
    routes
});

const store = new Vuex.Store({
    state: {
        loggedin: Boolean,
        token: String
    },
    mutations: {
        login(state, token) {
            state.loggedin = true;
            state.token = token;
            localStorage.setItem("token", token);
        }
    }
});

const app = new Vue({
    el: "#app",

    components: {
        App
    },
    router,
    store,
    beforeCreate() {
        if (!localStorage.getItem("token") ||
            localStorage.getItem("token") == "no-token"
        ) {
            store.state.loggedin = false;
            store.state.token = "no-token";
        } else {
            fetch("api/check_token/", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        Authorization: "Bearer " + localStorage.getItem("token")
                    }
                })
                .then(res => {
                    if (res.status !== 200) {
                        localStorage.setItem("token", "no-token");
                        store.state.loggedin = false;
                        store.state.token = "no-token";
                        this.$router.push({ name: "Home" })
                    } else {
                        store.state.loggedin = true;
                        store.state.token = localStorage.getItem("token");
                    }
                })
                .catch(e => console.log(e));
        }
    }
});