<template>
    <div
        class="flex ga-4 card border-thin"
    >
        <div>
            <div class="btn" :class="{'bg-red-accent-2' : !state, 'bg-teal-accent-4' : state}">
                <v-btn
                    @click="toggle(peer)"
                    icon="mdi-power"
                >
                </v-btn>
            </div>
        </div>
        <div class="flex flex-column ga-3">
            <div class="flex justify-between">
                <span>{{ title }}</span>
                <span>{{ server }}</span>
            </div>
            <div>
                <span><Strong>Interface </Strong> {{ network }} </span>
                <span><Strong> - Listen Port</Strong> {{ port }}</span>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        title: {
            type: String,
            required: true
        },
        server: {
            type: String,
            required: true
        },
        network: {
            type: String,
            required: true
        },
        port: {
            type: String,
            required: true
        },
        state: {
            type: Boolean,
            required: true
        },
        peer: {
            type: Object,
            required: true
        }
    },

    methods: {
        /**
         * Delete Server
         */
        async toggle(item) {
            try {
                const res = await this.$api.put(item.links.toggle, {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                });
                if (res.status == 201) {
                    this.$emit("updated", res.data);
                    this.dialog = false;
                }
            } catch (err) {
                if (err.response.status == 403) {
                    this.$notification.error(err.response.data.message);
                }
                if (err.response.status == 500) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 404) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 422) {
                    this.$notification.error(err.response.data.message);
                }
                this.dialog = false;
            }
        },
    },
}
</script>

<style scoped>
.card {
    padding: .5rem 1rem;
    border-radius: 1rem;
    align-items: center;
    justify-content: center;
}

.card > div:last-child {
    width: 80%;
}

.btn {
    padding: .5rem;
    border-radius: 50%;
}
</style>
