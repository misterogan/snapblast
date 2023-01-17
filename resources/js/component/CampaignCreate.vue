<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Create Campaign
                </h1>
            </div>
            <div class="top-action">
            </div>
        </div>
        <div class="pl-6 pr-6 pb-6">
            <form action="">
                <div class="form-group mb-4">
                    <label for="campaign_name">Campaign Name*</label>
                    <input class="w-50" type="text" name="campaign_name" id="campaign_name" placeholder="Type Campaign Name" autocomplete="off" v-model="form.campaign_name">
                    <small class="error mt-1 hide"></small>
                </div>
                <div class="form-group mb-4">
                    <label for="campaign_url">Website URL*</label>
                    <input class="w-75" type="text" name="campaign_url" id="campaign_url" placeholder="Type Website URL" autocomplete="off" v-model="form.campaign_url"  @keyup="isUrlValid">
                    <small class="error mt-1 hide"></small>
                    <span  class="error-url mt-1" style="display: block" v-if="has_error.campaign_url">URL Format is not valid. Ex : https://domain.com</span>
                </div>
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col mr-4">
                            <label for="target_lead">Target Leads*</label>
                            <input type="number" name="target_lead" id="target_lead" placeholder="ex : 1000" min="1" v-model="form.target_lead">
                            <small class="error mt-1 hide"></small>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-6">
                    <div class="row">
                        <div class="col mr-4">
                            <label for="postback_id">Postback URL</label>
                            <PostbackList name="postback_id" id="postback_id" @change="postback_change($event)" v-model="form.postback_id"></PostbackList>
                            <small class="mt-1 d-block">Create Postback <router-link to="/postback/create">Here</router-link></small>
                        </div>
                        <div class="col-5">
                            <label for="postback_trigger" :class="has_callback?'disabled':''">Postback Trigger</label>
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
                            <button type="button" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="submit">
                                <span class="text">Submit</span>
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
import api from '../api'
import PostbackList from "./PostbackList";

export default {
    name: "CampaignCreate",
    data(){
        return {
            has_callback : true,
            is_submitting:false,
            is_loading : true,
            form : {
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
    components:{
        PostbackList
    },
    mounted() {
        utils.set_navigation('side_menu_campaign');
    },
    methods:{
        postback_change(event){
            if(event.target.value.length > 0){
                this.has_callback = false;
            }else{
                this.has_callback = true;
            }
        },
        isUrlValid() {
            let url = this.form.campaign_url;
            this.has_error.campaign_url = utils.valid_url(url)

            return this.has_error.campaign_url;
        },
        submit(){
            this.is_submitting = true;
            api.submit_campaign(this.form).then(response => {
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else{
                    this.$toast.success('Data saved successfully');
                    this.$router.push('/campaign')
                }
            })
        }
    }
}
</script>

<style scoped>
    /*@import 'vue-toast-notification/dist/theme-sugar.css';*/
</style>
