<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Edit Company
                </h1>
            </div>
            <div>
                <router-link to="/company" class="btn btn-outline-primary"><ion-icon name="list-outline" class="mr-1"></ion-icon> Company List</router-link>
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <LoadingFetching v-if="is_loading"></LoadingFetching>
            <form action="" v-else>
                <div class="row mb-5">
                    <div class="col-4 pr-2">
                        <div class="form-group mb-4">
                            <label for="company_name">Company Name</label>
                            <input class="w-100" type="text" name="company_name" id="company_name" placeholder="Type a Company Name" autocomplete="off" v-model="form.company_name">
                            <small class="error mt-1 hide"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email</label>
                            <input class="w-100" type="email" name="email" id="email" placeholder="Type Email" autocomplete="off" v-model="form.email">
                            <small class="error mt-1 hide"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="category_code">Category</label>
                            <CategoryList name="category_code" id="category_code" class="w-100"  v-model="form.category_code"></CategoryList>
                            <small class="error mt-1 hide"></small>
                        </div>
                    </div>
                    <div class="col-4 pl-2">
                        <div class="form-group mb-4">
                            <label for="pic_name">PIC Name</label>
                            <input class="w-100" type="text" name="pic_name" id="pic_name" placeholder="Type a PIC Name" autocomplete="off" v-model="form.pic_name">
                            <small class="error mt-1 hide"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="phone_number">Phone</label>
                            <input class="w-100" type="text" name="phone_number" id="phone_number" placeholder="Type Phone Number" autocomplete="off" v-model="form.phone_number">
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
import api from '../api'
import utils from '../utils'
import CategoryList from "./CategoryList";
import LoadingFetching from "./LoadingFetching";

export default {
    name: "CompanyEdit",
    components:{
        CategoryList,
        LoadingFetching
    },
    data(){
        return {
            is_loading:true,
            is_submitting : false,
            form : {
                company_code: '',
                company_name:'',
                email : '',
                phone_number :'',
                pic_name :'',
                category_code : '',
                api_token :''
            }
        }
    },
    mounted() {
        utils.set_navigation('side_menu_company');
        this.get_data();
    },
    methods:{
        get_data(){
            api.get_company_by_code({company_code : this.$route.params.code}).then(response=>{
                let res = response.data;
                if (res.code === 200){
                    let obj = res.data;
                    this.form.company_code = obj.company_code;
                    this.form.company_name = obj.company_name;
                    this.form.email = obj.email;
                    this.form.pic_name = obj.pic_name;
                    this.form.phone_number = obj.phone_number;
                    this.form.category_code = obj.category_code;
                    this.form.api_token = obj.api_token;
                    this.is_loading = false;
                }else{
                    this.$toast.error('Error');
                }
            });
        },
        update(){
            this.is_submitting = true;
            api.update_company(this.form).then(response => {
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else{
                    this.$toast.success('Data saved successfully');
                    this.$router.push('/company')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
