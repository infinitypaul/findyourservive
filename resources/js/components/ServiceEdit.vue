<template>
    <div class="card">
        <div class="card-header">
            Edit Service
        </div>
        <div class="card-body bg-light text-center">
            <form @submit.prevent="update">
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            class="form-control"
                            id="inputPassword"
                            v-model="updating.form.title"
                            :class="{ ' is-invalid' : updating.errors['title'] }"
                        >
                        <small v-if="updating.errors['title']" class="form-text text-muted">
                            {{ updating.errors['title'][0]}}
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            class="form-control"
                            id="description"
                            v-model="updating.form.description"
                            :class="{ ' is-invalid' : updating.errors['description'] }"
                        >
                        <small v-if="updating.errors['description']" class="form-text text-muted">
                            {{ updating.errors['description'][0]}}
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Type & Select An Address</label>
                    <div class="col-sm-10">
                        <vue-google-autocomplete
                            id="map"
                            ref="autocomplete"
                            classname="form-control"
                            placeholder="Start typing"
                            v-on:placechanged="getAddressData"
                        >
                        </vue-google-autocomplete>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            readonly
                            class="form-control-plaintext" id="address"
                            v-model="updating.form.address"
                        >
                        <small v-if="updating.errors['address']" class="form-text text-muted">
                            {{ updating.errors['address'][0]}}
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="city" class="col-sm-2 col-form-label">City</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            readonly
                            class="form-control-plaintext" id="city"
                            v-model="updating.form.city"
                        >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="state" class="col-sm-2 col-form-label">State</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            readonly
                            class="form-control-plaintext" id="state"
                            v-model="updating.form.state"
                        >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="zip_code" class="col-sm-2 col-form-label">Zip Code</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            readonly
                            class="form-control-plaintext" id="zip_code"
                            v-model="updating.form.zip_code"
                        >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            readonly
                            class="form-control-plaintext" id="longitude"
                            v-model="updating.form.longitude"
                        >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            readonly
                            class="form-control-plaintext" id="latitude"
                            v-model="updating.form.latitude"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</template>

<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    export default {
        props: ['endpoint', 'id', 'url'],
        name: "ServiceEdit",
        components:{
            VueGoogleAutocomplete
        },
        data(){
            return {
                updating:{
                    form:{
                        city: null,
                        state:null,
                        latitude:null,
                        longitude:null,
                        zip_code:null,
                        address:null
                    },
                    errors: []
                }
            }
        },
        methods:{
            getServiceById(){
                axios.get(`${this.endpoint}/${this.id}/edit`).then((response)=>{
                    this.updating.form = response.data
                })
            },
            getAddressData(addressData, placeResultData, id){
                this.updating.form.city = addressData.country
                this.updating.form.latitude = addressData.latitude
                this.updating.form.longitude = addressData.longitude
                this.updating.form.zip_code = addressData.postal_code
                this.updating.form.state = addressData.administrative_area_level_1
                this.updating.form.address = addressData.street_number+' '+ addressData.route
                this.$refs.autocomplete.clear()
            },
            update(){
                axios.patch(`${this.endpoint}/${this.id}`, this.updating.form).then(()=>{
                    window.location.replace(this.url);
                }).catch((error)=> {
                    this.updating.errors = error.response.data.errors
                })
            },
        },
        mounted() {
            this.getServiceById()
        }
    }
</script>

<style scoped>

</style>
