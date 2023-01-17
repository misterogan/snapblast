<template>
    <router-view></router-view>
</template>

<script>
import api from "../api";

export default {
    name: "App",
    data(){
        return{
            is_online : navigator.onLine
        }
    }
}
</script>

<style scoped>

</style>
