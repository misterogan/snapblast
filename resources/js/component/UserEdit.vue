<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Edit User
                </h1>
            </div>
            <div class="top-action">
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <LoadingFetching v-if="is_loading"></LoadingFetching>
            <form action="" v-else>
                <div class="row mb-5" v-if="user.row_status === 'pending'">
                    <span class="badge pending p-1 pl-2 pr-2" style="font-size: 0.9rem">Pending Invitation</span>
                </div>
                <div class="row mb-5">
                    <div class="col-4 pr-2">
                        <div class="form-group mb-4">
                            <label for="name">Name*</label>
                            <input class="w-100" type="text" name="name" id="name" placeholder="Type a Name" autocomplete="off" v-model="form.name">
                            <small class="error mt-1 hide"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email*</label>
                            <input class="w-100" type="email" name="email" id="email" placeholder="Type Email" autocomplete="off" v-model="form.email_view" disabled>
                            <small class="error mt-1 hide"></small>
                        </div>
                    </div>
                    <div class="col-4 pl-2">
                        <div class="form-group mb-4" v-if="user_role==='administrator'">
                            <label for="company_code">Company Name*</label>
                            <CompanyList name="company_code" id="company_code" v-model="form.company_code"></CompanyList>
                            <small class="error mt-1 hide"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="role_id">Role Name*</label>
                            <select name="role_id" id="role_id" class="w-100" v-model="form.role_id">
                                <option value="administrator">Administrator</option>
                                <option value="manager">Manager</option>
                                <option value="member">Team Member</option>
                            </select>
                            <small class="error mt-1 hide"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="d-flex align-center">
                            <div class="col mr-2">
                                <a @click="this.$router.go(-1)" class="btn btn-outline-primary d-flex align-center pl-4 pr-4">
                                    <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Back
                                </a>
                            </div>
                            <div class="col mr-2" v-if="user.row_status === 'pending'">
                                <a @click="copy_invitation" class="btn btn-outline-primary d-flex align-center pl-4 pr-4">
                                    <ion-icon class="mr-1" name="copy-outline"></ion-icon> Copy Invitation
                                </a>
                            </div>
                            <div class="col">
                                <button type="button" :disabled="is_loading" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="update">
                                    <span class="text">Update</span>
                                    <span class="loading"><span class="icon"></span> Saving</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import utils from '../utils'
import CompanyList from "./CompanyList";
import api from "../api";
import LoadingFetching from "./LoadingFetching";

export default {
    name: "UserEdit",
    data(){
        return {
            is_loading:true,
            is_submitting : false,
            user_role :'',
            user : null,
            form : {
                name:'',
                email : '',
                role_id :'',
                company_code :'',
                email_view : ''
            }
        }
    },
    components:{
        CompanyList,
        LoadingFetching
    },
    mounted() {
        utils.set_navigation('side_menu_user');
        api.get_user({email : this.$route.params.email}).then(response => {
            let res = response.data;
            if (res.code === 200){
                let obj = res.data;
                this.user = obj.user;
                this.form.name = obj.user.name;
                this.form.email = obj.user.email;
                this.form.email_view = obj.user.email;
                this.form.role_id = obj.user.role_id;
                this.form.company_code = obj.user.company.company_code;
                this.user_role = obj.user_role
                this.is_loading = false;
            }else{
                this.$toast.error('Error');
                // this.$router.push('/campaign')
            }
        })
    },
    methods:{
        update(){
            this.is_submitting = true;
            api.update_user(this.form).then(response => {
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else{
                    this.$toast.success('Data saved successfully');
                    this.$router.push('/user')
                }
            })
        },
        copy_invitation(){
            let url = 'http://127.0.0.1:8000/invitation/'+ this.user.invitation_token;
            navigator.clipboard.writeText(url);
            this.$toast.default('Invitation Copied');
        }
    }
}
</script>

<style scoped>

</style>
