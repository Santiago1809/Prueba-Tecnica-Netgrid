<template>
  <form
    class="max-w-lg rounded overflow-hidden shadow-lg mt-4 mx-6"
    @submit.prevent="add_task($event)"
  >
    <div class="flex items-center px-6 py-4 flex-col">
      <h2 class="text-2xl">Agregar tarea</h2>
      <input
        type="text"
        class="flex-grow h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500 text-center"
        placeholder="Agrega el titulo"
        v-model="titulo"
        required
      />
      <input
        type="text"
        class="flex-grow h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500 text-center"
        placeholder="Agrega la descripción"
        v-model="descripcion"
        required
      />
      <select name="estado" id="estado" v-model="estado" class="text-center">
        <option value="" disabled selected hidden>Seleccionar estado</option>
        <option v-for="(state, index) in estados" :key="index" :value="state">
          {{ state }}
        </option>
      </select>

      <button
        type="submit"
        class="px-4 py-2 bg-slate-600 text-white rounded-md hover:bg-slate-800"
      >
        Agregar
      </button>
    </div>
  </form>
</template>
<script>
export default {
  name: "TaskCardInput",
  data() {
    return {
      titulo: "",
      descripcion: "",
      estado: "",
      estados: ["pendiente", "en progreso", "completada"],
    };
  },
  methods: {
    add_task(e) {
      e.preventDefault();
      fetch(`http://localhost:8000/api/tareas`, {
        method: "POST",
        body: JSON.stringify({
          titulo: this.titulo,
          descripcion: this.descripcion,
          idProyecto: this.$route.params.id,
          estado: this.estado,
        }),
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          window.location.reload();
          this.titulo = "";
          this.descripcion = "";
        });
    },
  },
};
</script>
