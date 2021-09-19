<template>
    <div>
        <div class="display-1 py-4">
            Home
        </div>
        <latest-products :latestProducts="latestProducts"></latest-products>
        <random-product></random-product>
    </div>
</template>

<script>
import LatestProducts from './LatestProducts'
import RandomProduct from './RandomProduct'

export default {
    props: {
        latestProducts: Array,
    },
    components: {
        LatestProducts,
        RandomProduct
    },
}
</script>

