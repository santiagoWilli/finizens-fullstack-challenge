<template>
    <div>
        <form @submit.prevent="createOrder">
            <div>
                <label for="id">Order ID:</label>
                <input type="number" v-model="order.id" required />
            </div>
            <div>
                <label for="allocation">Allocation ID:</label>
                <input type="number" v-model="order.allocation" required />
            </div>
            <div>
                <label for="type">Type:</label>
                <select v-model="order.type" required>
                    <option value="buy">Buy</option>
                    <option value="sell">Sell</option>
                </select>
            </div>
            <div>
                <label for="shares">Shares:</label>
                <input type="number" v-model="order.shares" required />
            </div>
            <button type="submit" :disabled="!canCreateOrder">Create Order</button>
        </form>
        <Error :message="error" />
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useStore } from 'vuex';
import Error from './Error.vue';

const order = reactive({
    id: null,
    portfolio: 1,
    allocation: null,
    type: null,
    shares: 0
});
let error = ref("");

const store = useStore();

const canCreateOrder = computed(() => {
    return (
        order.id         != null &&
        order.allocation != null &&
        order.type       != null &&
        order.shares     > 0
    );
})
const createOrder = () => {
    store.dispatch('newOrder', order)
        .then(() => {
            error.value = "";
        })
        .catch(({ response }) => {
            switch (response.status) {
                case 404:
                    error.value = "Allocation not found";
                    break;
                case 500:
                    error.value = "Cannot sell more shares than the available amount for that allocation";
                    break;
            }
        });
};
</script>
