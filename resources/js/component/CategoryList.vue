<template>
    <select v-if="!loading" class="w-100" :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)">
        <option v-for="(l, i) in list" :value="l.code">{{l.value}}</option>
    </select>
    <select v-else class="w-100">
        <option>Loading ...</option>
    </select>
</template>

<script>
import api from '../api'
export default {
    name: "CategoryList",
    props: ['modelValue'],
    emits: ['update:modelValue'],
    data(){
        return{
            list : [],
            loading : true
        }
    },
    mounted() {
        api.get_list_of_categories().then(response => {
            this.list = response.data.data;
            this.loading = false
            if(response.data.data.length > 0){
                // this.$emit('update:modelValue', response.data.data[0].code)
            }
        });
    }
}
</script>

<style scoped>

</style>
