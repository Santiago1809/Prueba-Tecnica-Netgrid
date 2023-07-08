<template>
  <div
    class="max-w-lg rounded overflow-hidden shadow-lg hover:bg-gray-300 cursor-pointer mx-6 my-2.5"
  >
    <div class="px-6 py-4 flex justify-between items-center">
      <div class="font-bold text-xl mb-2">
        <router-link :to="ruta" class="hover:underline hover:text-blue-700">{{
          nombreGrupo
        }}</router-link>
        <p class="mt-2 text-xs">{{ descripcion }}</p>
        <p class="text-gray-700 text-sm">
          Fecha de inicio: {{ showFecha(fecha_inicio) }}
        </p>
        <p class="text-gray-700 text-sm">
          Fecha de fin: {{ showFecha(fecha_fin) }}
        </p>
      </div>
      <div class="flex">
        <ModalComponent
          :text="'fa-solid fa-pen-to-square'"
          :nombreGrupo="nombreGrupo"
          :descripcion="descripcion"
          :fecha_inicio="fecha_inicio"
          :fecha_fin="fecha_fin"
          :id="id"
          :EditarGrupo="true"
          color="blue"
        />
        <ModalComponent
          :text="'fa-solid fa-trash'"
          :BorrarGrupo="true"
          :id="id"
          :nombreGrupo="nombreGrupo"
          color="red"
        />
      </div>
    </div>
  </div>
</template>

<script>
import ModalComponent from "./ModalComponent.vue";

export default {
  name: "IndexCardComponent",
  props: {
    nombreGrupo: String,
    ruta: String,
    id: Number,
    fecha_inicio: Date,
    fecha_fin: Date,
    descripcion: String,
  },
  components: {
    ModalComponent,
  },
  methods: {
    formatFecha(fecha) {
      const options = { day: "2-digit", month: "2-digit", year: "numeric" };
      return new Date(fecha).toLocaleDateString(undefined, options);
    },
    showFecha(fecha) {
      const fechaActual = new Date(fecha);
      fechaActual.setDate(fechaActual.getDate() + 1);

      const options = { day: "2-digit", month: "2-digit", year: "numeric" };
      return fechaActual.toLocaleDateString(undefined, options);
    },
  },
  mounted() {},
};
</script>
