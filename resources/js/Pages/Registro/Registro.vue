<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Link, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CurrentTime from "@/Components/CurrentTime.vue";
import { watch } from "vue";

const form = useForm({
    dni: "",
});

const submit = () => {
    window.location.href = `/registro/create/${form.dni}`;
};
watch(
    () => form.dni,
    (value) => {
        if (value.length >= 8) {
            form.dni = value.slice(0, 8);
            submit();
        }
    },
);
</script>

<template>
    <Head title="Registrar" />
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="uppercase p-3 bg-black rounded-full text-white w-full font-semibold text-center"
            >
                Registrar nueva entrada
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
                    <div>
                        <CurrentTime></CurrentTime>
                    </div>
                    <div class="shadow pb-8">
                        <div class="max-w-4xl mx-auto py-4">
                            <div
                                class="mt-6 flex justify-center items-center text-center"
                            >
                                <form
                                    class="grid grid-cols-3 gap-4 items-center"
                                    @submit.prevent="submit"
                                >
                                    <div class="col-span-3 md:col-span-2">
                                        <InputLabel
                                            for="dni"
                                            value="Documento Nacional de Identidad"
                                            class="font-semibold text-gray-600 py-2 uppercase hidden"
                                        />
                                        <TextInput
                                            id="dni"
                                            v-model="form.dni"
                                            type="text"
                                            class="appearance-none block w-full bg-grey-lighter text-grey-darker text-2xl font-extrabold text-center border border-grey-lighter rounded-lg h-24 px-4 shadow-xl"
                                            placeholder="Número de DNI"
                                            required
                                            autofocus
                                        />
                                    </div>
                                    <button
                                        type="submit"
                                        class="col-span-3 md:col-span-1 h-10 focus:outline-none font-bold text-white text-sm py-2 px-4 rounded-full bg-blue-400 hover:bg-blue-500 hover:shadow-lg"
                                    >
                                        Realizar Búsqueda del DNI
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
