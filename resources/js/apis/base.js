import axios from 'axios';

let base = axios.create();

base.defaults.withCredentials = true;
base.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
base.defaults.headers.common['Accept'] = 'application/json';
base.interceptors.response.use(response => {
    return response;
}, error => {
    const self = this
    console.log('ma');
    if (error.response.status === 401) {
        localStorage.removeItem('auth');
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
    return error.response;
});


export default base
