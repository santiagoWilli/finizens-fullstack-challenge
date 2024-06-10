<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>

<script setup>
import { onBeforeMount } from 'vue';
import { useStore } from 'vuex';

onBeforeMount(async () => {
    const store = useStore();
    store.dispatch('fetchPortfolio');
});
</script>

<style>
#app {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
}
</style>
