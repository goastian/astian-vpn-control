import { defineStore } from 'pinia';
import { $api } from '../Config/axios.js';

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            user: [],
            access: false,
            role: null,
        }
    },

    actions: {
        async getUser() {
            try {
                const data = await $api.get('/user');
                this.user = data.data;
                this.access = true;
                this.role = data.data.groups[0].name;
            } catch(e) {
                return e;
            }
        }
    }
})
