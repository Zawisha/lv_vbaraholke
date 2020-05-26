import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        product_name:'',
        product_desc:'',
        product_price:'',
        city:'',
        product_cond:'',
        selected_region:'',
        selected_category:''
    },
    getters: {

    },
    mutations: {
        setAdvertisement(state, product_arr)
        {
            state.product_name=product_arr['product_name'];
            state.product_desc=product_arr['product_desc'];
            state.product_price=product_arr['product_price'];
            state.city=product_arr['city'];
            state.product_cond=product_arr['product_cond'];
            state.selected_region=product_arr['selected_region'];
            state.selected_category=product_arr['selected_category'];
        },

    },
    actions: {
        setDescription(context, product_arr)
        {
            context.commit('setAdvertisement', product_arr);
        }

    },
});
