<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Account Setting
                </h1>
            </div>
            <div class="top-action"></div>
        </div>
        <LoadingFetching class="pl-6 pr-6" v-if="is_loading"></LoadingFetching>
        <div v-if="user_info" class="pl-6 pr-6 pb-6">
            <div class="row mb-5">
                <div class="col-6">
                    <div class="campaign-detail">
                        <div class="mb-3">
                            <span class="title">Name</span>
                            <span class="value">{{user_info.name}}</span>
                        </div>
                        <div class="mb-3">
                            <span class="title">Email</span>
                            <span class="value">{{user_info.email}}</span>
                        </div>
                        <div class="mb-3">
                            <span class="title">Company Name</span>
                            <span class="value">{{user_info.company.company_name}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="campaign-detail">
                        <div class="mb-3">
                            <span class="title">Role</span>
                            <span class="value capitalize">{{user_info.role_id}}</span>
                        </div>
                        <div class="mb-3">
                            <span class="title">Joined Date</span>
                            <span class="value">{{user_info.join_date}}</span>
                        </div>
                        <div class="mb-3" v-if="user_info.role_id === 'administrator'">
                            <span class="title">API Token</span>
                            <span class="value d-flex align-center">{{user_info.company.api_token}} <span class="ml-2" style="width: 80px;"><a @click="copy_token(user_info.company.api_token)" class="btn btn-outline-primary btn-sm"><ion-icon class="mr-1" name="copy-outline"></ion-icon> Copy</a></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="d-flex">
                        <a href="javascript:void(0)" class="btn btn-primary mr-2" @click="show_modal=true"><img class="mr-1" width="22" src="/images/logout.png"
                                                                                                                alt=""> Change Password</a>
                        <a class="btn btn-outline-primary" @click="logout">Logout</a>
                    </div>
                </div>
            </div>
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
                        <label for="">Old Password</label>
                        <input type="password" class="w-100" name="old_password" id="old_password" placeholder="Input the old password" autocomplete="off" v-model="form.old_password">
                        <small class="error mt-1"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">New Password</label>
                        <input type="password" class="w-100" name="password" id="password" placeholder="Input the new password" autocomplete="off" v-model="form.password">
                        <small class="error mt-1"></small>
                    </div>
                    <div class="form-group mb-6">
                        <label for="">Password Confirmation</label>
                        <input type="password" class="w-100" name="password_confirmation" id="password_confirmation" placeholder="Input the password confirmation" autocomplete="off" v-model="form.password_confirmation">
                        <small class="error mt-1"></small>
                    </div>
                </form>
            </template>
            <template #footer>
                <div class="row justify-end">
                    <button type="button" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="change_password">
                        <span class="text">Change Password</span>
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
import LoadingFetching from './LoadingFetching'
import api from '../api'
export default {
    name: "SettingAccount",
    data(){
        return{
            is_submitting : false,
            is_loading:true,
            show_modal: false,
            modal : {
                title : 'Change Password :'
            },
            user_info : null,
            form :{
                old_password:'',
                password:'',
                password_confirmation:''
            }
        }
    },
    components:{
        Modal,
        LoadingFetching
    },
    mounted() {
        api.get_user_info().then(response=>{
            let obj = response.data;
            this.user_info = obj.data;
            this.is_loading = false;
        });
    },
    methods:{
        add_member(){
            this.show_modal = true
        },
        change_password(){
            this.is_submitting = true;
            api.change_password(this.form).then(response=>{
                let obj = response.data;
                if(obj.code === 201){
                    $.each(obj.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else if(obj.code === 401){
                    this.$toast.warning('Please fill in the mandatory field');
                }else{
                    this.$toast.success('Data saved successfully');
                    this.show_modal = false;
                }
            });
        },
        copy_token(token){
            navigator.clipboard.writeText(token);
            this.$toast.default('API Token Copied');
        },
        logout(){
            api.logout().then(response=>{
                localStorage.removeItem('auth','true');
                this.$router.push('/login');
            })
        }
    }
}
</script>

<style scoped>

</style>
