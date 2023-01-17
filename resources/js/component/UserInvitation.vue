<template>
    <div class="d-flex align-center justify-center flex-column h-100">
        <img src="/images/logo_rec.png" alt="" width="200" class="mb-5">
        <div v-if="is_valid">
            <h4 class="text-center">Welcome <span style="color: #00c2d6;">{{name}}</span>, please complete your password in form below :</h4>
            <div style="width: 500px" class="p-6 m-auto">
                <div class="form-group">
                    <div class="form-group mb-2">
                        <label for="name">Name</label>
                        <input name="email" id="name" type="text" class="w-100" placeholder="input your name" v-model="form.name" autocomplete="off">
                        <small class="error mt-1"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password">Password</label>
                        <input name="password" id="password" type="password" class="w-100" placeholder="input your password" v-model="form.password">
                        <small class="error mt-1"></small>
                    </div>
                    <div class="form-group mb-6">
                        <label for="password_confirmation">Password Confirmation</label>
                        <input name="password" id="password_confirmation" type="password" class="w-100" placeholder="input your password" v-model="form.password_confirmation">
                        <small class="error mt-1"></small>
                    </div>
                    <div class="form-group mb-2">
                        <button type="button" :disabled="is_submitting" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting w-100':'btn btn-primary pl-4 pr-4 w-100'" @click="submit">
                            <span class="text">Confirm</span>
                            <span class="loading"><span class="icon"></span> Saving ...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="is_broken">
            <h4 class="text-center">The requested URL was not found on this invitation.</h4>
        </div>
        <div v-if="is_expired">
            <h4 class="text-center">Your invitation link was expired</h4>
        </div>
        <div class="bg">
            Snapblast by Cashtree For Indonesia
        </div>
    </div>
</template>

<script>
import api from "../api";

export default {
    name: "UserInvitation",
    data(){
        return{
            name :'',
            is_submitting : false,
            is_loading : true,
            is_expired : false,
            is_valid : false,
            is_broken : false,
            form :{
                invitation_token : '',
                name : '',
                password : '',
                password_confirmation : ''
            }
        }
    },
    mounted() {
        api.validate_invitation({invitation_token:this.$route.params.invitation_token}).then(response=>{
            let obj = response.data;
            if(obj.code === 200){
                this.name = obj.data.name;
                this.form.name = obj.data.name;
                this.is_loading = false;
                this.form.invitation_token = this.$route.params.invitation_token;
                this.is_valid = true;
            }else if(obj.code === 404){
                this.is_broken=true
                this.is_loading = false;
            }else if(obj.code === 202){
                this.is_expired=true
                this.is_loading = false;
            }
        })
    },
    methods:{
        submit(){
            this.is_submitting = true;
            api.confirm_invitation(this.form).then(response=>{
               let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else {
                    this.$toast.success('Data has been changed successfully');
                    this.$router.push('/dasboard')
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
