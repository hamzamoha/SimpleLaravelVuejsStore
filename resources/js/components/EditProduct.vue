<template>
<div class="position-relative">
    <div v-show="alert" class="position-fixed top-0 start-0 end-0 mx-auto row justify-content-center" style="z-index: 1;">
        <div class="alert alert-danger mx-auto col-md-6 col-sm-10" role="alert">
            {{ alert }}
        </div>
    </div>
    <div class="card card-body w-auto">
        <div class="row justify-content-center" v-if="product">
            <form @submit.prevent="Save" class="col-8" enctype="multipart/form-data" autocomplete="off">
                <h4>Edit</h4>
                <div class="mb-3">
                    <label for="name" class="form-label">Product name</label>
                    <input type="text" class="form-control" id="name" placeholder="e.g. My product" name="name" v-model="product.name" />
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control" aria-label="Product's Price" placeholder="e.g. 18.50" id="price" name="price" v-model="product.price" />
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="3" name="description" placeholder="e.g. This product is the product that I'm selling and I'm trying to describe it" v-model="product.description"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Original image</label>
                    <div class="text-center">
                        <div style="position: relative; display: inline-block;" v-if="product.image &&
    product.image != 'no_image.png'">
                            <img :src="'products/images/' + product.image" alt="" style="max-width:100%; max-height: 200px;" class="img-thumbnail mx-auto d-block" />
                            <button style="position:absolute;top:3px;right:3px;" class="btn btn-danger btn-sm" @click="DeleteImage">
                                Delete
                            </button>
                        </div>
                        <div v-else>
                            No image
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload new image</label>
                    <input class="form-control" type="file" id="formFile" @change="onChange" />
                </div>
                <div class="mb-3" v-if="preview_upload">
                    <div class="text-center">
                        <div style="position: relative; display: inline-block;">
                            <img :src="preview_upload" alt="Preview" style="max-width:100%; max-height: 200px;" class="img-thumbnail mx-auto d-block" />
                            <button style="position:absolute;top:3px;right:3px;" class="btn btn-light btn-sm" @click="clearUpload">
                                X
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                    <router-link :to="{ name: 'Home' }" class="btn btn-light border">Go Home</router-link>
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
            product: null,
            alert: false,
            preview_upload: false
        };
    },
    beforeMount() {
        fetch("api/products/" + this.$route.params.id, {
                headers: {
                    Accept: "application/json"
                }
            })
            .then(res => {
                return res.json();
            })
            .then(res => {
                if (res.id) this.product = res;
                else
                    this.$router.push({
                        name: "Products"
                    });
            });
    },
    methods: {
        Save() {
            let formData = new FormData();
            formData.append("name", this.product.name);
            formData.append("price", this.product.price);
            formData.append("description", this.product.description);
            formData.append("image", this.product.image);
            formData.append("imageToUpload", this.product.imageToUpload);
            formData.append("_method", 'PUT');

            axios.post("api/products/" + this.$route.params.id, formData, {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.token,
                    },
                })
                .then(res => res.data)
                .then(res => {
                    if (res == 1) {
                        this.$emit("update")
                        this.$router.push({ name: 'Products' })
                    } else {
                        if (res.message) {
                            if (res.errors) {
                                this.alert = res.errors.name ?
                                    res.errors.name[0] :
                                    res.errors.price ?
                                    res.errors.price[0] :
                                    res.errors.description ?
                                    res.errors.description[0] :
                                    false;
                            } else this.alert = res.message;
                        } else if (res.error) this.alert = res.error;
                        else {
                            this.alert = "Oops something went wrong !";
                        }
                    }
                })
                .catch(e => {
                    if (e.response && e.response.data) {
                        let res = e.response.data;
                        if (res.message) {
                            if (res.errors) {
                                this.alert = res.errors.name ?
                                    res.errors.name[0] :
                                    res.errors.price ?
                                    res.errors.price[0] :
                                    res.errors.description ?
                                    res.errors.description[0] :
                                    false;
                            } else this.alert = res.message;
                        } else if (res.error) this.alert = res.error;
                        else {
                            this.alert = "Oops something went wrong !";
                        }
                    }
                });
        },
        onChange(e) {
            this.product.imageToUpload = e.target.files[0];
            if (this.product.imageToUpload)
                this.preview_upload = URL.createObjectURL(
                    this.product.imageToUpload
                );
            else this.preview_upload = false;
        },
        clearUpload() {
            document.querySelector("form input[type=file]").value = "";
            this.preview_upload = false;
            this.product.imageToUpload = null;
        },
        DeleteImage(e) {
            this.product.image = null;
        }
    }
};
</script>
