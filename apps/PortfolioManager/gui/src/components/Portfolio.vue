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
        <table v-if="orders && orders.length">
            <tr>
                <th>Order ID</th>
                <th>Allocation</th>
                <th>Type</th>
                <th>Shares</th>
                <th></th>
            </tr>
            <tr v-for="order in orders" :key="order.id">
                <td>{{ order.id }}</td>
                <td>{{ order.allocation }}</td>
                <td>{{ order.type }}</td>
                <td>{{ order.shares }}</td>
                <td><button @click="completeOrder(order)">Complete order</button></td>
            </tr>
        </table>
        <span v-else>There are no pending orders</span>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const allocations = computed(() => store.getters.allocations);
const orders = computed(() => store.getters.orders);

const completeOrder = (order) => {
    store.dispatch('completeOrder', order);
}

</script>
