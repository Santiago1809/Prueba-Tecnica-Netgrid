<template>
  <div>
    <!-- Botón para abrir el modal -->
    <button
      @click="openModal"
      :class="`bg-${color}-500 hover:bg-${color}-700 text-white font-bold py-2 px-4 rounded mx-1`"
    >
      <span><i :class="text"></i></span>
    </button>

    <!-- Modal -->
    <div
      v-if="isModalOpen && EditarGrupo"
      class="fixed z-10 inset-0 overflow-y-auto"
    >
      <div class="flex items-center justify-center min-h-screen px-4">
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          aria-hidden="true"
        ></div>
        <div
          class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl"
        >
          <div class="bg-slate-200 px-4 py-5 sm:p-6">
            <!-- Contenido del modal -->
            <h2 class="text-xl font-bold mb-4">Cambiar nombre de grupo</h2>
            <form @submit="actualizarGrupo">
              <input
                type="text"
                :value="nombreGrupo"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                @input="nombreGrupo = $event.target.value"
                @keydown.enter.prevent="actualizarGrupo()"
              />
              <input
                type="text"
                :value="descripcion"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                @input="descripcion = $event.target.value"
                @keydown.enter.prevent="actualizarGrupo()"
              />
              <input
                type="datetime-local"
                class="w-full h-10 px-4 py-2 text-xl rounded-md focus:outline-none focus:border-blue-500"
                placeholder="Fecha inicio"
                :value="formatDateTime(fecha_inicio)"
                @input="fecha_inicio = $event.target.value"
                :min="getCurrentDateTime()"
              />
              <input
                type="datetime-local"
                class="w-full h-10 px-4 py-2 text-xl rounded-md focus:outline-none focus:border-blue-500"
                placeholder="Fecha fin"
                :value="formatDateTime(fecha_fin)"
                @input="fecha_fin = $event.target.value"
                :min="getCurrentDateTime()"
              />
              <div
                class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
              >
                <!-- Botones para cerrar y guardar el modal -->
                <button
                  @click="closeModal"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Cerrar
                </button>
                <button
                  type="submit"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Guardar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="isModalOpen && BorrarGrupo"
      class="fixed z-10 inset-0 overflow-y-auto"
    >
      <div class="flex items-center justify-center min-h-screen px-4">
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          aria-hidden="true"
        ></div>
        <div
          class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl"
        >
          <div class="bg-red-200 px-4 py-5 sm:p-6">
            <!-- Contenido del modal -->
            <h2 class="text-xl font-bold mb-4">Eliminar grupo</h2>
            <p>Está seguro de eliminar el grupo:</p>
            <form @submit="eliminarGrupo">
              <input
                type="text"
                :value="nombreGrupo"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                readonly
                @keydown.enter.prevent="eliminarGrupo"
              />

              <div
                class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
              >
                <!-- Botones para cerrar y guardar el modal -->
                <button
                  @click="closeModal"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Cerrar
                </button>
                <button
                  type="submit"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Eliminar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="isModalOpen && EditarTarea"
      class="fixed z-10 inset-0 overflow-y-auto"
    >
      <div class="flex items-center justify-center min-h-screen px-4">
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          aria-hidden="true"
        ></div>
        <div
          class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl"
        >
          <div class="bg-slate-200 px-4 py-5 sm:p-6">
            <!-- Contenido del modal -->
            <h2 class="text-xl font-bold mb-4">Actalizar tarea</h2>
            <form @submit.prevent="actualizarTarea">
              <input
                type="text"
                :value="tituloTarea"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                @input="tituloTarea = $event.target.value"
                @keydown.enter.prevent="actualizarTarea"
              />
              <input
                type="text"
                :value="descripcionTarea"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                @input="descripcionTarea = $event.target.value"
                @keydown.enter.prevent="actualizarTarea"
              />
              <h3>Cambiar de estado:</h3>
              <select
                name="grupos"
                id="grupos"
                v-model="estado"
                class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
              >
                <option
                  v-for="(estado, index) in estados"
                  :key="index"
                  :value="estado"
                >
                  {{estado }}
                </option>
              </select>
              <div
                class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
              >
                <!-- Botones para cerrar y guardar el modal -->
                <button
                  @click="closeModal"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Cerrar
                </button>
                <button
                  type="submit"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Guardar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="isModalOpen && BorrarTarea"
      class="fixed z-10 inset-0 overflow-y-auto"
    >
      <div class="flex items-center justify-center min-h-screen px-4">
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          aria-hidden="true"
        ></div>
        <div
          class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl"
        >
          <div class="bg-red-200 px-4 py-5 sm:p-6">
            <!-- Contenido del modal -->
            <h2 class="text-xl font-bold mb-4">Eliminar tarea</h2>
            <p>Está seguro de eliminar la tarea:</p>
            <form @submit.prevent="eliminarTarea">
              <input
                type="text"
                :value="tituloTarea"
                class="w-full px-4 pointer-events-none py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                @input="tituloTarea = $event.target.value"
                @keydown.enter.prevent="eliminarTarea"
              />
              <input
                type="text"
                :value="descripcionTarea"
                class="w-full px-4 py-2 pointer-events-none rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                @input="descripcionTarea = $event.target.value"
                @keydown.enter.prevent="eliminarTarea"
              />
              <div
                class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
              >
                <!-- Botones para cerrar y guardar el modal -->
                <button
                  @click="closeModal"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Cerrar
                </button>
                <button
                  type="submit"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Eliminar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isModalOpen: false,
      grupos: [],
      grupoNuevo: this.id,
      nuevoEstado: '',
      estados: ['pendiente', 'en progreso', 'completada'],
      estado: ''
    };
  },
  methods: {
    openModal() {
      this.isModalOpen = true;
      this.getGroups();
    },
    closeModal() {
      this.isModalOpen = false;
    },
    actualizarGrupo() {
      fetch(`http://localhost:8000/api/proyectos/${this.id}`, {
        method: "PUT",
        body: JSON.stringify({
          titulo: this.nombreGrupo,
          descripcion: this.descripcion,
          fecha_inicio: this.fecha_inicio,
          fecha_fin: this.fecha_fin,
        }),
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => response.json())
        .then(() => {
          this.openModal = false;
          window.location.reload();
        });
    },
    eliminarGrupo() {
      fetch(`http://localhost:8000/api/proyectos/${this.id}`, {
        method: "DELETE"
      })
        .then((response) => response.json())
        .then(() => {
          this.openModal = false;
          window.location.reload();
        });
    },
    actualizarTarea() {
      fetch(`http://localhost:8000/api/tareas/${this.id_tarea}`, {
        method: "PUT",
        body: JSON.stringify({
          titulo: this.tituloTarea,
          descripcion: this.descripcionTarea,
          estado: this.estado
        }),
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(this.estado)
          this.openModal = false;
          window.location.reload();
        });
    },
    getGroups() {
      fetch(
        `http://localhost:8000/api/proyectos/${localStorage.getItem("usuario")}`
      )
        .then((response) => response.json())
        .then((data) => {
          data.map((grupo) =>{
            console.log(grupo)
            this.grupos.push({ id_grupo: grupo.id_grupo, nombre: grupo.nombre })
          });
        });
    },
    eliminarTarea() {
      fetch(`http://localhost:8000/api/tareas/${this.id_tarea}`, {
        method: "DELETE",
      })
        .then((response) => response.json())
        .then(() => {
          this.openModal = false;
          window.location.reload();
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
    formatDateTime(datetime) {
      return new Date(datetime).toISOString().slice(0, 16);
    },
  },
  props: {
    text: String,
    EditarGrupo: Boolean,
    BorrarGrupo: Boolean,
    nombreGrupo: String,
    descripcion: String,
    fecha_inicio: Date,
    fecha_fin: Date,
    tituloTarea: String,
    descripcionTarea: String,
    EditarTarea: Boolean,
    BorrarTarea: Boolean,
    id: Number,
    id_tarea: Number,
    color: {
      typeof: String,
      required: true,
    },
  },
};
</script>
