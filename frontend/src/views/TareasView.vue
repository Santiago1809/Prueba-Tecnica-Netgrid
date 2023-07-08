<template>
  <div>
    <LogOutButtonComponent />
    <section class="h-screen">
      <div class="h-full flex">
        <!-- Left column container with background -->
        <div class="w-full lg:w-6/12 xl:w-6/12 bg-slate-600">
          <div class="h-full flex items-center justify-center">
            <h1 class="text-7xl text-white">{{ nombreGrupo }}</h1>
          </div>
        </div>

        <!-- Right column container -->
        <div class="w-full lg:w-6/12 xl:w-6/12 overflow-y-auto bg-gray-100">
          <div class="h-full flex flex-col justify-center p-4">
            <div class="flex items-center mb-4">
              <div class="custom-dropdown">
                <button class="dropdown-button" @click="toggleDropdown">
                  <span class="flex items-center">
                    <i class="fas fa-filter text-xl mr-2"></i> Filtros
                  </span>
                  <i
                    class="fas fa-chevron-down ml-2"
                    :class="{ rotate: showDropdown }"
                  ></i>
                </button>
                <div class="dropdown-content" v-show="showDropdown">
                  <ul class="space-y-2">
                    <div class="dropdown-content" v-show="showDropdown">
                      <div class="flex items-center mb-2">
                        <label for="filtroTitulo" class="mr-2">
                          <i class="fas fa-font mr-1"></i> Filtrar por título
                        </label>
                        <input
                          id="filtroTitulo"
                          type="text"
                          class="w-64 h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500"
                          placeholder="Ingrese un título"
                          v-model="filtroTitulo"
                        />
                      </div>
                      <div class="flex items-center mb-2">
                        <label for="filtroDescripcion" class="mr-2">
                          <i class="fas fa-file-alt mr-1"></i> Filtrar por
                          descripción
                        </label>
                        <input
                          id="filtroDescripcion"
                          type="text"
                          class="w-64 h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500"
                          placeholder="Ingrese una descripción"
                          v-model="filtroDescripcion"
                        />
                      </div>
                      <div class="flex items-center">
                        <label for="filtroEstado" class="mr-2">
                          <i class="fas fa-check-circle mr-1"></i> Filtrar por
                          estado
                        </label>
                        <select
                          id="filtroEstado"
                          class="w-64 h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500"
                          v-model="filtroEstado"
                        >
                          <option value="">Todos</option>
                          <option value="pendiente">Pendiente</option>
                          <option value="en progreso">En Progreso</option>
                          <option value="completada">Completada</option>
                        </select>
                      </div>
                    </div>
                  </ul>
                </div>
              </div>
            </div>
            <div v-for="(tarea, index) in tareasFiltradas" :key="index">
              <Card
                :id_grupo="tarea.idProyecto"
                :id_tarea="tarea.id"
                :nombreTarea="tarea.titulo"
                :descripcionTarea="tarea.descripcion"
                :estado="tarea.estado"
                :responsable="tarea.responsable"
              />
            </div>
            <CardInput @tarea-agregado="agregarTarea" v-show="show" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import LogOutButtonComponent from "@/components/LogOutButtonComponent.vue";
import CardInput from "../components/Tareas/CardInputTaskComponent.vue";
import Card from "../components/Tareas/CardTaskComponent.vue";

export default {
  name: "TaskCard",
  data() {
    return {
      tareas: [],
      nombreGrupo: "",
      filtroTitulo: "",
      filtroDescripcion: "",
      filtroEstado: "",
      showDropdown: false,
      show: true,
    };
  },
  mounted() {
    this.getGroup();
    this.getTasks();
    document.title = "Tareas";
    let user = JSON.parse(localStorage.getItem("usuario"));
    if (user.rol !== "administrador") {
      this.show = false;
    }
  },
  components: {
    LogOutButtonComponent,
    Card,
    CardInput,
  },
  methods: {
    getTasks() {
      const user = JSON.parse(localStorage.getItem("usuario"));
      fetch(`http://localhost:8000/api/tareas/${this.$route.params.id}`)
        .then((response) => response.json())
        .then((data) => {
          data.tareas.map((item) => {
            if (user.rol == "usuario" && item.responsable == user.id) {
              this.tareas.push({
                idProyecto: item.idProyecto,
                id: item.id,
                titulo: item.titulo,
                descripcion: item.descripcion,
                estado: item.estado,
                responsable: item.responsable,
              });
            } else if (user.rol == "administrador") {
              this.tareas.push({
                idProyecto: item.idProyecto,
                id: item.id,
                titulo: item.titulo,
                descripcion: item.descripcion,
                estado: item.estado,
                responsable: item.responsable,
              });
            }
          });
        });
    },
    getGroup() {
      fetch(`http://localhost:8000/api/tareas/${this.$route.params.id}`)
        .then((response) => response.json())
        .then((data) => {
          this.nombreGrupo = data.proyecto;
        });
    },
    agregarTarea(tarea) {
      this.tareas.push({
        id_grupo: tarea.id_grupo,
        id_tarea: tarea.id_tarea,
        titulo: tarea.titulo,
        descripcion: tarea.descripcion,
      });
    },
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
  },
  computed: {
    tareasFiltradas() {
      const filtroTitulo = this.filtroTitulo.toLowerCase();
      const filtroDescripcion = this.filtroDescripcion.toLowerCase();
      const filtroEstado = this.filtroEstado.toLowerCase();

      return this.tareas.filter((tarea) => {
        const titulo = tarea.titulo.toLowerCase();
        const descripcion = tarea.descripcion.toLowerCase();
        const estado = tarea.estado.toLowerCase();

        return (
          titulo.includes(filtroTitulo) &&
          descripcion.includes(filtroDescripcion) &&
          (filtroEstado === "" || estado === filtroEstado)
        );
      });
    },
  },
};
</script>

<style>
.custom-dropdown summary::-webkit-details-marker {
  display: none;
}
.rotate {
  transform: rotate(180deg);
}
</style>
