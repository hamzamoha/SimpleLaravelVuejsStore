<template>
<div class="product">
    <div class="card w-100">
        <div class="card-header text-success">{{ product.price }} $</div>
		<img :src="`/products/images/${product.image}`" :alt="product.image" class="card-img-top" height="180px">
        <div class="card-body">
            <div class="card-title">
                <h4 class="text-center">{{ product.name }}</h4>
            </div>
            <router-link :to="{name: 'ShowProduct', params: {id: product.id}}" class="btn btn-primary d-block">View</router-link>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        product: Object,
    },
};
</script>

<style scoped>
.product {
    display: inline-block;
    margin-bottom: 10px;
}
.product img {
    object-fit: cover;
}
.product h4 {
    line-height: 30px;
    height: 30px;
    overflow: hidden;
}
</style>
