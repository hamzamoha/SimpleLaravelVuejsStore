<template>
<div class="container-md my-2">
    <NavBar></NavBar>
    <router-view name="EditProduct" @update='productspage=1; fetchProducts(); fetchLatestProducts()'></router-view>
    <router-view name="Products" :products='products' @load-more="loadmore" @delete-product='DeleteProduct' :ismore="ismore"></router-view>
    <router-view name="NewProduct" @add-product='addProduct'></router-view>
    <router-view name="Home" :latestProducts='latestProducts'></router-view>
    <router-view></router-view>
</div>
</template>

<script>
import NavBar from "./NavBar";
export default {
    data() {
        return {
            products: [],
            latestProducts: [],
            productspage: 1,
            ismore: false,
        };
    },
    methods: {
        loadmore() {
            this.productspage++
            this.fetchProducts()
        },
        fetchProducts() {
            fetch("/api/products/?page=" + this.productspage)
                .then(res => res.json())
                .then(res => {
                    if (res.last_page > this.productspage) this.ismore = true
                    else if (res.last_page == this.productspage) this.ismore = false
                    else {
                        this.productspage = res.last_page
                        return
                    }
                    this.products = [...this.products, ...res.data];
                })
                .catch(e => console.log(e));
        },
        fetchLatestProducts() {
            fetch("/api/products/latest/4")
                .then((res) => res.json())
                .then(res => {
                    this.latestProducts = [...res]
                })
                .catch(e => console.log(e));
        },
        addProduct(product) {
            this.products.push(product)
            this.$router.push({
                name: 'Home'
            })
        },
        DeleteProduct(id) {
            fetch(`api/products/${id}`, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: "Bearer " + this.$store.state.token,
                    },
                    method: 'DELETE'
                }).then(res => res.json())
                .then(res => {
                    this.products = this.products.filter(item => item.id !== res.id);
                })
                .catch(e => console.log(e));
        },
    },
    created() {
        this.fetchProducts();
        if (this.$route.name == 'Home') this.fetchLatestProducts();
    },
    components: {
        NavBar,
    },
    computed: {
        getRouteName() {
            return this.$route.name;
        }
    },
};
</script>

<style>
    
</style>
