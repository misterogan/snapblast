<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Create Company
                </h1>
            </div>
            <div>
                <router-link to="/company" class="btn btn-outline-primary"><ion-icon name="list-outline" class="mr-1"></ion-icon> Company List</router-link>
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <form action="">
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
                            <label for="category_id">Category</label>
                            <CategoryList name="category_id" id="category_id" class="w-100"  v-model="form.category_id"></CategoryList>
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
                                <router-link class="btn btn-outline-primary d-flex align-center pl-4 pr-4" to="/company">
                                    <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Back
                                </router-link>
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
import utils from '../utils'
import api from "../api";
import CategoryList from "./CategoryList";

export default {
    name: "CompanyCreate",
    components : {
        CategoryList
    },
    data(){
        return {
            has_callback : true,
            is_loading:false,
            form : {
                company_name:'',
                email : '',
                phone_number :'',
                pic_name :'',
                category_id : '1'
            }
        }
    },
    mounted() {
        utils.set_navigation('side_menu_company');
    },
    methods:{
        submit(){
            this.is_loading = true;
            api.submit_company(this.form).then(response => {
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_loading = false;
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
