<template>
    <div class="card border-thin flex items-center">
        <span class="number bg-light-blue-darken-1">{{ number }}</span>
        <div>
            <img :src="image" width="200px">
        </div>
        <div class="flex flex-column items-center ga-3">
            <h4><strong>{{ title }}</strong></h4>
            <span>{{ description }}</span>
        </div>
        <a
            v-if="btnTitle"
            :href="btnUrl"
            target="_blank"
            class="bg-blue text-center"
        >{{ btnTitle }}</a>
    </div>
</template>

<script>

export default {
    props: {
        title: {
            type: String,
            required: true
        },
        description: {
            type: String,
            required: true
        },
        number: {
            type: Number,
            required: true
        },
        image: {
            type: String,
            required: true
        },
        btnTitle: {
            type: String,
        },
        btnUrl: {
            type: String
        }
    }
}

</script>

<style scoped>

.card {
    position: relative;
    padding: 1.5rem;
    border-radius: 1rem;
    gap: 2rem;
    min-height: 260px;
    overflow: hidden;
}

.number {
    position: absolute;
    padding: .5rem 1rem;
    left: 0;
    top: 0;
    border-radius: 40%;
}

a {
    position: absolute;
    bottom: 0;
    width: 100%;
    left: 0;
    padding: .4rem;
}

@media (max-width: 480px) {
    .card {
        flex-direction: column;
    }

    img {
        width: 100px;
    }
}

</style>
