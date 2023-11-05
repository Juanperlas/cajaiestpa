<template>
    <div class="relative">
        <input
            type="text"
            v-model="selectedConcept"
            @input="filterConcepts"
            @focus="showDropdown = true"
            class="border border-gray-300 rounded w-full px-3 py-2 mb-4"
        />
        <ul
            v-show="showDropdown"
            class="border border-gray-300 rounded w-full px-3 py-2 mb-4 absolute bg-white z-10"
        >
            <li
                v-for="concepto in filteredConcepts"
                :key="concepto.id"
                @mousedown.prevent=""
                @click="selectConcept(concepto)"
                class="cursor-pointer hover:bg-gray-200"
            >
                {{ concepto.concept_name }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        conceptos: Array,
    },
    data() {
        return {
            selectedConcept: "",
            filteredConcepts: [],
            showDropdown: false,
        };
    },
    methods: {
        filterConcepts() {
            if (this.selectedConcept) {
                this.filteredConcepts = this.conceptos.filter((concepto) =>
                    concepto.concept_name
                        .toLowerCase()
                        .includes(this.selectedConcept.toLowerCase()),
                );
                if (this.filteredConcepts.length === 0) {
                    this.$emit("update:modelValue", this.selectedConcept);
                }
            } else {
                this.filteredConcepts = this.conceptos;
            }
        },
        selectConcept(concepto) {
            this.selectedConcept = concepto.concept_name;
            this.$emit("update:modelValue", this.selectedConcept);
            this.showDropdown = false;
        },
        hideDropdown() {
            setTimeout(() => {
                this.showDropdown = false;
            }, 200);
        },
    },
    watch: {
        conceptos: {
            immediate: true,
            handler(newVal) {
                this.filteredConcepts = newVal;
            },
        },
    },
};
</script>
