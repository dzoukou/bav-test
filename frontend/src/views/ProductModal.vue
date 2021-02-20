<template>
    <b-modal id="product-modal" ref="modal" size="lg" hide-footer @hidden="reset">
        <validation-observer
            ref="form"
            #default="{invalid}">
            <b-form @submit.prevent="submit">
                <b-row>
                    <b-col cols="12">
                        <b-form-group
                            label="Image"
                            label-for="image"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="image"
                                rules="required">
                                <div class="text-center mb-2" v-if="product.image">
                                    <img style="width: 50%" :src="product.image"/>
                                </div>
                                <input type="text" hidden v-model="product.image">
                                <input type="file" class="hidden" ref="uploadImgInput" @change="updateCurrImg"
                                       accept="image/*">
                                <b-button variant="primary" @click="$refs.uploadImgInput.click()">
                                    {{ product.image ? 'Change image' : 'Upload Image' }}
                                </b-button>
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>
                    <b-col cols="12">
                        <b-form-group
                            label="First Name"
                            label-for="h-first-name"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="name"
                                rules="required">
                                <b-form-input
                                    id="name"
                                    v-model="product.name"
                                    :state="errors.length > 0 ? false:null"
                                    placeholder="Name"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>
                    <b-col cols="12">
                        <b-form-group
                            label="Description"
                            label-for="description"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="Description"
                                rules="required">
                                <b-form-textarea
                                    id="description"
                                    v-model="product.description"
                                    :state="errors.length > 0 ? false:null"
                                    placeholder="Name"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>
                    <b-col cols="12">
                        <b-form-group
                            label="Price"
                            label-for="price"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="Price"
                                rules="required">
                                <b-form-input
                                    id="price"
                                    type="number"
                                    min="1"
                                    v-model="product.price"
                                    :state="errors.length > 0 ? false:null"
                                    placeholder="Price"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>

                    <b-col cols="12">
                        <b-form-group
                            label="Service"
                            label-for="is_service"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="IsService"
                                rules="required">
                                <b-form-checkbox
                                    id="is_service"
                                    v-model="product.is_service"
                                    :state="errors.length > 0 ? false:null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>
                    <b-col cols="12">
                        <b-form-group
                            label="In Stock"
                            label-for="in_stock"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="InStock"
                                rules="required">
                                <b-form-checkbox
                                    id="in_stock"
                                    v-model="product.in_stock"
                                    :state="errors.length > 0 ? false:null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>
                    <b-col cols="12">
                        <b-form-group
                            label="Published"
                            label-for="published"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="Published"
                                rules="required">
                                <b-form-checkbox
                                    id="published"
                                    v-model="product.published"
                                    :state="errors.length > 0 ? false:null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>
                    <b-col cols="12">
                        <b-form-group
                            label="Discount"
                            label-for="discount"
                            label-cols-md="4">
                            <validation-provider
                                #default="{ errors }"
                                name="Discount"
                                rules="required">
                                <b-form-checkbox
                                    id="discount"
                                    v-model="product.discount"
                                    :state="errors.length > 0 ? false:null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                        </b-form-group>
                    </b-col>
                    <b-col cols="12" class="text-right">
                        <b-button
                            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                            type="reset" @click="reset"
                            variant="outline-secondary">
                            Reset
                        </b-button>
                        <b-button
                            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                            type="submit"
                            variant="primary"
                            class="mr-1">
                            Submit
                        </b-button>

                    </b-col>
                </b-row>

            </b-form>
        </validation-observer>
    </b-modal>
</template>

<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate'
import {required, email} from '@validations'
import {
    BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormTextarea,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from "@core/components/toastification/ToastificationContent";

export default {
    components: {
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        BFormCheckbox,
        BForm,
        BButton,
        BFormTextarea,
        ValidationObserver, ValidationProvider
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            product: {
                id: null,
                name: '',
                description: '',
                image: '',
                price: '',
                is_service: false,
                in_stock: false,
                published: false,
                discount: false
            },
            file: null,
            // validation
            required,
            email,
        }
    },
    methods: {
        edit(item) {
            this.product.id = item.id
            this.product.name = item.name
            this.product.description = item.description
            this.product.price = item.price
            this.product.image = item.image
            this.product.in_stock = item.in_stock
            this.product.published = item.published
            this.product.is_service = item.is_service
            this.product.discount = item.discount
            this.$refs.modal.show()
        },
        reset() {
            this.product.id = null
            this.product.name = ''
            this.product.description = ''
            this.product.price = ''
            this.product.image = ''
            this.file = null
            this.product.in_stock = false
            this.product.published = false
            this.product.is_service = false
            this.product.discount = false
        },
        submit() {
            this.$refs.form.validate().then(result => {
                if (result) {
                    // this.loading(true)
                    const data = new FormData()
                    data.append('name', this.product.name)
                    data.append('description', this.product.description)
                    data.append('is_service', this.product.is_service ? 1 : 0)
                    data.append('in_stock', this.product.in_stock ? 1 : 0)
                    data.append('discount', this.product.discount ? 1 : 0)
                    data.append('published', this.product.published ? 1 : 0)
                    data.append('price', this.product.price)
                    if (this.file) {
                        data.append('image', this.file)
                    }

                    if (this.product.id) {
                        data.append('_method', 'patch')
                        this.$http.post('product/' + this.product.id, data)
                            .then(() => {
                                this.$emit('refresh')
                                this.$toast({
                                    component: ToastificationContent,
                                    position: 'top-right',
                                    props: {
                                        title: `Product updated`,
                                        icon: 'CoffeeIcon',
                                        variant: 'success',
                                        text: `Your product has been successfully updated`,
                                    },
                                })
                                this.$refs.modal.hide()
                            })
                            .catch(error => {
                                console.log(error);
                                this.$refs.form.setErrors(error.response.data.errors)
                            })
                        // .finally(() => this.loading(false))
                    } else {
                        this.$http.post('product', data)
                            .then(() => {
                                this.$refs.modal.hide()
                                this.$emit('refresh')
                                this.$toast({
                                    component: ToastificationContent,
                                    position: 'top-right',
                                    props: {
                                        title: `Product created`,
                                        icon: 'CoffeeIcon',
                                        variant: 'success',
                                        text: `Your product has been successfully created`,
                                    },
                                })
                            })
                            .catch(error => {
                                console.log(error);
                                this.$refs.form.setErrors(error.response.data.errors)
                            })
                        // .finally(() => this.loading(false))
                    }

                }
            })
        },
        updateCurrImg(input) {
            if (input.target.files && input.target.files[0]) {
                const reader = new FileReader()
                reader.onload = e => {
                    this.product.image = e.target.result
                }
                reader.readAsDataURL(input.target.files[0])
                this.file = input.target.files[0]
            }
        }
    }
}
</script>
