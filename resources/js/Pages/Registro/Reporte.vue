<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import { ref, watch } from "vue";

let props = defineProps({ pagos: Object });
let pagosFiltrados = ref(props.pagos);

let fechaDesde = ref(null);
let fechaHasta = ref(null);

const filtrarPorRangoDeFechas = () => {
    if (fechaDesde.value && fechaHasta.value) {
        const desdeStr = new Date(fechaDesde.value).toISOString().split("T")[0]; // 'YYYY-MM-DD'
        const hastaStr = new Date(fechaHasta.value).toISOString().split("T")[0]; // 'YYYY-MM-DD'
        pagosFiltrados.value = props.pagos.filter((pago) => {
            const fechaPago = new Date(pago.payment_date);
            const fechaPagoStr = fechaPago.toISOString().split("T")[0]; // 'YYYY-MM-DD'
            return fechaPagoStr >= desdeStr && fechaPagoStr <= hastaStr;
        });
    }
};

watch([fechaDesde, fechaHasta], filtrarPorRangoDeFechas);

const mostrarTodos = () => {
    pagosFiltrados.value = props.pagos;
    fechaDesde.value = null;
    fechaHasta.value = null;
};

const filtrarHoy = () => {
    const hoy = new Date();
    const hoyStr = hoy.toISOString().split("T")[0]; // 'YYYY-MM-DD'
    pagosFiltrados.value = props.pagos.filter((pago) => {
        const fechaPago = new Date(pago.payment_date);
        const fechaPagoStr = fechaPago.toISOString().split("T")[0]; // 'YYYY-MM-DD'
        return fechaPagoStr === hoyStr;
    });
    fechaDesde.value = null;
    fechaHasta.value = null;
};

const filtrarUltimoMes = () => {
    const haceUnMes = new Date();
    haceUnMes.setMonth(haceUnMes.getMonth() - 1);
    const haceUnMesStr = haceUnMes.toISOString().split("T")[0]; // 'YYYY-MM-DD'
    pagosFiltrados.value = props.pagos.filter((pago) => {
        const fechaPago = new Date(pago.payment_date);
        const fechaPagoStr = fechaPago.toISOString().split("T")[0]; // 'YYYY-MM-DD'
        return fechaPagoStr >= haceUnMesStr;
    });
    fechaDesde.value = null;
    fechaHasta.value = null;
};

const filtrarUltimoAño = () => {
    const haceUnAño = new Date();
    haceUnAño.setFullYear(haceUnAño.getFullYear() - 1);
    const haceUnAñoStr = haceUnAño.toISOString().split("T")[0]; // 'YYYY-MM-DD'
    pagosFiltrados.value = props.pagos.filter((pago) => {
        const fechaPago = new Date(pago.payment_date);
        const fechaPagoStr = fechaPago.toISOString().split("T")[0]; // 'YYYY-MM-DD'
        return fechaPagoStr >= haceUnAñoStr;
    });
    fechaDesde.value = null;
    fechaHasta.value = null;
};
</script>

<template>
    <Head title="Reportes" />

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
                        <div class="justify-center flex mb-4">
                            <input
                                type="date"
                                v-model="fechaDesde"
                                class="my-custom-class"
                            />
                            <input
                                type="date"
                                v-model="fechaHasta"
                                class="my-custom-class"
                            />
                        </div>
                        <div class="flex justify-center">
                            <PrimaryButton
                                @click="filtrarHoy"
                                class="btn btn-success my-custom-class mx-2"
                            >
                                Hoy
                            </PrimaryButton>
                            <PrimaryButton
                                @click="filtrarUltimoMes"
                                class="btn btn-success my-custom-class mx-2"
                            >
                                Último mes
                            </PrimaryButton>
                            <PrimaryButton
                                @click="filtrarUltimoAño;"
                                class="btn btn-success my-custom-class mx-2"
                            >
                                Último año
                            </PrimaryButton>
                            <PrimaryButton @click="mostrarTodos"
                                >Mostrar todos
                            </PrimaryButton>
                        </div>
                        <div class="table-responsive">
                            <DataTable
                                id="reporte"
                                :data="pagosFiltrados"
                                :columns="columns"
                                class="text-sm font-normal text-gray-700 whitespace-nowrap row-border dt[-head|-body]-justify stripe hover"
                                :options="{
                                    responsive: true,
                                    autoWidth: true,
                                    select: true,
                                    dom: 'Bfrtip',
                                    language: {
                                        search: 'Buscar',
                                        zeroRecords:
                                            'No hay registros para mostrar',
                                        info: 'Mostrando del _START_ al _END_ de _TOTAL_ registros',
                                        infoFiltered:
                                            '(Filtrados de _MAX_ registros.)',
                                        paginate: {
                                            first: 'Primero',
                                            previous: 'Anterior',
                                            next: 'Siguiente',
                                            last: 'Último',
                                        },
                                    },
                                }"
                            >
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
import print from "datatables.net-buttons/js/buttons.print";
import pdfmake from "pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import "datatables.net-bs5";
import JsZip from "jszip";
import PrimaryButton from "@/Components/PrimaryButton.vue";
pdfmake.vfs = pdfFonts.pdfMake.vfs;

window.JSZip = JsZip;
DataTable.use(DataTableLib);
DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);
export default {
    components: { DataTable, TableHeaderCell, TableRow, AdminLayout },
    data() {
        return {
            columns: [
                { data: "id", title: "ID" },
                { data: "dni", title: "DNI" },
                { data: "user_name", title: "VISITANTE" },
                { data: "payment_time", title: "HORA" },
                { data: "payment_date", title: "FECHA" },
                { data: "observation", title: "OBS" },
                { data: "importe", title: "IMPORTE" },
            ],
            buttons: [
                {
                    title: "Reporte de productos",
                    extend: "excelHtml5",
                    text: '<i class="fa-solid fa-file-excel"></i> Excel',
                    className: "btn btn-success my-custom-class",
                },
                {
                    title: "Reporte de productos",
                    extend: "excelHtml5",
                    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
                    className: "btn btn-danger",
                },
                {
                    title: "Reporte de productos",
                    extend: "print",
                    text: '<i class="fa-solid fa-file-print"></i> Imprimir',
                    className: "btn btn-warning",
                },
            ],
        };
    },
};
</script>
<style>
@import "datatables.net-dt";

.dataTables_wrapper {
    background-color: #f5f5f5;
}

/* Personalizar estilos de las celdas de encabezado */
.dataTables_wrapper th {
    background-color: #ddd;
    font-weight: bold;
}

/* Personalizar estilos de paginación */
.dataTables_paginate .paginate_button {
    background-color: #e9e9e9;
    border: 1px solid #ccc;
}

/* Personalizar estilos de búsqueda */
.dataTables_filter input {
    border: 1px solid #ccc;
    border-radius: 3px;
    padding: 5px;
}
</style>
