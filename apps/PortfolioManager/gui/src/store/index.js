import axios from 'axios';
import { createStore } from 'vuex'

const API_URL = 'http://localhost:9000/api';

export default createStore({
    state: {
        allocations: [],
        orders: []
    },
    getters: {
        allocations: (state) => state.allocations,
        orders: (state) => state.orders
    },
    mutations: {
        setAllocations(state, allocations) {
            state.allocations = allocations;
        },
        setOrders(state, orders) {
            state.orders = orders;
        },
        removeOrder(state, order) {
            state.orders = state.orders.filter(o => o.id !== order.id);
        }
    },
    actions: {
        fetchPortfolio({ commit }) {
            axios.get(`${API_URL}/portfolios/1`)
                .then(({ data }) => { commit('setAllocations', data.allocations) })
                .catch(() => ({}));
            axios.get(`${API_URL}/orders?portfolio=1&completed=false`)
                .then(({ data }) => { commit('setOrders', data) })
                .catch(() => {});
        },
        completeOrder({ commit, dispatch }, order) {
            axios.patch(`${API_URL}/orders/${order.id}`, {status: 'completed'})
                .then(() => {
                    commit('removeOrder', order);
                    dispatch('fetchPortfolio');
                })
                .catch(() => {});
        }
    }
});