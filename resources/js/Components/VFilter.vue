<template>
    <div class="p-2 bg-gray-50 border-bottom-1 rounded-lg shadow-lg space-y-6">
        <div
            class="bg-blue-400 px-2 text-white font-semibold text-xl py-1 rounded-t-lg"
        >
            <v-btn icon variant="tonal" class="me-2" @click="show = !show">
                <v-icon icon="mdi-filter-multiple-outline"></v-icon>
            </v-btn>
            <span>Advanced Filters</span>
        </div>

        <div v-show="show" class="grid grid-cols-1 px-2 sm:grid-cols-2 gap-6">
            <div class="w-full">
                <label
                    for="filterValue"
                    class="block text-sm font-medium text-gray-700"
                >
                    Searching
                </label>
                <input
                    id="filterValue"
                    type="text"
                    v-model="inputValue"
                    @input="emitFilterChange"
                    placeholder="Type your search"
                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm py-2 px-4"
                />
            </div>

            <div class="w-full">
                <label
                    for="parameter"
                    class="block text-sm font-medium text-gray-700"
                >
                    Searching by
                </label>
                <select
                    id="parameter"
                    v-model="selected_parameter"
                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm py-2 px-4"
                >
                    <option v-for="param in params" :key="param" :value="param">
                        {{ param }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["change"],
    props: {
        params: {
            type: Array,
            required: true,
        },
    },

    watch: {
        selected_parameter() {
            this.emitFilterChange();
        },
    },
    data() {
        return {
            selected_parameter: this.params[0] || "",
            inputValue: "",
            show: false,
        };
    },
    methods: {
        emitFilterChange() {
            const filterObject = {};
            filterObject[this.selected_parameter] = this.inputValue;
            this.$emit("change", filterObject);
        },
    },
};
</script>
