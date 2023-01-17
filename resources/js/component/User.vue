<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    User Management
                </h1>
            </div>
            <div>
                <router-link to="/user/create" class="btn btn-primary"><ion-icon name="add-outline"></ion-icon> Add New User</router-link>
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <table id="dt_users" class="display w-100">
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
</template>

<script>
import utils from "../utils";
export default {
    name: "User",
    data(){
        return{

        }
    },
    mounted() {
        this.init_datatable()
    },
    methods:{
        init_datatable(){
            const self = this

            $('#dt_users').DataTable(
                {
                    processing: true,
                    ajax:  {
                        url: "/api/user/data-table/list",
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
                                return '<span class="badge '+data +' capitalize">'+data+'</span>';
                            }
                        },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'company.company_name' },
                        { data: 'role', class:'capitalize',
                            render:function (data){
                                return '<span class="dot '+data +' capitalize">'+data+'</span>';
                            }
                        },
                        { data: 'email',class: 'text-center',
                            render:function(data){
                                return '<a data-code="'+data+'" class="mr-2 edit-data" href="javascript:void(0)">Edit</a>';
                            }
                        },
                    ]
                }
            );

            let table = $("#dt_users");
            $('tbody', table).on( 'click', '.edit-data', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.edit_data(cell)
            });
        },
        edit_data(code){
            this.$router.push('/user/edit/'+code);
        }
    }
}
</script>

<style scoped>
@import 'datatables.net-dt';
</style>
