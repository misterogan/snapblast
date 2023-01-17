<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Campaign
                </h1>
            </div>
            <div>
                <router-link to="/campaign/create" class="btn btn-primary"><ion-icon name="add-outline"></ion-icon> Add Campaign</router-link>
            </div>
        </div>
        <div class="rounded pl-6 pr-6">
            <table id="list_campaign" class="display w-100" v-once>
                <thead>
                <tr>
                    <th>No</th>
                    <th>AD ID</th>
                    <th>Status</th>
                    <th>Campaign Name</th>
                    <th class="text-center">Target Leads</th>
                    <th class="text-center">Total Leads</th>
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
import utils from '../utils'
import api from "../api";

export default {
    name: "Campaign",
    data(){
        return{
            param1:'',
            dataSource : null,
        }
    },
    mounted(){
        this.init_datatable();
    },
    methods:{
        init_datatable(){
            const self = this
            $('#list_campaign').DataTable(
                {
                    processing: true,
                    ajax:  {
                        url: "/api/campaign/data-table/list",
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
                        { data: 'ad_id' },
                        { data: 'row_status',
                            render : function (data){
                                return '<span class="badge '+data +' capitalize">'+data+'</span>';
                            }
                        },
                        { data: 'campaign_name' },
                        { data: 'target_lead',class: 'text-right',
                            render:function(data){
                                return utils.to_number(data);
                            }
                        },
                        { data: 'leads_count',class: 'text-right',
                            render:function(data){
                                return utils.to_number(data);
                            }
                        },
                        { data: 'ad_id',class: 'text-center',
                            render:function(data){
                                return '<a data-code="'+data+'" class="mr-2 edit_campaign" href="javascript:void(0)">Edit</a><a class="open_detail_data" data-code="'+data+'" href="javascript:void(0)">Detail</a>';
                            }
                        },
                    ]
                }
            );
            let table = $("#list_campaign");
            $('tbody', table).on( 'click', '.open_detail_data', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.open_detail_data(cell)
            });
            $('tbody', table).on( 'click', '.edit_campaign', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.edit_campaign(cell)
            });
        },
        open_detail_data(ad_id){
            this.$router.push('/campaign/detail/'+ad_id);
        },
        edit_campaign(ad_id){
            this.$router.push('/campaign/edit/'+ad_id);
        }
    }
}
</script>

<style scoped>
@import 'datatables.net-dt';
</style>
