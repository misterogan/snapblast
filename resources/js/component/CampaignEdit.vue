<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Edit Campaign
                </h1>
            </div>
            <div class="top-action">
            </div>
        </div>
        <div class="pl-6 pr-6 pb-6">
            <LoadingFetching v-if="is_loading"></LoadingFetching>
            <form action="" v-else>
                <div class="form-group mb-4">
                    <label for="campaign_name">Campaign Name</label>
                    <input class="w-50" type="text" name="campaign_name" id="campaign_name" placeholder="Type Campaign Name" autocomplete="off" v-model="form.campaign_name">
                    <small class="error mt-1 hide"></small>
                </div>
                <div class="form-group mb-4">
                    <label for="website_url">Website URL</label>
                    <input class="w-75" type="text" name="campaign_url" id="campaign_url" placeholder="Type Website URL" autocomplete="off" v-model="form.campaign_url"  @keyup="isUrlValid">
                    <small class="error mt-1 hide"></small>
                    <span  class="error-url mt-1" style="display: block" v-if="has_error.campaign_url">URL Format is not valid. Ex : https://domain.com</span>
                </div>
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col mr-4">
                            <label for="target_leads">Target Leads</label>
                            <input type="number" name="target_lead" id="target_lead" placeholder="ex : 1000" min="1" v-model="form.target_lead">
                            <small class="error mt-1 hide"></small>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-6">
                    <div class="row">
                        <div class="col mr-4">
                            <label for="postback_id">Callback URL</label>
                            <PostbackList name="postback_id" id="postback_id" @change="postback_change($event)" v-model="form.postback_id"></PostbackList>
                        </div>
                        <div class="col-5">
                            <label for="postback_trigger" :class="has_callback?'disabled':''">Callback Trigger</label>
                            <select class="w-100" name="postback_trigger" id="postback_trigger" :disabled='has_callback' v-model="form.postback_trigger">
                                <option value="manually">Manually</option>
                                <option value="submitted">When data submitted</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex align-center">
                        <div class="col mr-2">
                            <a @click="this.$router.go(-1)" class="btn btn-outline-primary d-flex align-center pl-4 pr-4">
                                <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Back
                            </a>
                        </div>
                        <div class="col">
                            <button type="button" :disabled="is_loading" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="submit">
                                <span class="text">Update</span>
                                <span class="loading"><span class="icon"></span> Saving</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import utils from '../utils'
import api from "../api";
import LoadingFetching from "./LoadingFetching";
import PostbackList from "./PostbackList";
export default {
    name: "CampaignCreate",
    components :{
        LoadingFetching,
        PostbackList
    },
    data(){
        return {
            has_callback : true,
            is_loading:true,
            is_submitting :false,
            form : {
                ad_id: '',
                campaign_name:'',
                campaign_url : '',
                target_lead :'',
                company_id :'',
                postback_id : '0',
                postback_trigger : ''
            },
            has_error : {
                campaign_url : false,
            },
        }
    },
    mounted() {
        utils.set_navigation('side_menu_campaign');
        api.get_campaign({ad_id : this.$route.params.ad_id}).then(response => {
            let res = response.data;
            if (res.code === 200){
                let obj = res.data;
                this.form.ad_id = obj.ad_id;
                this.form.campaign_name = obj.campaign_name;
                this.form.campaign_url = obj.campaign_url;
                this.form.target_lead = obj.target_lead;
                if(obj.postback){
                    this.form.postback_id = obj.postback.postback_code;
                }
                this.form.postback_trigger = obj.postback_trigger;
                this.has_callback = !obj.postback_id;
                this.is_loading = false;
            }else{
                this.$toast.error('Error');
                // this.$router.push('/campaign')
            }
        })
    },
    methods:{
        postback_change(event){
            if(event.target.value.length > 0){
                this.has_callback = false;
            }else{
                this.has_callback = true;
            }
        },
        submit(){
            this.is_submitting = true;
            api.update_campaign(this.form).then(response => {
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else{
                    this.$toast.success('Data has been changed successfully');
                    this.$router.push('/campaign')
                }
            })
        },
        isUrlValid() {
            let url = this.form.campaign_url;
            this.has_error.campaign_url = utils.valid_url(url)

            return this.has_error.campaign_url;
        },
    }
}
</script>

<style scoped>
    /*@import 'vue-toast-notification/dist/theme-sugar.css';*/
</style>
