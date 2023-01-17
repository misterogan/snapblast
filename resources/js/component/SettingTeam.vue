<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Team Management
                </h1>
            </div>
            <div class="top-action">
                <a @click="show_modal=true" href="javascript:void(0)" class="btn btn-primary"><ion-icon name="add-outline"></ion-icon> Add Member</a>
            </div>
        </div>
        <div class="pl-6 pr-6 pb-6">
            <table id="dt_team_management" class="display w-100">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Company Name</th>
                    <th>Role</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <Teleport to="body">
        <modal class="md" :show="show_modal" @close="show_modal = false">
            <template #header>
                <h3>{{modal.title}}</h3>
            </template>
            <template #body>
                <div class="form-group mb-2">
                    <label for="">Name</label>
                    <input name="email" id="name" type="text" class="w-100" placeholder="input the name" v-model="form.name">
                    <small class="error mt-1"></small>
                </div>
                <div class="form-group mb-2">
                    <label for="">Email</label>
                    <input name="email" id="email" type="text" class="w-100" placeholder="input the email" v-model="form.email">
                    <small class="error mt-1"></small>
                </div>
                <div class="form-group mb-6">
                    <label for="">Role</label>
                    <select name="role_id" id="role_id" class="w-100" v-model="form.role_id">
                        <option value="manager">Manager</option>
                        <option value="member">Team Member</option>
                    </select>
                    <small class="error mt-1"></small>
                </div>
            </template>
            <template #footer>
                <div class="row justify-end">
                    <button type="button" :class="is_submitting?'btn btn-primary pl-4 pr-4 submitting':'btn btn-primary pl-4 pr-4'" @click="send_invitation">
                        <span class="text">Send Invitation</span>
                        <span class="loading"><span class="icon"></span> Sending...</span>
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
import utils from "../utils";
import api from "../api";
export default {
    name: "SettingGeneral",
    data(){
        return{
            is_submitting :false,
            is_loading:true,
            show_modal: false,
            modal : {
                title : 'Invite New Member :'
            },
            form : {
                name :'',
                email : '',
                role_id : ''
            }
        }
    },
    components:{
        Modal
    },
    mounted() {
        this.init_datatable();
    },
    methods:{
        init_datatable(){
            const self = this
            $('#dt_team_management').DataTable(
                {
                    processing: true,
                    ajax:  {
                        url: "/api/team-management/list",
                        error: function (xhr, error, code) {
                            if(xhr.status === 401){
                                self.$toast.error('Session has been expired');
                                setTimeout(function (){
                                    self.$router.push({
                                        name: 'Login',
                                        query : {
                                            'redirect' : self.$route.fullPath
                                        }
                                    });
                                }, 1000)
                            }
                        }
                    },
                    columns : [
                        { data: 'DT_RowIndex', class:'text-center'},
                        { data: 'row_status',
                            render : function (data){
                                let text = data === 'pending' ? 'pending invitation' : data;
                                return '<span class="badge '+data +' capitalize">'+text+'</span>';
                            }
                        },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'company.company_name' },
                        { data: 'role' , class:'capitalize'},
                        { data: 'email',class: 'text-center',
                            render:function(data){
                                return '<a data-code="'+data+'" class="mr-2 edit-data" href="javascript:void(0)">Edit</a>';
                            }
                        },
                    ]
                }
            );
            let table = $("#dt_team_management");
            $('tbody', table).on( 'click', '.edit-data', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.edit_data(cell)
            });
        },
        edit_data(code){
            this.$router.push('/user/edit/'+code);
        },
        add_member(){
            this.show_modal = true
        },
        send_invitation(){
            this.is_submitting = true
            api.send_invitation(this.form).then(response=>{
                let res = response.data;
                if (res.code === 201){
                    $.each(res.data, function (index, value) {
                        $("#"+index).next('small').removeClass('hide').html(value[0]);
                    });
                    this.$toast.warning('Please fill in the mandatory field');
                    this.is_submitting = false;
                }else{
                    this.$toast.success('Data saved successfully');
                    this.show_modal = false;
                    this.is_submitting = false;
                    this.form.name = '';
                    this.form.email = '';
                    this.form.role_id = '';
                    $('#dt_team_management').DataTable().ajax.reload();
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
