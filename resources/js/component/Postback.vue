<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Postback
                </h1>
            </div>
            <div class="top-action">
                <router-link to="/postback/create" class="btn btn-primary"><ion-icon name="add-outline"></ion-icon> Add Postback</router-link>
            </div>
        </div>

        <div class="pl-6 pr-6 pb-6 w-100">
            <table id="dt_postback" class="display w-100" v-once>
                <thead>
                <tr>
                    <th class="text-center">Code</th>
                    <th>Status</th>
                    <th>Postback Name</th>
                    <th>URL</th>
                    <th>Company Name</th>
                    <th class="text-center">Used</th>
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

export default {
    name: "Postback",
    data(){
        return{

        }
    },
    mounted(){
        this.init_datatable()
    },
    methods:{
        init_datatable(){
            const self = this

            $('#dt_postback').DataTable(
                {
                    processing: true,
                    ajax:  {
                        url: "/api/postback/data-table/list",
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
                        { data: 'postback_code', class:'text-center'},
                        { data: 'row_status',
                            render : function (data){
                                return '<span class="badge '+data +' capitalize">'+data+'</span>';
                            }
                        },
                        { data: 'postback_name' },
                        { data: 'postback_url' },
                        { data: 'company.company_name' },
                        { data: 'campaign_count', class:'text-center' },
                        { data: 'postback_code',class: 'text-center',
                            render:function(data){
                                return '<a data-code="'+data+'" class="edit-data" href="javascript:void(0)">Edit</a>';
                            }
                        },
                    ]
                }
            );

            let table = $("#dt_postback");
            $('tbody', table).on( 'click', '.edit-data', function(){
                const cell = $(this).closest("td a").attr('data-code');
                self.edit_data(cell)
            });
        },
        edit_data(code){
            this.$router.push('/postback/edit/'+code);
        }
    }
}
</script>

<style scoped>

</style>
