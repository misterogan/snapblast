<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Data Table
                </h1>
            </div>
            <div class="d-flex">
                <div class="mr-2">
                    <a @click="clear_filter" class="btn btn-primary"><img class="mr-1" src="/images/reset.png" alt="" width="20"> Reset Filter</a>
                </div>
                <div class="date-range">
                    <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
                </div>
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <div class="data-filter mb-6">
                <h4>Data Filter</h4>
                <div class="row mt-3">
                    <div class="col-3 pr-2">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select name="filter_status" id="filter_status" class="w-100" v-model="filter.status">
                                <option value="">Select One</option>
                                <option value="pending">Pending</option>
                                <option value="open">Open</option>
                                <option value="succeed">Succeed</option>
                                <option value="failed">Failed</option>
                                <option value="rejected">Rejected</option>
                                <option value="missed">Missed</option>
                                <option value="busy">Busy</option>
                                <option value="invalid">Invalid</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4 pr-2">
                        <div class="form-group">
                            <label for="filter_assign_to">PIC</label>
                            <PicList name="filter_assign_to" id="filter_assign_to" class="w-100" v-model="filter.assign_to">
                            </PicList>
                        </div>
                    </div>
                    <div class="col-4 pr-2">
                        <div class="form-group">
                            <label for="filter_campaign">Campaign</label>
                            <CampaignList name="filter_campaign" id="filter_campaign"  class="w-100" v-model="filter.campaign_code"></CampaignList>
                        </div>
                    </div>
                    <div class="col-1 d-flex align-end">
                        <a class="btn btn-filter w-100" href="javascript:void(0)" @click="filter_datatable"><img width="18" class="mr-1" src="/images/search.png"
                                                                                                                 alt=""> Search</a>
                    </div>
                </div>
            </div>
            <table id="data_lead_list" v-once class="display w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Status</th>
<!--                    <th>Campaign Name</th>-->
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Source</th>
                    <th>PIC</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <Teleport to="body">
        <modal class="md" :show="show_modal" @close="show_modal = false">
            <template #header>
                <h3>{{modal.title}}</h3>
            </template>
            <template #body>
                <form action="">
                    <div class="form-group mb-2">
                        <label for="assign_to">PIC</label>
                        <PicList name="assign_to" id="assign_to" class="w-100" v-model="form.assign_to"></PicList>
                    </div>
                    <div class="form-group mb-2">
                        <label for="due_date">Due Date</label>
                        <input name="due_date" id="due_date" type="datetime-local" class="w-100" v-model="form.due_date">

                    </div>
                </form>
            </template>
            <template #footer>
                <div class="row justify-end">
                    <button type="button" :class="is_assigning?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="assign_data">
                        <span class="text">Assign</span>
                        <span class="loading"><span class="icon"></span> Updating</span>
                    </button>
                    <button class="btn btn-outline-primary align-center pl-4 pr-4 ml-2" @click="show_modal=false">
                        Close
                    </button>
                </div>
            </template>
        </modal>
    </Teleport>
</template>
<script>
import Modal from './Modal.vue'
import PicList from "./PicList";
import CampaignList from "./CampaignList";
import api from "../api";

const d = new Date();

export default {
    name: "Datatables",
    data(){
        return{
            is_assigning : false,
            is_loading:false,
            show_modal: false,
            modal : {
                title : 'Data Assignment :'
            },
            form : {
                assign_to : '',
                due_date: moment(d).add(1, 'day').format("YYYY-MM-DDThh:00"),
                data_code :''
            },
            filter:{
                assign_to :'',
                status :'',
                campaign_code : ''
            }
        }
    },
    mounted() {
        this.init_datatable();
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    },
    components:{
        Modal,
        PicList,
        CampaignList
    },
    methods:{
        init_datatable(){
            $('#data_lead_list').DataTable(
                {
                    processing: true,
                    ajax:  {
                        url: "/api/lead/data-table/list",
                        data: {
                            "data_status": this.get_filter_status,
                            "ad_id" : this.get_filter_campaign,
                            "assign_to" : this.get_filter_assign_to
                        },
                        error: function (xhr, error, code) {
                            if(xhr.status === 401){
                                self.$toast.error('Session has been expired');
                                setTimeout(function (){
                                    self.$router.push({
                                        name: 'Login',
                                        query : {
                                            'redirect' : self.$route.fullPath
                                        }
                                    });
                                }, 1000)
                            }
                        }
                    },
                    columns : [
                        { data: 'DT_RowIndex', class:'text-center'},
                        { data: 'data_code'},
                        { data: 'data_status',
                            render : function (data){
                                return '<span class="pt-1 pb-1 badge '+data +' capitalize">'+data+'</span>';
                            }
                        },
                        // { data: 'campaign_name' },
                        { data: 'full_name' },
                        { data: 'email' },
                        { data: 'phone_number' },
                        { data: 'utm_source' },
                        { data: 'pic' ,
                            render:function (data, type, full, meta){
                                if(full.data_status === 'pending'){
                                    return '<a data-code="'+full.data_code+'" class="btn btn-primary btn-sm assign_data">Assign</a>'
                                }else{
                                    return data.name
                                }
                            }
                        },
                        { data: 'data_code',class: 'text-center',
                            render:function(data, type, full, meta){
                                return '<a href="javascript:void(0)" data-code="'+full.data_code+'" class="open_detail_data">Detail</a>'
                            }
                        },
                    ]
                }
            );
            const self = this
            let table = $("#data_lead_list");
            $('tbody', table).on( 'click', '.assign_data', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.open_assign_data_modal(cell)
            });
            $('tbody', table).on( 'click', '.open_detail_data', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.open_detail_data(cell)
            });
        },
        filter_datatable(status){
            $('#data_lead_list').DataTable().ajax.reload();
        },
        get_filter_datatable(){
            return this.filter.status;
        },
        get_filter_status(){
            return this.filter.status;
        },
        get_filter_campaign(){
            return this.filter.campaign_code;
        },
        get_filter_assign_to(){
            return this.filter.assign_to;
        },
        open_assign_data_modal(data){
            this.is_loading = false;
            this.show_modal = true;
            this.is_assigning = false;
            this.data_code = data;
        },
        assign_data(){
            this.is_assigning = true;
            api.assign_lead(
                {
                    'due_date' : this.form.due_date,
                    'assign_to' : this.form.assign_to,
                    'data_code' : this.data_code
                }
            ).then(response=>{
                this.show_modal = false;
                let obj = response.data;
                if(obj.code === 200){
                    this.$toast.success('Data already saved successfully');
                    $('#data_lead_list').DataTable().ajax.reload();
                }else{
                    this.$toast.warning('We are sorry, we couldn\'t update the request');
                }
            });
        },
        open_detail_data(data){
            this.$router.push('/datatable/'+data)
        },
        clear_filter(){
            this.filter.assign_to = '';
            this.filter.status = '';
            this.filter.campaign_code = '';
            this.filter_datatable();
        }
    }
}
</script>

<style scoped>

</style>
