<template>
    <div>
        <h1>Portfolio</h1>
        <h2>Allocations</h2>
        <ul>
            <li v-for="allocation in allocations" :key="allocation.id">
                {{ allocation.id }}: {{ allocation.shares }} shares
            </li>
        </ul>
        <h2>Pending orders</h2>
        <ul>
            <li v-for="order in orders" :key="order.id">
                allocation {{ order.allocation }}: {{ order.type }} {{ order.shares }} shares
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const allocations = computed(() => store.getters.allocations);
const orders = computed(() => store.getters.orders);
</script>
