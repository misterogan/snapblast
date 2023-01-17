<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    List of Companies
                </h1>
            </div>
            <div>
                <router-link to="/company/create" class="btn btn-primary"><ion-icon name="add-outline"></ion-icon> Add Company</router-link>
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <table id="dt_companies" class="display w-100">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>PIC</th>
                    <th class="text-center">Total Campaign</th>
                    <th class="text-center">Total User</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>DF3S44FG</td>
                    <td><span class="badge green">Active</span></td>
                    <td>
                        PT Cashtree For Indonesia
                    </td>
                    <td>sales@cashtree.id</td>
                    <td>0813141408745</td>
                    <td>Agust</td>
                    <td class="text-center">3</td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                        <router-link to="/company/edit/1">Edit</router-link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import utils from "../utils";
export default {
    name: "Company",
    data(){
        return{

        }
    },
    mounted() {
        this.init_datatable();

    },
    methods:{
        init_datatable(){
            const self = this

            $('#dt_companies').DataTable(
                {
                    processing: true,
                    ajax:  {
                        url: "/api/company/data-table/list",
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
                        { data: 'company_code' },
                        { data: 'row_status',
                            render : function (data){
                                return '<span class="badge '+data +' capitalize">'+data+'</span>';
                            }
                        },
                        { data: 'company_name' },
                        { data: 'email' },
                        { data: 'phone_number' },
                        { data: 'pic_name' },
                        { data: 'campaign_count',class: 'text-right',
                            render:function(data){
                                return utils.to_number(data);
                            }
                        },
                        { data: 'user_count',class: 'text-right',
                            render:function(data){
                                return utils.to_number(data);
                            }
                        },
                        { data: 'company_code',class: 'text-center',
                            render:function(data){
                                return '<a class="mr-2 edit-data" href="javascript:void(0)" data-code="'+data+'">Edit</a>';
                            }
                        },
                    ]
                }
            );

            let table = $("#dt_companies");
            $('tbody', table).on( 'click', '.edit-data', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.edit_data(cell)
            });
        },
        edit_data(code){
            this.$router.push('/company/edit/'+code);
        }
    }
}
</script>

<style scoped>

</style>
