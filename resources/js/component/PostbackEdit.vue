<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Edit Postback
                </h1>
            </div>
            <div class="top-action">
            </div>
        </div>
        <div class="pl-6 pr-6 pb-6">
            <LoadingFetching v-if="is_loading"></LoadingFetching>
            <form id="postback_create_form" action="" v-else>
                <div class="form-group mb-4">
                    <label for="postback_name">Postback Name</label>
                    <input class="w-50" type="text" name="postback_name" id="postback_name" placeholder="Type Postback Name" autocomplete="off" v-model="form.postback_name" @keyup="has_error.postback_name=false">
                    <small class="error mt-1" v-if="has_error.postback_name">Postback Name is mandatory</small>
                </div>
                <div class="form-group mb-4">
                    <label for="postback_url">Postback URL <small>(*must be a POST method)</small> </label>
                    <input class="w-50" type="text" name="postback_url" id="postback_url" placeholder="Type Postback URL" autocomplete="off" v-model="form.postback_url" @keyup="isUrlValid">
                    <small class="error mt-1"></small>
                    <span  class="error-url mt-1" style="display: block" v-if="has_error.postback_url">URL Format is not valid. Ex : https://domain.com/api/callback</span>
                </div>
                <div class="form-group mb-6">
                    <div class="row mb-3 align-center justify-between">
                        <div class="col">
                            <label class="mb-0" for="postback_url">Parameters</label>
                        </div>
                        <div class="col">
                            <a href="javascript:void(0)" @click="add_field" class="btn btn-primary"><ion-icon name="add-outline"></ion-icon> Add Parameter</a>
                        </div>
                    </div>
                    <table class="table-1">
                        <thead>
                        <tr>
                            <th>Parameter Name</th>
                            <th>Get Value From</th>
                            <th>Set Fix Value (Optional)</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(param,index) in list_params">
                            <td>
                                <input type="text" placeholder="your parameter name" :class="param.has_error ? 'error w-100' : 'w-100'" :name="'param['+index+'][name]'" @keyup="add_param_name(index, param.param_name)" v-model="param.param_name">
                            </td>
                            <td>
                                <select :name="'param['+index+'][value]'" id="" class="w-100" v-model="param.param_value">
                                    <option v-for="param in param_options" :value="param">{{param}}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" placeholder="set the fix value" class="w-100" :name="'param['+index+'][default]'"  v-model="param.default_value">
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" class="icon-delete" @click="delete_param(index)">
                                    <ion-icon name="trash"></ion-icon>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <div class="d-flex align-center">
                        <div class="col mr-2">
                            <a @click="this.$router.go(-1)" class="btn btn-outline-primary d-flex align-center pl-4 pr-4">
                                <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Back
                            </a>
                        </div>
                        <div class="col">
                            <button type="button" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="update_postback">
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
export default {
    name: "PostbackEdit",
    components:{
        LoadingFetching
    },
    data(){
        return {
            has_callback : true,
            is_loading:true,
            is_submitting : false,
            list_params : [
                {
                    param_name : '',
                    param_value : '',
                    default_value : '',
                    has_error : false
                }
            ],
            param_options : [
                'trx_id',
                'campaign_id',
                'campaign_name',
                'full_name',
                'first_name',
                'last_name',
                'email',
                'phone',
                'dob',
                'company_name',
                'message',
                'referer',
                'clickid',
                'ip',
                'lat',
                'long',
                'tm',
                'android_id',
                'idfa',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_banner',
                'utm_term',
                'os',
                'browser',
                'device',
                'device_model',
                'sub1',
                'sub2',
                'sub3',
                'sub4',
                'sub5'
            ],
            has_error : {
                postback_url : false,
                postback_name : false,
            },
            form : {
                postback_code :'',
                postback_url : '',
                postback_name: ''
            }
        }
    },
    mounted() {
        utils.set_navigation('side_menu_postback');
        this.get();
    },
    methods:{
        get(){
            api.get_postback({postback_code : this.$route.params.code}).then(response=>{
                let obj = response.data.data;
                this.form.postback_code = obj.postback_code;
                this.form.postback_name =obj.postback_name;
                this.form.postback_url =obj.postback_url;
                this.form.postback_name = obj.postback_name;
                this.list_params = obj.param;
                this.is_loading = false;
            });
        },
        callback_change(event){
            if(event.target.value === ''){
                this.has_callback = true;
            }else{
                this.has_callback = false;
            }
        },
        add_field(){
            this.list_params.push({
                param_name : '',
                param_value : '',
                default_value : ''
            })
        },
        add_param_name(index, param_name){
            this.list_params[index].param_name = param_name;
            this.list_params[index].has_error =false;
        },
        delete_param(index){
            this.list_params.splice(index, 1);
        },
        isUrlValid() {
            let url = this.form.postback_url;
            this.has_error.postback_url = !/^(https):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
        },
        update_postback(){
            this.is_submitting = true;
            let arr_params = this.list_params;
            if(arr_params.length > 0){
                arr_params.forEach((value, index)=>{
                    if(value.param_name === ''){
                        // this.$toast.error('Error Found');
                        this.list_params[index].has_error = true;
                        return;
                    }
                    this.form.param = this.list_params;
                })
            }else{
                this.$toast.error('Please add at least 1 parameter.');
            }

            api.update_postback(this.form).then(response => {
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else if (res.code === 404){
                    this.$toast.warning(res.message);
                    this.is_submitting = false;
                }
                else{
                    this.$toast.success('Data saved successfully');
                    this.$router.push('/postback')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
