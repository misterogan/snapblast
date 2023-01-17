<template>
    <select v-if="!loading" class="w-100" :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)">
        <option value="">Select One</option>
        <option v-for="(l, i) in list" :value="l.postback_code">{{l.postback_name}}</option>
    </select>
    <select v-else class="w-100">
        <option>Loading ...</option>
    </select>
</template>

<script>
import api from '../api'
export default {
    name: "PostbackList",
    props: ['modelValue'],
    emits: ['update:modelValue'],
    data(){
        return{
            list : [],
            loading : true
        }
    },
    mounted() {
        api.get_postback_list().then(response => {
            this.list = response.data.data;
            this.loading = false
            if(response.data.data.length > 0){
                // this.$emit('update:modelValue', response.data.data[0].email)
            }
        });
    }
}
</script>

<style scoped>

</style>

