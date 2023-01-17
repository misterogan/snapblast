<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Detail Campaign
                </h1>
            </div>
            <div class="d-flex">
                <router-link to="/campaign/create" class="btn btn-outline-primary mr-2 pl-4 pr-4">Create</router-link>
                <router-link v-if="campaign" :to="'/campaign/edit/'+campaign.ad_id" class="btn btn-primary mr-2 pl-4 pr-4"><img class="mr-1" width="15" src="/images/pencil.png" alt=""> Edit</router-link>
                <div class="date-range">
                    <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
                </div>
            </div>
        </div>
        <ul class="dashboard-summary pl-5 pr-6">
            <li>
                <div class="title">
                    Total Leads
                </div>
                <div class="value">
                    <div v-if="!is_loading">{{campaign.leads_count}}</div>
                    <div class="loader" v-if="is_loading"></div>
                </div>
            </li>
            <li>
                <div class="title">
                    Open
                </div>
                <div class="value">
                    <div v-if="!is_loading">
                        {{campaign.open_count}}
                        <small>{{campaign.open_rate}}%</small>
                    </div>
                    <div class="loader" v-if="is_loading"></div>
                </div>
            </li>
            <li>
                <div class="title">
                    Pending
                </div>
                <div class="value">
                    <div v-if="!is_loading">
                        {{campaign.pending_count}}
                        <small>{{campaign.pending_rate}}%</small>
                    </div>
                    <div class="loader" v-if="is_loading"></div>
                </div>
            </li>
            <li>
                <div class="title">
                    Followed Up
                </div>
                <div class="value">
                    <div v-if="!is_loading">
                        {{campaign.followup_count}}
                        <small>{{campaign.followup_rate}}%</small>
                    </div>
                    <div class="loader" v-if="is_loading"></div>
                </div>
            </li>
        </ul>
        <div class="pt-6 pb-6 pl-6 pr-6">
            <div class="row">
                <div class="col-7 campaign-detail bg-white rounded shadow p-6">
                    <div class="mb-3">
                        <span class="title">Campaign Name</span>
                        <span class="value" v-if="!is_loading">{{campaign.campaign_name}}</span>
                        <div class="loader" v-if="is_loading"></div>
                    </div>
                    <div class="mb-3">
                        <span class="title">Website URL</span>
                        <span class="value" v-if="!is_loading">{{campaign.campaign_url}}</span>
                        <div class="loader" v-if="is_loading"></div>
                    </div>
                    <div class="mb-3">
                        <span class="title">Target Leads</span>
                        <span class="value" v-if="!is_loading">{{campaign.target_lead}}</span>
                        <div class="loader" v-if="is_loading"></div>
                    </div>
                    <div class="mb-3">
                        <span class="title">Callback URL</span>
                        <span class="value" v-if="!is_loading">{{campaign.postback ? campaign.postback.postback_name + ' ('+ campaign.postback.postback_url+')' : '-'}}</span>
                        <div class="loader" v-if="is_loading"></div>
                    </div>
                    <div class="mb-3">
                        <span class="title">Callback Trigger</span>
                        <span class="value capitalize" v-if="!is_loading">{{campaign.postback_trigger ?? '-'}}</span>
                        <div class="loader" v-if="is_loading"></div>
                    </div>
                    <div>
                        <span class="title">Last Updated</span>
                        <span class="value" v-if="!is_loading">{{campaign.last_update}} by {{campaign.updated_by}}</span>
                        <div class="loader" v-if="is_loading"></div>
                    </div>
                </div>
                <div class="col-5 d-flex align-center justify-center">
                    <div class="progress" v-if="!is_loading">
                        <div class="percent" :style="'--clr:#04fc43;--num:' + leads_rate">
                            <svg>
                                <circle cx="140" cy="140" r="140"></circle>
                                <circle cx="140" cy="140" r="140"></circle>
                            </svg>
                        </div>
                        <div class="value">
                            {{campaign.leads_rate}}%
                            <small>{{campaign.leads_count}} <span>Leads</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pl-6 pr-6">
            <div class="detail-menu">
                <ul>
                    <li class="active" @click="filter_datatable()">All Data</li>
                    <li @click="filter_datatable('pending')">Pending</li>
                    <li @click="filter_datatable('open')">Open</li>
                    <li @click="filter_datatable('succeed')">Succeed</li>
                    <li @click="filter_datatable('failed')">Failed</li>
                    <li @click="filter_datatable('rejected')">Rejected</li>
                    <li @click="filter_datatable('missed')">Missed</li>
                    <li @click="filter_datatable('busy')">Busy</li>
                    <li @click="filter_datatable('invalid')">Invalid</li>
                    <li @click="filter_datatable('expired')">Expired</li>
                </ul>
            </div>
        </div>
        <div class="pl-6 pr-6 pt-6 pb-3 bg-whites">
            <table id="list_data_leads" v-once class="display w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Status</th>
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
        <div class="d-flex pl-6 pr-6 pb-4">
            <a @click="this.$router.go(-1)" class="btn btn-outline-primary d-flex align-center pl-4 pr-4">
                <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Back
            </a>
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
import utils from '../utils'
import api from "../api";
import Modal from './Modal.vue'
import PicList from "./PicList";
const d = new Date();

export default {
    name: "CampaignCreate",
    components:{
        Modal,
        PicList
    },
    data(){
        return {
            has_callback : true,
            is_loading:true,
            is_assigning:false,
            campaign: null,
            param_status : '',
            show_modal: false,
            modal : {
                title : 'Data Assignment :'
            },
            form : {
                assign_to : '',
                due_date: moment(d).add(1, 'day').format("YYYY-MM-DDThh:00")
            },
            filter:{
                assign_to :'',
                status :'',
                campaign_code : '',
            },
            data_code :'',
            leads_rate : 0,
        }
    },
    mounted() {
        utils.set_navigation('side_menu_campaign');
        this.get_campaign();
        this.init_datatable();
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    },
    methods:{
        callback_change(event){
            if(event.target.value === ''){
                this.has_callback = true;
            }else{
                this.has_callback = false;
            }
        },
        get_campaign(){
            api.get_campaign({ad_id : this.$route.params.ad_id}).then(response => {
                let res = response.data;
                if (res.code === 200){
                    this.campaign = res.data;
                    this.leads_rate = this.campaign.leads_rate > 100 ? 100 : this.campaign.leads_rate;
                    this.is_loading = false;
                }else{
                    this.$toast.error('Error');
                }
            })
        },
        init_datatable(){
            $('#list_data_leads').DataTable(
                {
                    processing: true,
                    ajax:  {
                        url: "/api/lead/data-table/list",
                        data: {
                            "data_status": this.get_filter_datatable,
                            "ad_id" : this.$route.params.ad_id
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
                        { data: 'full_name' },
                        { data: 'email' },
                        { data: 'phone_number' },
                        { data: 'utm_source' },
                        { data: 'pic' ,
                            render:function (data, type, full, meta){
                                if(data){
                                    return data.name
                                }else{
                                    return '<a data-code="'+full.data_code+'" class="btn btn-primary btn-sm assign_data">Assign</a>'
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
            let table = $("#list_data_leads");
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
            this.param_status = status;
            $('#list_data_leads').DataTable().ajax.reload();
        },
        get_filter_datatable(){
            return this.param_status;
        },
        open_assign_data_modal(data){
            this.is_loading = false;
            this.show_modal = true;
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
                    $('#list_data_leads').DataTable().ajax.reload();
                }else{
                    this.$toast.warning('We are sorry, we couldn\'t update the request');
                }
            });
        },
        open_detail_data(data){
            this.$router.push('/datatable/'+data)
        }
    }
}
</script>

<style scoped>
    /*@import 'vue-toast-notification/dist/theme-sugar.css';*/
</style>
