<template>
    <div class="container-md my-2">
        <div class="col-lg-6 col-md-8 col-sm-10 mx-auto">
            <div class="alert alert-danger" role="alert" v-show="alert">
                {{alert}}
            </div>
            <form class="card card-body" @submit.prevent="login" autocomplete="off">
                <h3 class="text-center py-3">
                    Login Form
                </h3>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" v-model="email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="********" v-model="password">
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-success">Login</button>
                    <router-link to="/" class="btn btn-light border">Go Home</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            alert: false
        }
    },
    props: {
    },
    methods: {
        login(){
            fetch('/api/login', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    'Accept': 'application/json',
                },
                body : JSON.stringify({
                    email: this.email,
                    password: this.password,
                })
            })
            .then(res => res.json())
            .then(res => {
                if(res.token){
                    this.$store.commit('login', res.token)
                    this.$router.push({name: 'Home'})
                }
                else {
                    // some kind of error
                    if(res.message) {
                        if(res.errors){
                            this.alert = (res.errors.email)? res.errors.email[0] : res.errors.password[0];
                        }
                        else this.alert = res.message
                    }
                    else if(res.error) this.alert = res.error;
                    else this.alert = "Oops something went wrong !";
                }
            });
        }
    },
};
</script>
