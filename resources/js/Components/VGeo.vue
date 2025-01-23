<template>
    <div class="flex ga-1 text-grey-darken-3 items-center">
        <v-icon :icon="$utils.toKebabCase('mdi-map-marker-radius-outline')"
        />
        <span>{{ geo.country }}</span>
        <v-icon
            :icon="$utils.toKebabCase('mdi-reload')"
            size="15"
            @click="getGeo()"
        />
    </div>
</template>

<script>
export default {
    data() {
        return {
            geo: {},
        }
    },

    mounted() {
        this.getGeo();
    },

    methods: {
        getGeo() {
            fetch('/api/endpoint', {
                method: "GET",
                headers: {
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                this.geo = data;
            })
            .catch(error => {
                console.log(error);
            });
        },
    }
}
</script>
