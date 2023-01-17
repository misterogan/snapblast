<template>
    <div>
        <div class="login-form-header text-center mt-8 mb-8">
            <img src="/images/logo_rec.png" alt="" width="200">
        </div>
        <div class="login-form shadow rounded">
            <form action="">
                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" class="w-100" placeholder="your@email.com" autocomplete="off" v-model="form.email">
                    <small class="error mt-1 hide"></small>
                </div>
                <div class="form-group mb-7">
                    <label for="password">Password</label>
                    <input type="password" name="password"  id="password" class="w-100" placeholder="your password" autocomplete="false" v-model="form.password">
                    <small class="error mt-1 hide"></small>
                </div>
                <div class="form-group">
                    <button type="button" :class="is_submitting?'btn btn-primary pl-4 pr-4 w-100 submitting':'btn btn-primary pl-4 pr-4 w-100'" @click="sign_in">
                        <span class="text">Sign In</span>
                        <span class="loading"><span class="icon"></span> Signing ...</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="bg">
            Snapblast by Cashtree For Indonesia
        </div>
    </div>
</template>

<script>
import api from '../api'
export default {
    name: "Login",
    data(){
        return{
            is_submitting:false,
            form:{
                email:'',
                password:''
            }
        }
    },
    methods:{
        sign_in(){
            this.is_submitting = true;
            api.login(this.form).then(response=>{
                let res = response.data;
                if(res.code === 200){
                    localStorage.setItem('auth','true');
                    // localStorage.setItem('token',res.data.token);
                    if(this.$route.query.redirect){
                        this.$router.push(this.$route.query.redirect);
                    }else{
                        this.$router.push('/dashboard');
                    }
                }else{
                    if(res.code === 201){
                        $.each(res.data, function (index, value) {
                            $("#"+index).next('small').removeClass('hide').html(value[0]);
                        });
                        this.$toast.warning('Please fill in the mandatory field');
                        this.is_submitting = false;
                    }
                    if(res.code === 401){
                        $("#password").next('small').removeClass('hide').html('User & password doesn\'t match or the user not exist');
                        this.$toast.warning('Login failed!');
                        this.is_submitting = false;
                    }
                }
            })
            // this.$router.push('/dashboard')
        }
    }
}
</script>

<style scoped>

</style>
