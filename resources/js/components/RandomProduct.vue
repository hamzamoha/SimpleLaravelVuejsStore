<template>
<div class="card mb-3" v-if="product">
    <div class="row g-0">
        <div class="col-12">
            <div class="card-header">Random Product</div>
        </div>
        <div class="col-md-5 col-lg-4 col-sm-6">
            <img :src="'products/images/'+product.image" class="img-fluid rounded-start w-100" :alt="product.name" style="max-height:300px;object-fit:cover;">
        </div>
        <div class="col-md-7 col-lg-8 col-sm-6">
            <div class="card-body">
                <h5 class="card-title">{{ product.name }}</h5>
                <p class="card-text">{{ product.description }}</p>
                <p class="card-text"><small class="text-muted">Last updated {{ product.updated_at_str }}</small></p>
            <router-link :to="{name: 'ShowProduct', params: {id: product.id}}" class="btn border d-block" style="background: #4ba;">View</router-link>
            </div>
        </div>
    </div>
</div>
</template>


<script>
export default {
    data() {
        return {
            product: null
        }
    },
    beforeMount() {
        fetch('api/products/random', {
            headers: {
                Accept: 'application/json',
            }
        }).then(res => res.json())
        .then(res => this.product = res)
        .catch(e => console.log(e));
    },
}
</script>
