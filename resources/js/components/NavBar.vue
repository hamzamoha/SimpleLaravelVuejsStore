<template>
<nav class="navbar navbar-expand col-8 navbar-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav">
            <li class="nav-item">
                <router-link :to="{ name: 'Home' }" class="nav-link active">Home</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'Products' }" class="nav-link">Products</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'Login' }" class="nav-link" v-show="!this.$store.state.loggedin">Login</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'Register' }" class="nav-link" v-show="!this.$store.state.loggedin">Register</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'Dashboard' }" class="nav-link" v-show="this.$store.state.loggedin">Dashboard</router-link>
            </li>
            <li class="nav-item">
                <button @click="logout" class="nav-link border-0 bg-transparent" v-show="this.$store.state.loggedin">Logout</button>
            </li>
        </ul>
    </div>
</nav>
</template>

<script>
export default {
    methods: {
        logout() {
            fetch('api/logout', {
                    headers: {
                        Accept: 'application/json',
                        Authorization: "Bearer " + this.$store.state.token,
                    },
                    method: 'POST'
                })
                .then(res => res.json())
                .then(res => console.log(res))
                .catch(e => console.log(e));
        }
    }
};
</script>
