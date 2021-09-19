<template>
<div>
    <table class="table table-striped table-bordered text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Thumbnail</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products" :key="product.id">
                <td>{{ product.id }}</td>
                <td><img :src="'products/images/' + product.image" :alt="product.name" height="30px"></td>
                <td>{{ product.name }}</td>
                <td>
                    <button class="btn btn-danger btn-sm">Delete</button>
                    <router-link :to="{name: 'EditProduct', params: {id: product.id}}" class="btn btn-primary btn-sm">Edit</router-link>
                </td>
            </tr>
            <tr v-if="more">
                <td colspan="4">
                    <button @click="getProducts()" class="btn btn-dark">More</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    data() {
        return {
            products: [],
            more: false,
            current_page: 1,
        }
    },
    methods: {
        getProducts() {
            this.more = false
            fetch('api/products/?page='+ this.current_page++, {
                    headers: {
                        Accept: 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    console.log(this.current_page, res)
                    if(res.current_page < res.last_page) this.more = true
                    else res.more = false
                    this.products = [...this.products, ...res.data]
                });
        }
    },
    beforeMount() {
        this.getProducts();
    },
}
</script>
