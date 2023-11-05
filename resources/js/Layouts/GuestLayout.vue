<template>
    <div
        class="bg-no-repeat bg-cover bg-center relative"
        style="
            background-image: url(https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1951&amp;q=80);
        "
    >
        <div
            class="absolute bg-gradient-to-b from-blue-500 to-white opacity-75 inset-0 z-0"
        ></div>
        <div class="min-h-screen sm:flex sm:flex-row mx-0 justify-center">
            <div
                class="flex-col flex self-center p-10 sm:max-w-5xl xl:max-w-2xl z-10"
            >
                <div class="self-start hidden lg:flex flex-col text-white">
                    <img src="" class="mb-3" />
                    <h1 class="mb-3 font-bold text-5xl">
                        Bienvenido al sistema de Caja
                    </h1>
                    <h1 class="mb-3 font-bold text-3xl">IESTPA - ACORA</h1>
                    <p class="pr-3">
                        Ingrese sus credenciales asignadas en el formulario de
                        Inicio de Sesión para poder ingresar al sistema.
                    </p>
                </div>
            </div>
            <div class="flex justify-center self-center z-10">
                <div
                    class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
                >
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
