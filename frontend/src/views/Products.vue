<template>
    <b-row>
        <b-col
            md="2"
            sm="4"
            class="my-1">
            <b-form-group
                class="mb-0"
            >
                <label class="d-inline-block text-sm-left mr-50">Per page</label>
                <b-form-select
                    id="perPageSelect"
                    v-model="perPage"
                    size="sm"
                    :options="pageOptions"
                    class="w-50"
                />

            </b-form-group>
        </b-col>
        <b-col
            md="4"
            sm="8"
            class="my-1"
        >
            <b-button variant="primary" v-b-modal:product-modal>Add new</b-button>
        </b-col>
        <b-col
            md="6"
            class="my-1"
        >
            <b-form-group
                label="Filter"
                label-cols-sm="3"
                label-align-sm="right"
                label-size="sm"
                label-for="filterInput"
                class="mb-0"
            >
                <b-input-group size="sm">
                    <b-form-input
                        id="filterInput"
                        v-model="search"
                        type="search"
                        placeholder="Type to Search"
                    />
                    <b-input-group-append>
                        <b-button
                            :disabled="!search"
                            @click="search = ''">
                            Clear
                        </b-button>
                    </b-input-group-append>
                </b-input-group>
            </b-form-group>
        </b-col>

        <b-col cols="12">
            <b-table
                striped
                hover
                responsive
                :items="products"
                :fields="fields"
            >
                <template #cell(image)="data">
                    <img style="width: 100px" :src="data.value"/>
                </template>

                <template #cell(published)="data">
                    <b-form-checkbox v-model="data.value" disabled/>
                </template>
                <template #cell(in_stock)="data">
                    <b-form-checkbox v-model="data.value" disabled/>
                </template>
                <template #cell(is_service)="data">
                    <b-form-checkbox v-model="data.value" disabled/>
                </template>
                <template #cell(discount)="data">
                    <b-form-checkbox v-model="data.value" disabled/>
                </template>
                <template #cell(created_at)="data">
                    {{ moment(data.value).format('lll') }}
                </template>
                <template #cell(actions)="data">
                    <b-dropdown
                        variant="link"
                        no-caret
                        right>

                        <template #button-content>
                            <feather-icon
                                icon="MoreVerticalIcon"
                                size="16"
                                class="align-middle text-body"
                            />
                        </template>
                        <b-dropdown-item @click="detail(data.item)">
                            <feather-icon icon="FileTextIcon"/>
                            <span class="align-middle ml-50">Details</span>
                        </b-dropdown-item>

                        <b-dropdown-item @click="edit(data.item)">
                            <feather-icon icon="EditIcon"/>
                            <span class="align-middle ml-50">Edit</span>
                        </b-dropdown-item>

                        <b-dropdown-item @click="remove(data.item.id)">
                            <feather-icon icon="TrashIcon"/>
                            <span class="align-middle ml-50">Delete</span>
                        </b-dropdown-item>
                    </b-dropdown>
                </template>
            </b-table>
        </b-col>
        <product-modal ref="productForm" v-on:refresh="loadData"/>
        <b-modal ref="productModal" hide-footer :title="'Product Details: #'+product.id">
            <table class="table" v-if="product.id">
                <tbody>
                <tr>
                    <th>Image</th>
                    <td><img :src="product.image" style="width: 50%" alt=""></td>
                </tr>
                <tr>
                    <th>Id</th>
                    <td>{{ product.id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ product.name }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td v-html="product.description"></td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>{{ product.price }}</td>
                </tr>
                <tr>
                    <th>Is Service</th>
                    <td>
                        <b-form-checkbox disabled v-model="product.is_service"/>
                    </td>
                </tr>
                <tr>
                    <th>Discount</th>
                    <td>
                        <b-form-checkbox disabled v-model="product.discount"/>
                    </td>
                </tr>
                <tr>
                    <th>Published</th>
                    <td>
                        <b-form-checkbox disabled v-model="product.published"/>
                    </td>
                </tr>
                <tr>
                    <th>In Stock</th>
                    <td>
                        <b-form-checkbox disabled v-model="product.in_stock"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </b-modal>
        <b-col
            cols="12"
        >
            <b-pagination
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
                align="center"
                size="sm"
                class="my-0"
            />
        </b-col>
    </b-row>
</template>

<script>
import {
    BTable,
    BAvatar,
    BBadge,
    BRow,
    BCol,
    BFormGroup,
    BFormSelect,
    BPagination,
    BInputGroup,
    BFormInput,
    BInputGroupAppend,
    BButton,
    BFormCheckbox,
    BDropdown,
    BDropdownItem,
} from 'bootstrap-vue'
import ProductModal from "@/views/ProductModal";
import moment from 'moment'
import _ from "lodash";

export default {
    components: {
        ProductModal,
        BTable,
        BAvatar,
        BBadge,
        BRow,
        BCol,
        BFormGroup,
        BFormSelect,
        BPagination,
        BInputGroup,
        BFormInput,
        BInputGroupAppend,
        BButton,
        BFormCheckbox,
        BDropdown,
        BDropdownItem
    },
    data() {
        return {
            perPage: 3,
            pageOptions: [3, 10, 30, 50],
            totalRows: 1,
            currentPage: 1,
            search: null,
            product: {},
            fields: [
                {
                    key: 'id', label: 'Id',
                },
                {
                    key: 'image', label: 'Image',
                },
                {key: 'name', label: 'Name', sortable: true},
                'price',
                'in_stock',
                'published',
                'discount',
                'is_service',
                {key: 'created_at', label: 'Created At'},
                'actions'
            ],
            products: []
        }
    },
    computed: {},
    mounted() {
        this.loadData()
    },
    watch: {
        search: _.debounce(function (newVal) {
            // ne pas faire la recherche si le champ ne contient pas au moins 2 caractères et n'est pas vide
            if (newVal.length > 0 && newVal.length < 2) return;
            this.loadData();
        }, 300),
        perPage() {
            this.loadData()
        },
        currentPage() {
            this.loadData()
        }
    },
    methods: {
        moment,
        info(item, index, button) {
            this.infoModal.title = `Row index: ${index}`
            this.infoModal.content = JSON.stringify(item, null, 2)
            this.$root.$emit('bv::show::modal', this.infoModal.id, button)
        },
        resetInfoModal() {
            this.infoModal.title = ''
            this.infoModal.content = ''
        },
        loadData() {
            // this.$loading(true);
            this.$http
                .get("product", {
                    params: {
                        size: this.perPage,
                        page: this.currentPage,
                        search: this.search
                    }
                })
                .then(({data}) => {
                    this.products = data.data;
                    const meta = data.meta;
                    this.totalRows = meta.total;
                })
                .finally(() => {
                    // this.$loading(false)
                });
        },
        remove(id) {
            this.$http.delete('product/' + id)
                .then(() => this.loadData())
        },
        edit(item) {
            this.$refs.productForm.edit(item)
        },
        detail(item) {
            this.product = item
            this.$refs.productModal.show()
        }
    },
}
</script>
