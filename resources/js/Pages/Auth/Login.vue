<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
        onSuccess: () => {
            toast.success(
                "Inicio de Sesión Correcto, ¡Bienvenido al Sistema de Visitas!",
                {
                    autoClose: 2000,
                    style: {
                        marginTop: "75px",
                    },
                },
            );
        },
        onError: () => {
            toast.error("Las credenciales introducidas son incorrectas", {
                autoClose: 2000,
                style: {
                    marginTop: "75px",
                },
            });
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form
            @submit.prevent="submit"
            class="p-12 bg-white mx-auto rounded-full w-100"
        >
            <div class="flex justify-center -mt-10 mb-4">
                <Link href="/">
                    <ApplicationLogo class="w-24" />
                </Link>
            </div>
            <div class="mb-4">
                <h3 class="font-bold text-2xl text-gray-800 text-center">
                    Iniciar Sesión
                </h3>
                <p class="text-gray-500">
                    Ingrese sus credenciales para ingresar
                </p>
            </div>
            <div>
                <InputLabel
                    for="email"
                    value="Email"
                    class="text-sm text-gray-700 tracking-wide"
                />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Ingrese su correo"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Ingrese su contraseña"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                        >Recuerdame</span
                    >
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button
                    class="w-full flex justify-center bg-green-400 hover:bg-green-500 text-gray-100 p-3 rounded-full tracking-wide font-bold shadow-lg cursor-pointer transition ease-in duration-500"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Iniciar Sesión
                </button>
            </div>
            <div class="pt-5 text-center text-gray-400 text-xs">
                <span>
                    Copyright © IESTP - ACORA
                    <a
                        href="https://codepen.io/uidesignhub"
                        rel=""
                        target="_blank"
                        title="Ajimon"
                        class="text-green hover:text-green-500"
                        >AJI</a
                    ></span
                >
            </div>
        </form>
    </GuestLayout>
</template>
