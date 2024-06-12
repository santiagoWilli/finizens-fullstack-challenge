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
        <Error :message="error" />
        <h2>New order</h2>
        <NewOrder />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';

import NewOrder from './NewOrder.vue';
import Error from './Error.vue';

const store = useStore();
const allocations = computed(() => store.getters.allocations);
const orders = computed(() => store.getters.orders);
let error = ref("");

const completeOrder = (order) => {
    store.dispatch('completeOrder', order)
        .then(() => {
            error.value = "";
        })
        .catch(({ response }) => {
            switch (response.status) {
                case 404:
                    error.value = "Cannot complete a sell order for an allocation that does not currently exist";
                    break;
                case 409:
                    error.value = "Cannot complete a sell order that would leave the allocation with a negative shares amount";
                    break;
            }
        });
}
</script>
