<template>
<div class="">
    <div class="alert alert-danger" role="alert" v-show="alert">
        {{alert}}
    </div>
    <div class="card card-body w-auto">
        <div class="row justify-content-center">
            <form @submit.prevent="addProduct" class="col-8" enctype="multipart/form-data" autocomplete="off">
                <h4>Add New Product</h4>
                <div class="mb-3">
                    <label for="name" class="form-label">Product name</label>
                    <input type="text" class="form-control" id="name" placeholder="e.g. My product" name="name" v-model="product_name">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control" aria-label="Product's Price" placeholder="e.g. 18.50" id="price" name="price" v-model="product_price">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="3" name="description" placeholder="e.g. This product is the product that I'm selling and I'm trying to describe it" v-model="product_description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Image</label>
                    <input class="form-control" type="file" id="formFile" @change="onChange">
                </div>
                <div class="mb-3 text-center" v-if="preview_upload">
                    <div style="position: relative; display: inline-block;">
                        <img :src="preview_upload" alt="Preview" style="max-width:100%; max-height: 200px;" class="img-thumbnail mx-auto d-block">
                        <button style="position:absolute;top:3px;right:3px;" class="btn btn-light btn-sm" @click="clearUpload">X</button>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <router-link :to='{name: "Home"}' class="btn btn-light border">Go Home</router-link>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            product_name: '',
            product_price: '',
            product_description: '',
            product_image: null,
            preview_upload: false,
            alert: false,
        }
    },
    methods: {
        onChange(e) {
            this.product_image = e.target.files[0];
            if (this.product_image) this.preview_upload = URL.createObjectURL(this.product_image)
            else this.preview_upload = false
        },
        clearUpload() {
            document.querySelector('form input[type=file]').value = ''
            this.preview_upload = false
            this.product_image = null
        },
        addProduct() {
            let formData = new FormData();
            formData.append('name', this.product_name);
            formData.append('price', this.product_price);
            formData.append('description', this.product_description);
            formData.append('image', this.product_image);
            axios.post('api/products', formData, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: "Bearer " + this.$store.state.token,
                    },
                }).then(res => {
                    console.log(res)
                    if (res.data) {
                        return res.data
                    } else return res
                })
                .then(res => {
                    if (res.status && res.status == 200) {
                        this.$emit("add-product", res.product);
                    } else {
                        if (res.message) {
                            if (res.errors) {
                                this.alert = (res.errors.name) ? res.errors.name[0] : (res.errors.price) ? res.errors.price[0] : (res.errors.description) ? res.errors.description[0] : false;
                            } else this.alert = res.message
                        } else if (res.error) this.alert = res.error;
                        else {
                            this.alert = "Oops something went wrong !"
                        }
                    }
                }).catch(e => {
                    if (e.response && e.response.data) {
                        let res = e.response.data
                        if (res.message) {
                            if (res.errors) {
                                this.alert = (res.errors.name) ? res.errors.name[0] : (res.errors.price) ? res.errors.price[0] : (res.errors.description) ? res.errors.description[0] : false;
                            } else this.alert = res.message
                        } else if (res.error) this.alert = res.error;
                        else {
                            this.alert = "Oops something went wrong !"
                        }
                    }
                });
        }
    },
};
</script>
