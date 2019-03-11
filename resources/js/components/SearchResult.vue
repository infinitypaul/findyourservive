<template>
    <div class="col-md-12 py-4">
        <div class="card">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                <div class="list-group">
                    <a href="#"  class="list-group-item list-group-item-action flex-column align-items-start" v-for="result in results" @click.prevent="loadModal(result)">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ result.title }}</h5>
                            <small>{{ result.human_readable_date }}</small>
                        </div>
                        <p class="mb-1">{{ result.excerpt }}</p>
                        <small>{{ result.address +' '+result.city+' '+result.state+ ' '+result.zip_code}}</small>
                    </a>
                </div>
            </div>
        </div>
        <modal :show="showModal" :result="result" @close="showModal = false"></modal>
    </div>
</template>

<script>
    import modal from './SearchDetails.vue'
    export default {
        props:['results'],
        name: "SearchResult",
        components:{
            modal
        },
        data() {
            return {
                result:{},
                showModal: false
            }
        },
        methods:{
            loadModal(result){
                this.result = result
                this.showModal = true
            }
        }
    }
</script>

<style scoped>

</style>
