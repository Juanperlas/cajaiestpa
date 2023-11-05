<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import UpOnTable from "@/Components/UpOnTable.vue";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

let props = defineProps(["pagos"]);

const form = useForm({});

const abrirBoleta = (id) => {
    window.open(`/registro/pagos/${id}/boleta`, "_blank");
};

const showConfirmDeletePagoModal = ref(false);
let selectedPagoId = null;

const confirmDeletePago = (idPago) => {
    selectedPagoId = idPago;
    showConfirmDeletePagoModal.value = true;
};

const closeModal = () => {
    showConfirmDeletePagoModal.value = false;
};

const deletePago = () => {
    if (selectedPagoId) {
        form.delete(route("registro.destroy", selectedPagoId), {
            onSuccess: () => closeModal(),
        });
    }
};
</script>

<template>
    <Head title="Registros" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="uppercase p-3 bg-black rounded-full text-white w-full font-semibold text-center"
            >
                Registros REALIZADOS
            </h2>
        </template>
        <div
            class="antialiased bg-slate-300 w-full text-slate-700 relative py-1"
        >
            <div
                class="grid grid-cols-12 mx-auto gap-2 sm:gap-4 md:gap-6 lg:gap-10 xl:gap-14 max-w-[90%] my-10 px-2"
            >
                <div
                    id="content"
                    class="bg-white col-span-12 md:col-span-12 rounded-lg p-6"
                >
                    <div class="max-w-[100%] mx-auto py-4">
                        <div class="mt-6">
                            <UpOnTable #posicion>
                                <div
                                    class="items-center mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0"
                                >
                                    <form class="lg:pr-3" method="GET">
                                        <label
                                            for="users-search"
                                            class="sr-only"
                                            >Buscar</label
                                        >
                                        <div class="flex mt-1 lg:w-64 xl:w-96">
                                            <input
                                                v-model="buscar"
                                                type="text"
                                                name="buscar"
                                                id="users-search"
                                                class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                                                placeholder="Buscar"
                                            />
                                        </div>
                                    </form>
                                    <div
                                        class="flex pl-0 mt-3 space-x-1 sm:pl-2 sm:mt-0"
                                    >
                                        <Link
                                            :href="route('registro.index')"
                                            :active="
                                                route().current(
                                                    'registro.index',
                                                )
                                            "
                                            class="hidden inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white rounded-lg bg-gradient-to-br from-green-400 to-green-800 sm:inline-flex sm:ml-auto shadow-md shadow-gray-300 hover:scale-[1.02] transition-transform"
                                        >
                                            Realizar reportes
                                        </Link>
                                    </div>
                                </div>

                                <Link
                                    :href="route('registro.index')"
                                    :active="route().current('registro.index')"
                                    class="hidden inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white rounded-lg bg-gradient-to-br from-blue-400 to-blue-800 sm:inline-flex sm:ml-auto shadow-md shadow-gray-300 hover:scale-[1.02] transition-transform"
                                >
                                    <svg
                                        class="mr-2 -ml-1 w-6 h-6"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                    Agregar Pago
                                </Link>
                            </UpOnTable>

                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>ID</TableHeaderCell>
                                        <TableHeaderCell>DNI</TableHeaderCell>
                                        <TableHeaderCell
                                            >USUARIO</TableHeaderCell
                                        >
                                        <TableHeaderCell>HORA</TableHeaderCell>
                                        <TableHeaderCell>FECHA</TableHeaderCell>
                                        <TableHeaderCell
                                            >IMPORTE</TableHeaderCell
                                        >
                                        <TableHeaderCell
                                            >OPCIONES</TableHeaderCell
                                        >
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow
                                        v-for="pago in pagos"
                                        :key="pago.id"
                                        class="border-b"
                                    >
                                        <TableDataCell>{{
                                            pago.id
                                        }}</TableDataCell>
                                        <TableDataCell>{{
                                            pago.dni
                                        }}</TableDataCell>
                                        <TableDataCell>{{
                                            pago.user_name
                                        }}</TableDataCell>
                                        <TableDataCell>{{
                                            pago.payment_time
                                        }}</TableDataCell>
                                        <TableDataCell>{{
                                            pago.payment_date
                                        }}</TableDataCell>
                                        <TableDataCell
                                            >S/.
                                            {{ pago.importe }}</TableDataCell
                                        >
                                        <TableDataCell
                                            ><button
                                                @click="
                                                    confirmDeletePago(pago.id)
                                                "
                                                class="text-red-400 hover:text-red-600"
                                            >
                                                <DangerButton
                                                    >Eliminar</DangerButton
                                                >
                                            </button>
                                            <Modal
                                                :show="
                                                    showConfirmDeletePagoModal
                                                "
                                                @close="closeModal"
                                            >
                                                <div class="p-6">
                                                    <h2
                                                        class="text-lg font-semibold text-slate-800"
                                                    >
                                                        ¿Estás seguro de
                                                        eliminar el pago?
                                                    </h2>
                                                    <div
                                                        class="mt-6 flex space-x-4"
                                                    >
                                                        <DangerButton
                                                            @click="
                                                                deletePago(
                                                                    pago.id,
                                                                )
                                                            "
                                                            >Eliminar</DangerButton
                                                        >
                                                        <SecondaryButton
                                                            @click="closeModal"
                                                            >Cancelar</SecondaryButton
                                                        >
                                                    </div>
                                                </div>
                                            </Modal>
                                            <PrimaryButton
                                                @click="abrirBoleta(pago.id)"
                                                class="mx-2"
                                                >BOLETA</PrimaryButton
                                            ></TableDataCell
                                        >
                                    </TableRow>
                                </template>
                            </Table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
