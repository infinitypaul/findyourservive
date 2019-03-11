<template>
    <div>
        <div class="card">
            <div class="card-header">
                {{ response.table | uppercase }}
                <a class="float-right" v-if="response.allow.creation" href="#" @click.prevent="creating.active = !creating.active">
                    {{ creating.active ? 'Cancel' : 'New Record'}}
                </a>
            </div>
            <div class="card-body ">
                <new-record :creating="creating" v-if="response.allow.creation && creating.active" v-on:save:record="store"></new-record>
                <h5 class="card-title">Search/Advanced Search</h5>
                <form action="#" @submit.prevent="getRecords">
                    <div class="row row-fluid">
                        <div class="form-group col-md-3">
                            <select id="" class="form-control" v-model="search.column">
                                <option :value="column" v-for="column in response.displayable">
                                    {{ column}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <select class="form-control" v-model="search.operator">
                                <option value="equals">=</option>
                                <option value="contains">contains</option>
                                <option value="start_with">Start With</option>
                                <option value="ends_with">End With</option>
                                <option value="greater_than">Greater Than</option>
                                <option value="less_than">Less Than</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" v-model="search.value">
                                <span class="input-group-btn">
                                    <button class="btn btn-outline-info" type="submit">Search</button>
                                </span>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="filter" class="form-control-label"> Quick Search Current Results</label>
                        <input type="text" id="filter" class="form-control" v-model="quickSearchQuery"/>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="limit" class="form-control-label">Display Records</label>
                                <select name="" id="limit" class="form-control" v-model="limit" @change="getRecords()">
                                    <option value="10"> 10 </option>
                                    <option value="50"> 50 </option>
                                    <option value="100"> 100 </option>
                                    <option value="1000"> 1000 </option>
                                    <option value=""> All </option>
                                </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4" >
            <div class="card-header" v-if="selected.length">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> With Selected</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" @click.prevent="destroy(selected)">Delete {{ selected.length }} record </a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive" v-if="filteredRecords.length">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th  v-if="canSelectItems">
                                    <input
                                        type="checkbox"
                                        @change="toggleSelectAll"
                                        :checked="filteredRecords.length === selected.length"

                                    />

                            </th>
                            <th v-for="column in response.displayable">
                                <span class="sortable" @click="sortBy(column)">{{ response.custom_columns[column] || column|uppercase }}</span>
                                <span class="arrow" v-if="sort.key === column"
                                      :class="{ 'arrow--asc' : sort.order === 'asc', 'arrow--desc' : sort.order === 'desc' }"></span>
                            </th>

                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="record in filteredRecords">
                            <td v-if="canSelectItems">
                                    <input type="checkbox"
                                           v-model="selected"
                                           :value="record.id"

                                    />

                            </td>
                            <td v-for="columnValue, column in record">
                                <template v-if="editing.id === record.id && isUpdatable(column) ">
                                    <div class="form-group" :class="{ ' is-invalid' : editing.errors[column]}">
                                        <input type="text" class="form-control"
                                               v-bind:key="columnValue"
                                               v-model="editing.form[column]"
                                               :readonly="response.read_only_columns.includes(column)"
                                        >
                                        <span class="form-text text-muted" v-if="editing.errors[column]">
                                    {{ editing.errors[column][0]}}
                                </span>
                                    </div>

                                </template>
                                <template v-else>
                                    {{ columnValue }}
                                </template>
                            </td>
                            <td>
                                <a href="#" @click.prevent="edit(record)" v-if="editing.id != record.id" title="Quick Edit">QE</a>
                                <a href="#" @click.prevent="fullEdit(record)" v-if="editing.id != record.id" title="Full Edit">FE</a>
                                <a href="#" @click.prevent="destroy(record.id)" v-if="response.allow.deletion" title="Delete">Del</a>
                                <template v-if="editing.id === record.id">
                                    <a href="#" @click.prevent="update">Save</a><br>
                                    <a href="#" @click.prevent="editing.id = null">Cancel</a>
                                </template>
                            </td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
    import queryString from 'query-string'
    import NewRecord from './NewRecord.vue'
    import ServiceEdit from './ServiceEdit.vue'

    export default {
        props: ['endpoint'],
        data(){
            return {
                response:{
                    table:'',
                    displayable: [],
                    updatable:[],
                    records: [],
                    allow:{}
                },
                sort:{
                    key:'id',
                    order:'desc'
                },
                editing:{
                  id: null,
                  form:{},
                  errors: []
                },
                search:{
                  value:'',
                  operator: 'equals',
                  column:'id'
                },
                creating:{
                  active:false,
                  form:{
                      city: null,
                      state:null,
                      latitude:null,
                      longitude:null,
                      zip_code:null,
                      address:null
                  },
                  errors: []
                },
                quickSearchQuery: '',
                limit:10,
                selected:[],
                autocomplete:null

            }

        },
        components:{
            NewRecord,
            ServiceEdit
        },
        computed:{
            filteredRecords(){
                let data = this.response.records.data
                if(this.response.records.data){
                    data = data.filter((row) => {
                        return Object.keys(row).some((key) => {
                            return String(row[key]).toLowerCase().indexOf(this.quickSearchQuery.toLowerCase()) > -1
                        })
                    })
                }


                if(this.sort.key){
                    data = _.orderBy(data, (i) => {
                        let value = i[this.sort.key]
                        if(!isNaN(parseFloat(value))){
                            return parseFloat(value)
                        }

                        return String(i[this.sort.key]).toLowerCase()
                    }, this.sort.order)
                }
                return data
            },
            canSelectItems(){
                return this.filteredRecords.length <= 90
            }
        },
        filters: {
            uppercase: function(s) {
                if (typeof s !== 'string') return ''
                return s.charAt(0).toUpperCase() + s.slice(1)
            }
        },
        methods:{
          getRecords(){
              return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => {
                  this.response = response.data.data
                  //console.log(response.data.data)
              })
          },
            getQueryParameters(){
                return queryString.stringify({
                    limit:this.limit,
                    page:1,
                    ...this.search
                })
            },
            sortBy(column){
              this.sort.key = column
              this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'

            },
            edit(record){
              this.editing.errors = []
              this.editing.id = record.id
              this.editing.form = _.pick(record, this.response.updatable)
            },
            fullEdit(record){
                window.location.replace('/edit_service/'+record.id);
            },
            isUpdatable(column){
              return this.response.updatable.includes(column)
            },
            update(){
              axios.patch(`${this.endpoint}/${this.editing.id}`, this.editing.form).then(()=>{
                    this.getRecords().then(() => {
                        this.editing.id = null
                        this.editing.form = {}
                    })
              }).catch((error)=> {
                    this.editing.errors = error.response.data.errors
                })
            },
            store(){
              axios.post(`${this.endpoint}`, this.creating.form).then(()=>{
                  this.getRecords().then(() => {
                      this.creating.active = false
                      this.creating.form = {}
                      this.creating.errors = []
                  })
              }).catch((error) => {
                  if(error.response.status === 422){
                      this.creating.errors = error.response.data.errors
                  }

              })
            },
            destroy(record){
              if(!window.confirm('Are You sure you want to Delete this')){
                  return
              }
              axios.delete(`${this.endpoint}/${record}`).then(()=>{
                  this.selected = []
                  this.getRecords()
              })
            },
            toggleSelectAll(){
              if(this.selected.length > 0){
                  this.selected = []
                  return
              }
                this.selected = _.map(this.filteredRecords, 'id')
            }
        },
        mounted() {
            this.getRecords()
        }
    }
</script>

<style lang="scss">
    .sortable {
        cursor: pointer;
    }

    .arrow{
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
        opacity: .6;

        &--asc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid #222;
         }


        &--desc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #222;
        }
    }
</style>
