import Vue from 'vue';

Vue.filter("NumberFormat",  function(value)  {
    if(isNaN(value)){
        return '0';
    }
    let val = (value/1).toFixed(0).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
});

