<template>
    <div
        class="row card q-gutter-x-md"
    >
        <div>
            <div class="btn" :class="{'bg-red-6' : !state, 'bg-blue-4' : state}">
                <q-btn
                    @click="toggle(peer)"
                    icon="mdi-power"
                >
                </q-btn>
            </div>
        </div>
        <div class="column q-gutter-y-md">
            <div class="flex justify-between">
                <span>{{ title }}</span>
                <span>{{ server }}</span>
            </div>
            <div>
                <span><strong>Interface </strong> {{ network }} </span>
                <span><strong> - Listen Port</strong> {{ port }}</span>
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
    padding: .5rem .8rem;
    border-radius: 1rem;
    align-items: center;
    justify-content: center;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    max-height: 100px;
}

.card > div:last-child {
    width: 70%;
}

.btn {
    padding: .5rem;
    border-radius: 50%;
}

.q-btn {
    border-radius: 50%;
    padding: .8rem .8rem;
    background-color: white;
}
</style>
