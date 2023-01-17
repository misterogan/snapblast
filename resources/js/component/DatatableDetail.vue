<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Detail Data
                </h1>
            </div>
            <div>
                <div class="d-flex align-center">
                    <div class="mr-5 d-flex align-center">
                        <span class="fw-300 mr-1">Person In Charge : </span> <span class="text-underlined" v-if="!is_loading">{{pic}}</span> <a @click="assign" class="reassign ml-1 d-flex align-center" href="javascript:void(0)"><img width="20" src="/images/pencil-blue.png" alt=""></a>
                    </div>
                    <div class="mr-5">
                        <span class="fw-300">Last status :</span> <span v-if="!is_loading" :class="'capitalize badge '+ lead.data_status + ' pt-1 pb-1 pl-2 pr-2 ml-1'">{{ lead.data_status }}</span>
                    </div>
                    <button type="button" class="btn btn-outline-primary pl-4 pr-4" @click="change_status">
                        <span class="text">Update Status</span>
                        <span class="loading"><span class="icon"></span> Saving</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="pl-7 pr-6">
            <div class="row mb-6">
                <div class="col-8">
                    <LoadingFetching v-if="is_loading"></LoadingFetching>
                    <h3 class="mb-3" v-if="!is_loading">{{lead.campaign.campaign_name}}</h3>
                    <div v-if="!is_loading" class="row mb-2">
                        <div class="col-6 campaign-detail">
                            <div class="mb-3">
                                <div class="title-section">
                                    BASIC INFORMATION
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="title">Name</span>
                                <span class="value">{{lead.full_name}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="title">Email</span>
                                <span class="value">{{lead.email ?? '-'}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="title">Phone</span>
                                <span class="value">{{lead.phone_number ?? '-'}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="title">DOB</span>
                                <span class="value">{{lead.dob ?? '-'}}</span>
                            </div>
                            <div class="">
                                <span class="title">Company Name</span>
                                <span class="value">{{lead.company_name ?? '-'}}</span>
                            </div>
<!--                            <div class="mb-3">-->
<!--                                <span class="title">Submitted Date</span>-->
<!--                                <span class="value">{{lead.submitted_date}}</span>-->
<!--                            </div>-->
                        </div>
                        <div class="col-6 campaign-detail">
                            <div class="mb-3">
                                <div class="title-section">
                                    TRACKER INFORMATION
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="title">utm_source</span>
                                <span class="value">{{lead.utm_source ?? '-'}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="title">utm_medium</span>
                                <span class="value">{{lead.utm_medium ?? '-'}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="title">utm_banner</span>
                                <span class="value">{{lead.utm_banner ?? '-'}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="title">utm_term</span>
                                <span class="value">{{lead.utm_term ?? '-'}}</span>
                            </div>
                            <div class="">
                                <span class="title">utm_campaign</span>
                                <span class="value">{{lead.utm_campaign ?? '-'}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="data-collapsed">
                        <div class="row" v-if="!is_loading">
                            <div class="mb-3 pt-5">
                                <div class="title-section">
                                    ADDITIONAL INFORMATION
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="!is_loading">
                            <div class="col-6 campaign-detail">
                                <div class="mb-3">
                                    <span class="title">OS</span>
                                    <span class="value">{{lead.os ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Browser</span>
                                    <span class="value">{{lead.browser ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Device</span>
                                    <span class="value">{{lead.device ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Device Name</span>
                                    <span class="value">{{lead.device_name ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Latitude - Longtitude</span>
                                    <span class="value">{{lead.lat ?? '-'}}, {{lead.long ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">IP</span>
                                    <span class="value">{{lead.ip ?? '-'}}</span>
                                </div>
                            </div>
                            <div class="col-6 campaign-detail">
                                <div class="mb-3">
                                    <span class="title">Sub 1</span>
                                    <span class="value">{{lead.sub1 ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Sub 2</span>
                                    <span class="value">{{lead.sub2 ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Sub 3</span>
                                    <span class="value">{{lead.sub3 ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Sub 4</span>
                                    <span class="value">{{lead.sub4 ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Sub 5</span>
                                    <span class="value">{{lead.sub5 ?? '-'}}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="title">Referer</span>
                                    <span class="value">{{lead.referer ?? '-'}}</span>
                                </div>
                            </div>
                        </div>
                        <div v-if="!is_loading" class="row mb-5">
                            <div class="col-10 campaign-detail">
                                <span class="title">Message</span>
                                <span class="value fs-primary" v-html="lead.message ?? '-'"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4" v-if="!is_loading">
                        <div class="col-12">
                            <div class="col"><a class="fs-primary show-more" @click="show_more">{{expanded ? 'Show Less' : 'Show More'}}</a></div>
                        </div>
                    </div>

                    <div class="row" v-if="!is_loading">
                        <a @click="this.$router.go(-1)" class="btn btn-outline-primary d-flex align-center pl-4 pr-4 mr-2">
                            <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Back
                        </a>
                        <button type="button" class="btn btn-primary pl-4 pr-4 mr-2" @click="change_status">
                            <span class="text d-flex align-center"> <img class="mr-1" src="/images/send.png" alt="" width="20"> Send Postback</span>
                            <span class="loading"><span class="icon"></span> Sending</span>
                        </button>
                        <button type="button" class="btn btn-primary pl-4 pr-4" @click="change_status">
                            <span class="text">Update Status</span>
                            <span class="loading"><span class="icon"></span> Saving</span>
                        </button>
                    </div>
                </div>
                <div class="col-4 campaign-detail">
                    <div class="bg-white shadow rounded p-6 data-history-div">
                        <LoadingFetching v-if="is_loading_histories"></LoadingFetching>
                        <ul class="data-history snippet pl-6" v-else>
                            <li class="" v-for="h in histories">
                                <div class="title">
                                    {{h.title}}
                                </div>
                                <div class="log">
                                    <p style="font-size: 12px;font-weight: 500;color:#0d0d0d">{{h.created_date}} by <u>{{h.created_by}}</u></p>
                                    <div class="history-note" v-html="h.note"></div>
                                    <div class="d-flex align-center mt-1">
                                        <span :class="'capitalize badge '+h.old_status">{{h.old_status}}</span> <ion-icon class="mr-1 ml-1" name="arrow-forward-outline"></ion-icon> <span :class="'capitalize badge '+ h.new_status">{{h.new_status}}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <a v-if="!is_loading_histories" @click="show_all" class="view_all">View All <ion-icon class="ml-1" name="arrow-forward-outline"></ion-icon></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="history-side-overlay" @click="close_history_detail"></div>
        <div class="history-side p-7">
            <div class="row mb-5">
                <div class="col-6">
                    <div class="title-section">
                        DATA HISTORIES
                    </div>
                </div>
                <div class="col-6 text-right">
                    <a style="cursor: pointer" @click="close_history_detail">close</a>
                </div>
            </div>
            <div class="row campaign-detail">
                <LoadingFetching v-if="is_loading_histories"></LoadingFetching>
                <ul class="data-history pl-3" v-else>
                    <li class="" v-for="h in histories">
                        <div class="title">
                            {{h.title}}
                        </div>
                        <div class="log">
                            <p style="font-size: 12px;font-weight: 500;color:#0d0d0d">{{h.created_date}} by <u>{{h.created_by}}</u></p>
                            <div class="history-note" v-html="h.note"></div>
                            <div class="d-flex align-center mt-1">
                                <span :class="'capitalize badge '+h.old_status">{{h.old_status}}</span> <ion-icon class="mr-1 ml-1" name="arrow-forward-outline"></ion-icon> <span :class="'capitalize badge '+ h.new_status">{{h.new_status}}</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <Teleport to="body">
        <!-- use the modal component, pass in the prop -->
        <modal class="md" :show="show_modal" @close="show_modal = false">
            <template #header>
                <h3>{{modal.title}}</h3>
            </template>
            <template #body>
                <ul class="list-of-action-status mb-5">
                    <li v-if="lead.data_status !== 'succeed'">
                        <input type="radio" value="succeed" name="status" v-model="form.status" id="status_succeed" checked>
                        <label for="status_succeed">
                            <span class="badge succeed p-1 mr-2"><b>Succeed</b></span> Data bisa dihubungi dan bersedia untuk membeli
                        </label>
                    </li>
                    <li v-if="lead.data_status !== 'failed'">
                        <input type="radio" value="failed" name="status" v-model="form.status" id="status_failed">
                        <label for="status_failed">
                            <span class="badge failed p-1 mr-2"><b>Failed</b></span> Data bisa dihubungi dan tidak bersedia untuk membeli
                        </label>
                    </li>
                    <li v-if="lead.data_status !== 'rejected'">
                        <input type="radio" value="rejected" name="status" v-model="form.status" id="status_rejected">
                        <label for="status_rejected">
                            <span class="badge rejected p-1 mr-2"><b>Rejected</b></span> Panggilan ditolak pada saat dihubungi
                        </label>
                    </li>
                    <li v-if="lead.data_status !== 'missed'">
                        <input type="radio" value="missed" name="status" v-model="form.status" id="status_missed">
                        <label for="status_missed">
                            <span class="badge missed p-1 mr-2"><b>Missed</b></span> Panggilan tidak diangkat
                        </label>
                    </li>
                    <li v-if="lead.data_status !== 'busy'">
                        <input type="radio" value="busy" name="status" v-model="form.status" id="status_busy">
                        <label for="status_busy">
                            <span class="badge busy p-1 mr-2"><b>Busy</b></span> Panggilan sibuk
                        </label>
                    </li>
                    <li v-if="lead.data_status !== 'invalid'">
                        <input type="radio" value="invalid" name="status" v-model="form.status" id="status_invalid">
                        <label for="status_invalid">
                            <span class="badge invalid p-1 mr-2"><b>Invalid</b></span> Nomor telepon yang diinput tidak valid
                        </label>
                    </li>
                </ul>
                <div class="form-group">
                    <textarea name="status_notes" id="status_notes" class="w-100 p-2" rows="3" placeholder="Input notes" @keyup="has_error=false" v-model="form.note"></textarea>
                    <small v-if="has_error" class="error">This field is mandatory</small>
                </div>
            </template>
            <template #footer>
                <div class="row justify-end">
                    <button type="button" :disabled="is_submitting" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="submit_status">
                        <span class="text">Update</span>
                        <span class="loading"><span class="icon"></span> Saving</span>
                    </button>
                    <button class="btn btn-outline-primary align-center pl-4 pr-4 ml-2" @click="show_modal=false">
                        Close
                    </button>
                </div>
            </template>
        </modal>
    </Teleport>
    <Teleport to="body">
        <modal class="md" :show="show_modal_assign" @close="show_modal_assign = false">
            <template #header>
                <h3>{{modal.title_assign}}</h3>
            </template>
            <template #body>
                <form action="">
                    <div class="form-group mb-2">
                        <label for="assign_to">PIC</label>
                        <PicList name="assign_to" id="assign_to" class="w-100" v-model="form_assign.assign_to"></PicList>
                    </div>
                    <div class="form-group mb-2">
                        <label for="due_date">Due Date</label>
                        <input name="due_date" id="due_date" type="datetime-local" class="w-100" v-model="form_assign.due_date">
                    </div>
                </form>
            </template>
            <template #footer>
                <div class="row justify-end">
                    <button type="button" :disabled="is_assigning" :class="is_assigning?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="assign_data">
                        <span class="text">Assign</span>
                        <span class="loading"><span class="icon"></span> Updating</span>
                    </button>
                    <button class="btn btn-outline-primary align-center pl-4 pr-4 ml-2" @click="show_modal_assign=false">
                        Close
                    </button>
                </div>
            </template>
        </modal>
    </Teleport>
</template>

<script>
import utils from '../utils'
import Modal from './Modal.vue'
import api from "../api";
import LoadingFetching from "./LoadingFetching";
import PicList from "./PicList";

const d = new Date();
export default {
    name: "DatatableDetail",
    data(){
      return{
          is_assigning : false,
          is_submitting:false,
          is_loading:true,
          is_loading_histories : true,
          show_modal: false,
          show_modal_assign :false,
          has_error:false,
          lead:null,
          histories : null,
          expanded : false,
          pic : '',
          form:{
              status : 'succeed',
              note:'',
              data_code : this.$route.params.data_code
          },
          form_assign : {
              assign_to : '',
              due_date: moment(d).add(1, 'day').format("YYYY-MM-DDThh:00"),
              data_code :''
          },
          modal : {
              title : 'Choose the Status :',
              title_assign : 'Data Assignment :'
          }
      }
    },
    components: {
        Modal,
        LoadingFetching,
        PicList
    },
    mounted() {
        utils.set_navigation('side_menu_datatable');
        this.get();
        this.get_histories();
    },
    methods:{
        get(){
            api.get_detail_data_table({data_code : this.$route.params.data_code}).then(response => {
                let res = response.data;
                if (res.code === 200){
                    this.lead = res.data;
                    this.pic = this.lead.pic ? this.lead.pic.name : '-'
                    this.is_loading = false;
                }else{
                    this.$toast.error('Error');
                }
            })
        },
        get_histories(){
            this.is_loading_histories = true;
            api.get_lead_data_histories({data_code : this.$route.params.data_code}).then(response => {
                let res = response.data;
                if (res.code === 200){
                    this.histories = res.data;
                    this.is_loading_histories = false;
                }else{
                    this.$toast.error('Error');
                }
            })
        },
        change_status(){
            this.is_submitting = false;
            this.show_modal = true;
        },
        submit_status(){
            this.is_submitting = true;
            if(this.form.note === ''){
                this.has_error = true;
                this.is_submitting = false;
            }else{
                api.update_lead_status(this.form).then(response=>{
                    let obj = response.data;
                    if(obj.code === 200){
                        this.$toast.success('Data saved successfully');
                        this.lead.data_status = this.form.status;
                        this.form.status = 'succeed';
                        this.form.note = '';
                        this.get_histories();
                    }else {
                        this.$toast.warning(obj.message);
                    }
                    this.show_modal = false;
                })
            }
        },
        show_all(){
            $('.history-side').addClass('show');
            $('.history-side-overlay').addClass('show');
        },
        show_more(){
            this.expanded = !this.expanded;
            $(".data-collapsed").toggleClass('show');
        },
        close_history_detail(){
            $('.history-side').removeClass('show');
            $('.history-side-overlay').removeClass('show');
        },
        assign(){
            this.show_modal_assign = true;
        },
        assign_data(){
            this.is_assigning = true;
            api.assign_lead(
                {
                    'due_date' : this.form_assign.due_date,
                    'assign_to' : this.form_assign.assign_to,
                    'data_code' : this.form.data_code
                }
            ).then(response=>{
                this.show_modal_assign = false;
                let obj = response.data;
                if(obj.code === 200){
                    this.pic = $("#assign_to option:selected").text();
                    this.lead.data_status =  'open';
                    this.$toast.success('Data already saved successfully');
                    this.get_histories();
                }else{
                    this.$toast.warning('We are sorry, we couldn\'t update the request');
                }
            });
        },
    }
}
</script>

<style scoped>

</style>
