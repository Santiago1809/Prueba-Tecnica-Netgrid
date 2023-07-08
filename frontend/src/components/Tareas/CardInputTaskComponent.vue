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
      <select name="estado" id="estado" v-model="estado" class="text-center mb-8" required>
        <option value="" disabled selected hidden>Seleccionar estado</option>
        <option v-for="(state, index) in estados" :key="index" :value="state">
          {{ state }}
        </option>
      </select>
      <select name="responsable" id="responsable" v-model="usuario" class="text-center mb-4" required>
        <option value="" disabled selected hidden>Seleccionar responsable</option>
        <option v-for="(user, index) in usuarios" :value="user.id" :key="index">
          {{ user.name }}
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
      usuario: '',
      usuarios: []
    };
  },
  methods: {
    add_task(e) {
      let user_fetch = JSON.parse(localStorage.getItem('usuario'))
      e.preventDefault();
      fetch(`http://localhost:8000/api/tareas`, {
        method: "POST",
        body: JSON.stringify({
          idUsuario: user_fetch.id,
          titulo: this.titulo,
          descripcion: this.descripcion,
          idProyecto: this.$route.params.id,
          estado: this.estado,
          responsable: this.usuario
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
    get_Users() {
      fetch('http://localhost:8000/api/users')
      .then(response=>response.json())
      .then((data)=>{
        data.map((user)=>{
          if(user.rol=='usuario'){
            this.usuarios.push({id: user.id, name: user.name});
          }
        })
      })
    }
  },
  mounted() {
      this.get_Users();
      
    },
};
</script>
