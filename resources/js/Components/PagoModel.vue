<template>
    <div
        class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center"
        @click.self="$emit('cancelar')"
    >
        <div
            class="bg-white rounded shadow w-full max-w-md px-4 py-2 sm:px-8 sm:py-4 mx-2"
        >
            <h2 class="text-lg font-semibold mb-4 text-center">Agregar pago</h2>
            <label class="block font-medium mb-2">Concepto:</label>
            <DropdownPicker
                v-model="concepto"
                :conceptos="conceptos"
                @update:modelValue="concepto = $event"
            />
            <label class="block font-medium mb-2">Pago:</label>
            <input
                type="number"
                step="0.01"
                v-model="importe"
                class="border border-gray-300 rounded w-full px-3 py-2 mb-4"
            />
            <div class="flex justify-between">
                <button
                    type="button"
                    @click="guardarPago"
                    class="flex justify-center items-center bg-blue-500 hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue text-white py-2 px-4 rounded-md transition duration-300 gap-2"
                >
                    Guardar
                </button>
                <button
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                    type="button"
                    @click="$emit('cancelar')"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { toast } from "vue3-toastify";
import DropdownPicker from "./DropdownPicker.vue";
export default {
    props: {
        pago: Object,
        conceptos: Array,
    },
    components: {
        DropdownPicker,
    },
    data() {
        return {
            concepto: "",
            importe: "",
        };
    },
    methods: {
        guardarPago() {
            if (!this.concepto || !this.importe) {
                toast.error("Por favor, rellena todos los campos.", {
                    autoClose: 2000,
                    style: {
                        marginTop: "75px",
                    },
                });
                return;
            }
            this.$emit("guardar", {
                concepto: this.concepto,
                importe: this.importe,
            });
        },
    },
    watch: {
        pago: {
            immediate: true,
            handler(pago) {
                if (pago) {
                    this.concepto = pago.concepto;
                    this.importe = pago.importe;
                } else {
                    this.concepto = "";
                    this.importe = "";
                }
            },
        },
    },
};
</script>
