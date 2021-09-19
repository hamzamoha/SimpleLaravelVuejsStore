<template>
<div class="card">
    <div v-if="product">
        <div class="card-header">
            {{ product.created_at_str }}
        </div>
            <img :src="'products/images/' + product.image" alt="" class="rounded img-thumbnail my-2 card-img-top w-auto mx-auto d-block" style="max-height: 300px">
        <div class="card-body">
            <div class="card-title">
                <h3>{{ product.name }}</h3>
            </div>
            <div class="card-text">
                <p>
                    {{ product.description }}
                </p>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            product: null,
        }
    },
    methods: {
        getProduct() {
            fetch('api/products/' + this.$route.params.id, {
                headers: {
                    Accept: 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => this.product = res);
        }
    },
    beforeMount() {
        this.getProduct();
    },
}
</script>
