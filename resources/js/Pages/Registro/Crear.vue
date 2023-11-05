<script setup>
import { reactive } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { onMounted } from "vue";
import CurrentTimeTwo from "@/Components/CurrentTimeTwo.vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import CurrentDate from "@/Components/CurrentDate.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import PagoModel from "@/Components/PagoModel.vue";

const showModal = ref(false);

const pagos = ref([]);
const pagoAEditar = ref(null);

let totalPagos = 0;
let montoTotal = 0;

const eliminarPago = (index) => {
    // Resta el importe del pago que se va a eliminar
    totalPagos -= pagos.value[index].importe;
    pagos.value.splice(index, 1);
};

const editarPago = (index) => {
    // Guarda el importe del pago que se va a editar para poder restarlo después
    montoTotal = pagos.value[index].importe;
    pagoAEditar.value = index;
    showModal.value = true;
};

const cancelarPago = () => {
    showModal.value = false;
};

const eliminarTodosLosPagos = () => {
    // Elimina todos los pagos
    pagos.value = [];
    // Restablece el total de pagos a 0
    totalPagos = 0;
};

const guardarPago = (pago) => {
    if (!pago.concepto || !pago.importe) {
        toast.error("Por favor, rellena todos los campos.", {
            autoClose: 2000,
            style: {
                marginTop: "75px",
            },
        });
        return;
    }
    if (pagoAEditar.value !== null) {
        // Resta el importe del pago anterior
        totalPagos -= montoTotal;
        pagos.value[pagoAEditar.value] = pago;
        pagoAEditar.value = null;
    } else {
        pagos.value.push(pago);
    }
    // Suma el importe del nuevo pago
    totalPagos += pago.importe;
    showModal.value = false;
};

const props = defineProps({
    visitanteArray: {
        type: Object,
        required: true,
    },
    conceptos: {
        type: Array,
    },
    auth: {
        type: Object,
        required: true,
    },
    dni: {
        type: String,
        required: true,
    },
    error: String,
    success: String,
});

const form = useForm({
    dni: props.visitanteArray?.numeroDocumento ?? props.dni,
    visitante: props.visitanteArray?.nombre ?? "No se encontraron datos",
    observation: "",
    nombre_usuario: "",
    pagos: pagos.value, // Agregar la propiedad pagos al objeto form
    usuario_id: props.auth.user.id,
    pagos: pagos.value,
});

function submitForm() {
    const missingFields = [];
    if (!form.dni) missingFields.push("DNI");
    if (!form.visitante) missingFields.push("Visitante");
    if (!form.usuario_id) missingFields.push("Usuario ID");
    if (missingFields.length > 0) {
        const missingFieldsString = missingFields.join(", ");
        toast.error(
            `Por favor complete los siguientes campos: ${missingFieldsString}`,
            {
                autoClose: 3000,
                style: {
                    marginTop: "75px",
                },
            },
        );
        return;
    }

    // Enviar el formulario
    form.post(route("registro.store"));
}
</script>

<template>
    <Head title="Registrar" />
    <!-- <div>
        <div>{{ visitanteArray }}</div>
    </div> -->
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Registrar
            </h2>
        </template>
        <div class="max-w-4xl mb-10 mx-auto p-5 rounded-xl mt-4 bg-white">
            <div class="mt-6">
                <form
                    class="grid grid-cols-3 gap-6 items-center"
                    @submit.prevent="submitForm"
                >
                    <div class="col-span-3">
                        <InputLabel
                            for="dni"
                            value="Documento Nacional de Identidad"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        />
                        <TextInput
                            id="dni"
                            type="text"
                            class="bg-gray-100 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded-lg h-10 px-4"
                            v-model="form.dni"
                            :readonly="visitanteArray"
                        ></TextInput>
                    </div>
                    <div class="col-span-3 -mt-4">
                        <InputLabel
                            for="nombre"
                            value="Nombre completo del visitante"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        />
                        <TextInput
                            id="nombre"
                            type="text"
                            class="bg-gray-100 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded-lg h-10 px-4"
                            v-model="form.visitante"
                            autocomplete="nombre"
                            :readonly="visitanteArray"
                        />
                    </div>
                    <div class="-mt-4">
                        <InputLabel
                            for="usuario"
                            value="Usuario"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        />

                        <TextInput
                            id="usuario"
                            type="text"
                            v-model="props.auth.user.name"
                            class="bg-gray-100 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded-lg h-10 px-4"
                            placeholder="Entidad proveniente"
                            autocomplete="entidad"
                            readonly
                        />
                    </div>
                    <div class="-mt-4">
                        <InputLabel
                            for="hora"
                            value="hora"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        ></InputLabel>

                        <div
                            id="hora"
                            type="text"
                            class="bg-gray-100 appearance-none block w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded-lg h-10 px-4 font-medium"
                            autocomplete="hora"
                        >
                            <CurrentTimeTwo></CurrentTimeTwo>
                        </div>
                    </div>
                    <div class="-mt-4">
                        <InputLabel
                            for="fecha"
                            value="fecha"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        ></InputLabel>

                        <div
                            id="fecha"
                            type="text"
                            class="bg-gray-100 appearance-none block w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded-lg h-10 px-4 font-medium"
                            autocomplete="fecha"
                        >
                            <CurrentDate></CurrentDate>
                        </div>
                    </div>
                    <!-- <div class="col-span-1 -mt-4">
                        <InputLabel
                            for="concepto"
                            value="concepto"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        />
                        <TextInput
                            class="col-span-1 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded-lg h-10 px-4"
                            type="text"
                            placeholder="Conceptos"
                        />
                    </div>
                    <div class="col-span-1 -mt-4">
                        <InputLabel
                            for="pago"
                            value="pago"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        />
                        <TextInput
                            class="col-span-1 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded-lg h-10 px-4"
                            type="text"
                            placeholder="Conceptos"
                        />
                    </div> -->
                    <button
                        type="button"
                        id="pagos"
                        @click="showModal = true"
                        class="col-span-3 mt-4 bg-blue-800 shadow-lg shadow-blue-500/50 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded"
                    >
                        <span>Agregar Pago</span>
                    </button>
                    <PagoModel
                        v-if="showModal"
                        :conceptos="conceptos"
                        :pago="pagos[pagoAEditar]"
                        @guardar="guardarPago"
                        @cancelar="cancelarPago"
                    />
                    <input
                        type="hidden"
                        name="pagos"
                        :value="JSON.stringify(pagos)"
                    />
                    <div class="flex col-span-3">
                        <table
                            v-if="pagos.length > 0"
                            class="w-full table-auto"
                        >
                            <thead
                                class="bg-blue-800 shadow-lg shadow-blue-500/50 hover:bg-blue-700 text-md font-extrabold uppercase text-white"
                            >
                                <tr>
                                    <th class="p-2">Concepto</th>
                                    <th class="p-2">Monto</th>
                                    <th class="p-2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 text-sm text-center border-b-2 border-blue-800"
                            >
                                <tr
                                    v-for="(pago, index) in pagos"
                                    :key="pago.concepto"
                                >
                                    <td class="p-2">
                                        <div class="font-bold text-gray-800">
                                            {{ pago.concepto }}
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="font-bold text-green-500">
                                            S/.{{
                                                parseFloat(
                                                    pago.importe,
                                                ).toFixed(2)
                                            }}
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <button
                                            @click="editarPago(index)"
                                            type="button"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-8 w-8 rounded-full p-1 hover:bg-gray-100 hover:text-blue-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                ></path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="eliminarPago(index)"
                                            type="button"
                                        >
                                            <svg
                                                class="h-8 w-8 rounded-full p-1 hover:bg-gray-100 hover:text-blue-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                ></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Fila del total -->
                                <tr
                                    class="bg-blue-100 border-2 border-blue-700 rounded-md font-extrabold"
                                >
                                    <td class="p-2 font-bold text-gray-800">
                                        TOTAL
                                    </td>
                                    <td class="p-2 font-bold text-gray-800">
                                        S/.{{
                                            parseFloat(totalPagos).toFixed(2)
                                        }}
                                    </td>
                                    <td class="p-2">
                                        <!-- Botón para eliminar todos los pagos -->
                                        <button
                                            @click="
                                                eliminarTodosLosPagos(index)
                                            "
                                            type="button"
                                        >
                                            <svg
                                                class="h-8 w-8 rounded-full p-1 hover:bg-gray-100 hover:text-blue-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                ></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-span-3 -mt-4">
                        <InputLabel
                            for="observacion"
                            value="Observación"
                            class="font-semibold text-gray-600 py-2 uppercase"
                        />

                        <TextInput
                            id="observacion"
                            type="text"
                            v-model="form.observation"
                            class="min-h-[100px] max-h-[300px] h-28 appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded-lg py-4 px-4"
                            placeholder="Agregrue cualquier observación..."
                            autocomplete="observacion"
                        />
                    </div>
                    <div class="flex justify-between col-span-3">
                        <Link
                            :href="route('registro.index')"
                            :active="route().current('registro.index')"
                            ><button
                                type="button"
                                class="hover:-translate-y-1 hover:scale-110 transition ease-in-out delay-50 bg-red-500 shadow-lg shadow-red-500/50 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
                            >
                                Cancelar
                            </button></Link
                        >

                        <button
                            type="submit"
                            class="hover:-translate-y-1 hover:scale-110 transition ease-in-out delay-50 bg-green-500 shadow-lg shadow-green-500/50 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
