<template>
    <div class="card card-body bg-light text-center" >
        <form action="#" @submit.prevent="saveRecord">
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Title</label>
                <div class="col-sm-6" >
                    <input type="text"
                           class="form-control"
                           id="title"
                           v-model="creating.form.title"

                           :class="{ ' is-invalid' : creating.errors['title'] }"
                    >
                    <small id="emailHelp" v-if="creating.errors['title']" class="form-text text-muted">
                        {{ creating.errors['title'][0]}}
                    </small>
                </div>

            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-6" >
                    <input type="text"
                           class="form-control"
                           id="description"
                           v-model="creating.form.description"

                           :class="{ ' is-invalid' : creating.errors['description'] }"
                    >
                    <small v-if="creating.errors['description']" class="form-text text-muted">
                        {{ creating.errors['description'][0]}}
                    </small>
                </div>

            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-6" >
                    <vue-google-autocomplete
                        id="map"
                        ref="autocomplete"
                        classname="form-control"
                        placeholder="Start typing"
                        v-on:placechanged="getAddressData"
                        :class="{ ' is-invalid' : creating.errors['address'] }"
                        v-on:inputChange="checking"
                    >
                    </vue-google-autocomplete>
                    <small v-if="creating.errors['address']" class="form-text text-muted">
                        {{ creating.errors['address'][0]}}
                    </small>
                </div>

            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">City</label>
                <div class="col-sm-6" >
                    <input type="text"
                           class="form-control"
                           id="city"
                           readonly
                           v-model="creating.form.city"

                           :class="{ ' is-invalid' : creating.errors['city'] }"
                    >
                    <small v-if="creating.errors['city']" class="form-text text-muted">
                        {{ creating.errors['city'][0]}}
                    </small>
                </div>

            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">State</label>
                <div class="col-sm-6" >
                    <input type="text"
                           class="form-control"
                           id="state"
                           readonly
                           v-model="creating.form.state"

                           :class="{ ' is-invalid' : creating.errors['state'] }"
                    >
                    <small v-if="creating.errors['state']" class="form-text text-muted">
                        {{ creating.errors['state'][0]}}
                    </small>
                </div>

            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Zip Code</label>
                <div class="col-sm-6" >
                    <input type="text"
                           class="form-control"
                           id="zip_code"
                           readonly
                           v-model="creating.form.zip_code"

                           :class="{ ' is-invalid' : creating.errors['zip_code'] }"
                    >
                    <small v-if="creating.errors['zip_code']" class="form-text text-muted">
                        {{ creating.errors['zip_code'][0]}}
                    </small>
                </div>

            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Latitude</label>
                <div class="col-sm-6" >
                    <input type="text"
                           class="form-control"
                           id="latitude"
                           readonly
                           v-model="creating.form.latitude"

                           :class="{ ' is-invalid' : creating.errors['latitude'] }"
                    >
                    <small v-if="creating.errors['latitude']" class="form-text text-muted">
                        {{ creating.errors['latitude'][0]}}
                    </small>
                </div>

            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Longitude</label>
                <div class="col-sm-6" >
                    <input type="text"
                           class="form-control"
                           id="longitude"
                           readonly
                           v-model="creating.form.longitude"

                           :class="{ ' is-invalid' : creating.errors['longitude'] }"
                    >
                    <small v-if="creating.errors['longitude']" class="form-text text-muted">
                        {{ creating.errors['longitude'][0]}}
                    </small>
                </div>

            </div>


            <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    export default {
        props:['creating'],
        data(){
            return {
                placeInput:''
            }
        },
        name: "NewRecord",
        components:{
            VueGoogleAutocomplete
        },
        methods:{
            getAddressData(addressData, placeResultData, id){
                this.creating.form.city = addressData.country
                this.creating.form.latitude = addressData.latitude
                this.creating.form.longitude = addressData.longitude
                this.creating.form.zip_code = addressData.postal_code
                this.creating.form.state = addressData.administrative_area_level_1
                this.creating.form.address = addressData.street_number+' '+ addressData.route
                this.$refs.autocomplete.clear()
            },
            saveRecord(){
                this.$emit('save:record')
            },
            checking(val){
                this.placeInput = val.newVal
            }
        }
    }
</script>

<style scoped>

</style>
