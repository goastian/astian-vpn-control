<template>
    <div class="card border-thin flex items-center">
        <span class="number bg-light-blue-darken-1">{{ number }}</span>
        <div>
            <img :src="image" width="200px">
        </div>
        <div class="flex flex-column items-center ga-4">
            <h4><strong>{{ title }}</strong></h4>
            <span>{{ description }}</span>
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
        }
    }
}

</script>

<style scoped>

.card {
    position: relative;
    padding: 2rem;
    border-radius: 1rem;
    gap: 2rem;
    min-height: 220px;
}

.number {
    position: absolute;
    padding: .5rem 1rem;
    left: 0;
    top: 0;
    border-radius: 40%;
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
