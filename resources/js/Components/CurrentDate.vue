<template>
    <span class="text-gray-700 body-font">
        {{ currentDate }}
    </span>
</template>

<script>
import { ref, onMounted, onUnmounted } from "vue";

export default {
    setup() {
        const currentDate = ref("");

        let intervalId = null;

        onMounted(() => {
            updateDate(); // Llamar a la función al montar el componente

            intervalId = setInterval(() => {
                updateDate(); // Actualizar la fecha cada segundo
            }, 1000);
        });

        onUnmounted(() => {
            clearInterval(intervalId); // Limpiar el intervalo al desmontar el componente
        });

        function updateDate() {
            const now = new Date();
            const optionsDate = {
                weekday: "long",
                month: "long",
                day: "numeric",
            };
            currentDate.value = now.toLocaleDateString("es-ES", optionsDate);
        }

        return { currentDate };
    },
};
</script>
