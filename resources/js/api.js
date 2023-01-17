import base from './apis/base'

export default{
    login(form){
        return base.post('/api/user/login', form);
    },
    logout(form){
        return base.post('/api/user/logout', form);
    },
    is_login(){
        return localStorage.getItem('auth');
    },
    get_user_login(){
        return base.get('/api/user')
    },
    get_user_info(){
        return base.post('/api/user/info')
    },
    submit_campaign(form){
        return base.post('/api/campaign/submit', form)
    },
    update_campaign(form){
        return base.post('/api/campaign/update', form)
    },
    get_campaign(form){
        return base.post('/api/campaign/get', form)
    },
    get_campaign_datatable(form){
        return base.get('api/campaign/data-table/list', form)
    },
    get_lead_data_table(form) {
        return base.get('/api/lead/data-table/list', form);
    },
    get_detail_data_table(form){
        return base.post('/api/lead/data-table/detail', form);
    },
    get_lead_data_histories(form) {
        return base.post('/api/lead/data-table/histories', form);
    },
    assign_lead(form){
        return base.post('/api/lead/data-table/assign', form);
    },
    submit_company(form){
        return base.post('/api/company/submit', form)
    },
    update_company(form){
        return base.post('/api/company/update', form)
    },
    get_company_by_code(form){
        return base.post('/api/company/get', form)
    },
    get_list_of_companies(){
        return base.get('/api/company/list')
    },
    get_list_of_campaigns(){
        return base.post('/api/campaign/list')
    },
    submit_user(form){
        return base.post('/api/user/submit', form)
    },
    update_user(form){
        return base.post('/api/user/update', form)
    },
    get_user(email){
        return base.post('/api/user/get', email)
    },
    change_password(form){
        return base.post('/api/user/password/change', form)
    },
    get_user_list(form){
        return base.post('/api/user/list/item', form)
    },
    get_list_of_categories(param){
        return base.get('/api/category/list', param)
    },
    update_lead_status(form){
        return base.post('/api/lead/status/update', form)
    },
    submit_postback(form){
        return base.post('/api/postback/submit', form)
    },
    update_postback(form){
        return base.post('/api/postback/update', form)
    },
    get_postback_list(form){
        return base.post('/api/postback/list/item', form)
    },
    get_postback(form){
        return base.post('/api/postback/edit', form)
    },
    send_invitation(form){
        return base.post('/api/user/invite', form)
    },
    validate_invitation(form){
        return base.post('/api/user/invite/validate', form)
    },
    confirm_invitation(form){
        return base.post('/api/user/invite/confirm', form)
    }
}
