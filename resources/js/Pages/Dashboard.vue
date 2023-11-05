<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Estadistica from "@/Components/Estadistica.vue";
import { Head } from "@inertiajs/vue3";
import ChartJs from "@/Components/ChartJs.vue";

const Labels = ["Hoy", "Total"];
const Labels2 = ["Hoy", "Ayer"];
const Colors = ["skyblue", "purple"];
const Colors2 = ["orange", "brown"];
const Colors3 = ["red", "yellow"];
const Colors4 = ["skyblue", "orange"];
</script>

<template>
    <Head title="Inicio" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Dashboard
            </h2>
        </template>

        <div class="w-full">
            <Estadistica>
                <template #oficina>{{
                    $page.props.auth.user.oficina_id
                }}</template>
                <template #usuario>{{ $page.props.auth.user.name }}</template>
                <template #correo>{{ $page.props.auth.user.email }}</template>
                <template #pagosTotales>{{
                    $page.props.registros_data.pagosTotales
                }}</template>
                <template #pagosHoy>
                    {{ $page.props.registros_data.pagosHoy }}
                </template>
                <template #operacionesTotales>
                    {{ $page.props.registros_data.operacionesTotales }}
                </template>
                <template #operacionesHoy>
                    {{ $page.props.registros_data.operacionesHoy }}
                </template>
                <template #pagoTotalHoy>
                    S/. {{ $page.props.registros_data.sumaImporteHoy }}
                </template>
                <template #pagoUltimoHoy>
                    S/. {{ $page.props.registros_data.ultimoPago }}
                </template>
                <template #pagosTotalesTotal>
                    S/. {{ $page.props.registros_data.sumaImporteTotal }}
                </template>
                <template #pagosTotalesUltimo>
                    S/. {{ $page.props.registros_data.ultimoPago }}
                </template>
                <template #graficos
                    ><ChartJs
                        :chart-data="[
                            $page.props.registros_data.pagosHoy,
                            $page.props.registros_data.pagosTotales,
                        ]"
                        :chart-labels="Labels"
                        chart-label="Visita de Pagos"
                        :chart-colors="Colors"
                    ></ChartJs>
                    <ChartJs
                        :chart-data="[
                            $page.props.registros_data.operacionesHoy,
                            $page.props.registros_data.operacionesTotales,
                        ]"
                        :chart-labels="Labels"
                        chart-label="Operaciones en caja"
                        :chart-colors="Colors2"
                    ></ChartJs>
                    <ChartJs
                        :chart-data="[
                            $page.props.registros_data.sumaImporteHoy,
                            $page.props.registros_data.sumaImporteTotal,
                        ]"
                        :chart-labels="Labels"
                        chart-label="Pagos Totales (S/.)"
                        :chart-colors="Colors3"
                    ></ChartJs>
                    <ChartJs
                        :chart-data="[
                            $page.props.registros_data.sumaImporteHoy,
                            $page.props.registros_data.sumaImporteAyer,
                        ]"
                        :chart-labels="Labels2"
                        chart-label="Pagos Totales (S/.)"
                        :chart-colors="Colors4"
                    ></ChartJs>
                </template>
            </Estadistica>
        </div>
    </AuthenticatedLayout>
</template>
