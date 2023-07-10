<template>
  <form
    class="max-w-lg rounded overflow-hidden shadow-lg mt-4"
    @submit.prevent="add_grupo()"
  >
    <div class="px-6 py-4">
      <div class="mb-4">
        <h2 class="text-2xl text-center py-2">Agregar proyecto</h2>

        <input
          type="text"
          class="w-full h-10 px-4 py-2 text-xl rounded-md focus:outline-none focus:border-blue-500"
          placeholder="Agrega un nuevo titulo"
          v-model="nuevo_grupo"
        />
      </div>
      <div class="mb-4">
        <input
          type="text"
          class="w-full h-10 px-4 py-2 text-xl rounded-md focus:outline-none focus:border-blue-500"
          placeholder="Agrega la descripción"
          v-model="nueva_descripcion"
        />
      </div>
      <div class="mb-4">
        <input
          type="datetime-local"
          class="w-full h-10 px-4 py-2 text-xl rounded-md focus:outline-none focus:border-blue-500"
          placeholder="Fecha inicio"
          v-model="fecha_inicio"
          :min="getCurrentDateTime()"
        />
      </div>
      <div class="mb-4">
        <input
          type="datetime-local"
          class="w-full h-10 px-4 py-2 text-xl rounded-md focus:outline-none focus:border-blue-500"
          placeholder="Fecha fin"
          v-model="fecha_fin"
          :min="getCurrentDateTime()"
        />
      </div>
      <div class="flex justify-center">
        <button
          type="submit"
          class="px-4 py-2 bg-slate-600 text-white rounded-md hover:bg-slate-800"
        >
          Agregar
        </button>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  name: "InputCardComponent",
  data() {
    return {
      nuevo_grupo: "",
      nueva_descripcion: "",
      fecha_inicio: "",
      fecha_fin: "",
    };
  },
  methods: {
    add_grupo() {
      let usuario = JSON.parse(localStorage.getItem("usuario"));
      console.log(usuario)
      fetch(`http://localhost:8000/api/proyectos`, {
        method: "POST",
        body: JSON.stringify({
          idUsuario:usuario.id,
          titulo: this.nuevo_grupo,
          descripcion: this.nueva_descripcion,
          fecha_inicio: this.fecha_inicio,
          fecha_fin: this.fecha_fin
        }),
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          window.location.reload();
          this.nuevo_grupo = "";
        });
    },
    getCurrentDateTime() {
      const now = new Date();
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, "0");
      const day = String(now.getDate()).padStart(2, "0");
      const hour = String(now.getHours()).padStart(2, "0");
      const minute = String(now.getMinutes()).padStart(2, "0");
      return `${year}-${month}-${day}T${hour}:${minute}`;
    },
  },
};
</script>
