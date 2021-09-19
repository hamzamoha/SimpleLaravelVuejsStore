<template>
<div class="card mb-3">
    <div class="card-header">
        Latest updates
    </div>
    <div class="card-body row justify-content-center">
        <Product v-for="product in latestProducts" :key="product.id" :product='product' class="col-md-4 col-lg-3 col-sm-6"></Product>
    </div>
</div>
</template>

<script>
import Product from './HomeProduct'

export default {
    props: {
        latestProducts: Array,

    },
    components: {
        Product
    },
}
</script>
<style scoped>
.product .card-img-top {
    width: 100%;
    height: 140px;
    object-fit: cover;
}
</style>