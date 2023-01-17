<template>
    <div>
        <div class="container pb-6">
            <div class="d-flex main">
                <div class="side-menu p-6"  v-if="is_online">
                    <a href="/" class="logo">
                        <img src="/images/logo_rec.png" alt="" width="150">
                    </a>
                    <ul class="mb-6">
                        <li>
                            <router-link to="/dashboard">Home</router-link>
                        </li>
                        <li><router-link id="side_menu_campaign" to="/campaign">Campaign</router-link></li>
                        <li><router-link id="side_menu_datatable" to="/datatable">Data Table</router-link></li>
                        <li><router-link id="side_menu_postback" to="/postback">Postback</router-link></li>
                        <li><router-link class="d-flex align-center" to="/notification">Notifications <span class="notification-count">9</span> </router-link></li>
                    </ul>
                    <ul class="mb-5">
                        <li v-if="user && user.role_id ==='administrator'"><router-link id="side_menu_company" to="/company">Company</router-link></li>
                        <li><router-link to="/setting/team">Team Management</router-link></li>
                        <li v-if="user && user.role_id ==='administrator'"><router-link id="side_menu_user" to="/user">User Management</router-link></li>
                        <li><router-link to="/setting/account">Account Setting</router-link></li>
                    </ul>
                    <ul>
                        <li><a href="javascript:void(0)" @click="logout">Logout</a></li>
                    </ul>
                </div>
                <div class="content">
                    <div v-if="is_online" class="top-menu">
                        <nav class="d-flex align-center justify-between">
                            <input class="search" type="search" placeholder="Search" name="search">
                            <div class="profile">
                                <div class="name" v-if="user">
                                    Welcome, {{split_name(this.user.name)}}
                                    <small>{{this.user.email}}</small>
                                </div>
                                <router-link to="/setting/account" v-if="user">
                                    <div class="img capitalize">{{get_first_character(this.user.name)}}</div>
                                </router-link>
                            </div>
                        </nav>
                    </div>
                    <router-view v-if="is_online" />
                    <div class="offline" v-else>
                        <img src="/images/offline.svg" alt="" width="250" class="mb-6">
                        <h4>Unable connect to the internet. Please check your connection.</h4>

                        <div class="mt-10">
                            <a @click="refresh" class="btn btn-primary"><img width="20" class="mr-1" src="/images/reset.png"
                                                                             alt=""> Refresh</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg"></div>
    </div>
</template>

<script>
import api from "../api";

export default {
    name: "Main",
    data(){
        return{
            is_online :navigator.onLine,
            user : null
        }
    },
    mounted() {
        api.get_user_login().then(response=>{
            this.user = response.data.data;
        })
    },
    methods:{
        logout(){
            api.logout().then(response=>{
                localStorage.removeItem('auth','true');
                this.$router.push('/login');
            })
        },
        refresh(){
            window.location.reload()
        },
        split_name(name){
            return name.split(' ')[0]
        },
        get_first_character(name){
            return name.charAt(0);
        }
    }
}
</script>

<style scoped>

</style>
