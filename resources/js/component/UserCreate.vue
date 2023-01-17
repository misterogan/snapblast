<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Create User
                </h1>
            </div>
            <div class="top-action">
<!--                <router-link to="/user" class="btn btn-outline-primary">Users List</router-link>-->
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <form action="">
                <div class="row mb-5">
                    <div class="col-4 pr-2">
                        <div class="form-group mb-4">
                            <label for="name">Name*</label>
                            <input class="w-100" type="text" name="name" id="name" placeholder="Type a Name" autocomplete="off" v-model="form.name">
                            <small class="error mt-1 hide"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email*</label>
                            <input class="w-100" type="email" name="email" id="email" placeholder="Type Email" autocomplete="off" v-model="form.email">
                            <small class="error mt-1 hide"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Password*</label>
                            <input class="w-100" type="email" name="password" id="password" placeholder="Type Password" autocomplete="off" v-model="form.password">
                            <small class="error mt-1 hide"></small>
                        </div>
                    </div>
                    <div class="col-4 pl-2">
                        <div class="form-group mb-4">
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
                            <div class="col">
                                <button type="button" :class="is_loading?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="submit">
                                    <span class="text">Submit</span>
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
import api from '../api'
import utils from '../utils'
import CompanyList from "./CompanyList";
export default {
    name: "UserCreate",
    data(){
        return {
            is_loading:false,
            form : {
                name:'',
                email : '',
                role_id :'manager',
                company_code :'',
                password :''
            }
        }
    },
    components:{
        CompanyList
    },
    mounted() {
        utils.set_navigation('side_menu_user');
    },
    methods:{
        submit(){
            this.is_loading = true;
            api.submit_user(this.form).then(response => {
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_loading = false;
                }else{
                    this.$toast.success('Data saved successfully');
                    this.$router.push('/user')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
