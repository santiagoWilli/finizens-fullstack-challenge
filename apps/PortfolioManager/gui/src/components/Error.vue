<template>
    <span v-if="message" class="errorMsg">{{ message }}</span>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    message: String
});
</script>

<style scoped>
.errorMsg {
    color: red;
}
</style>