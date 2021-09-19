<template>
<div class="product">
    <div class="card w-100">
        <div class="card-header text-center" v-if="this.$store.state.loggedin">
            <button class="btn btn-primary btn-sm" @click="$router.push({name: 'EditProduct', params: { 'id': product.id }})">Edit</button>
            <button class="btn btn-danger btn-sm" @click="$emit('delete-product',product.id)">Delete</button>
        </div>
        <div class="card-header text-success">{{ product.price }} $</div>
		<img :src="`/products/images/${product.image}`" :alt="product.image" class="card-img-top">
        <div class="card-body">
            <div class="card-title">
                <router-link :to='{name: "ShowProduct", params: {"id": product.id}}'><h3>{{ product.name }}</h3></router-link>
            </div>
            <div class="card-text" v-if="product.description">{{ product.description.substring(0, 30) }}</div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        product: Object,
    },
    methods: {
    }
};
</script>

<style scoped>
.product {
    display: inline-block;
    margin-bottom: 10px;
    width: 100%;
}
</style>
