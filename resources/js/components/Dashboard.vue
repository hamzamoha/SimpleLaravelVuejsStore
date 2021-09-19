<template>
<div>
    <h2 class="text-center py-4">Dashboard</h2>
    <div class="text-center my-2">
        <router-link class='btn btn-outline-primary' :to='{name: "NewProduct"}'>+ Add new product to your store</router-link>
    </div>
    <ProductsTable></ProductsTable>
</div>
</template>

<style scoped>


</style>

<script>
import ProductsTable from './ProductsTable'
export default {
    components: {
        ProductsTable
    }
}
</script>