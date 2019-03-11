<template>
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <form class="card card-sm" style="border: none; background-color: rgba(245, 245, 245, 0.4)">
                <div class="card-body row no-gutters align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-search h4 text-body"></i>
                    </div>
                    <!--end of col-->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ form.distance }} <span v-if="form.distance !== 'Distance' ">Km</span></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   v-for="(val, index) in distances" href="#"
                                   @click.prevent="form.distance = val" >

                                    <span v-if="index == distances.length - 1">
                                    <div  role="separator" class="dropdown-divider"></div>
                                    </span>
                                {{ val }} <span v-if="val !== 'Anywhere'">Km</span>
                                </a>


                            </div>
                        </div>
                        <input
                            type="text"
                            class="form-control form-control-lg  form-control-borderless"
                            placeholder="Search Servives or keywords"
                            v-model="form.keyword"
                            :class="{ ' is-invalid' : errors['keyword'] }"
                        >
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" @click="getResult">Search</button>
                        </div>
                    </div>
                    <!--end of col-->
                </div>
            </form>
        </div>
        <search-result v-if="results.length" :results="results"></search-result>
    </div>
</template>

<script>
    import VueGeolocation from 'vue-browser-geolocation';
    import SearchResult from './SearchResult.vue'
    Vue.use(VueGeolocation);
    export default {
        props:['endpoint'],
        name: "Search",
        data(){
            return {
                distances: ['1', '2', '5', '10', '50', '100', 'Anywhere'],
                form:{
                    distance:'Distance',
                },
                errors: [],
                results: {}
            }
        },
        components:{
            SearchResult
        },
        methods:{
            getLocation(){
                this.$getLocation()
                    .then(coordinates => {
                        this.form.latitude = coordinates.lat;
                        this.form.longitude = coordinates.lng;

                    })
                    .catch((error)=>{
                        this.distances =['Anywhere']
                        this.form.distance = 'Anywhere';
                    })
            },
            getResult(){
                axios.post(`${this.endpoint}`, this.form).then((response)=>{
                    this.errors = []
                    this.results = response.data.data
                }).catch((error) => {
                    if(error.response.status === 422){
                        this.errors = error.response.data.errors
                    }

                })
            }
        },
        mounted() {
            this.getLocation()
        }
    }
</script>

<style scoped>

</style>
