<template>
<div class="">
    <h2 class="text-center py-4">Products</h2>
    <div class="products">
        <Product v-for="product of products" :key="product.id" :product="product" @delete-product='$emit("delete-product", $event)'></Product>
    </div>
    <div class="text-center" v-if="ismore">
        <button class="btn bg-white border text-info" @click="$emit('load-more')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6cb2eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 13l5 5 5-5M7 6l5 5 5-5" />
            </svg> Load More
        </button>
    </div>
</div>
</template>

<script>
import Product from "./Product.vue";
export default {
    props: {
        products: Array,
        ismore: Boolean,
    },
    components: {
        Product
    }
};
</script>

<style scoped>
.products {
    columns: 1;
    margin: 0 auto;
}

@media (min-width: 576px) {
    .products {
        columns: 2;
    }
}

@media (min-width: 768px) {
    .products {
        columns: 3;
    }
}

@media (min-width: 992px) {
    .products {
        columns: 4;
    }
}

/* @media (min-width: 1200px) {
    .products {
        columns: 5;
    }
} */
</style>
