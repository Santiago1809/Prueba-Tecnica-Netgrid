<template>
  <section class="h-screen">
    <LogoutButton />
    <div class="h-full flex">
      <!-- Left column container with background -->
      <div class="w-full lg:w-6/12 xl:w-6/12 bg-slate-600">
        <div class="h-full flex items-center justify-center">
          <h1 class="text-9xl text-white">Proyectos</h1>
        </div>
      </div>

      <!-- Right column container -->
      <div class="w-full lg:w-6/12 xl:w-6/12 overflow-y-auto">
        <div class="flex items-start justify-start my-4 ml-4">
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
                  <ul class="space-y-2">
                    <li>
                      <label for="filtroNombre">
                        <i class="fas fa-font mr-2"></i> Filtrar por nombre
                      </label>
                      <input
                        id="filtroNombre"
                        type="text"
                        class="w-full h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500"
                        placeholder="Ingrese un nombre"
                        v-model="filtroNombre"
                      />
                    </li>
                    <li>
                      <label for="filtroDescripcion">
                        <i class="fas fa-file-alt mr-2"></i> Filtrar por
                        descripción
                      </label>
                      <input
                        id="filtroDescripcion"
                        type="text"
                        class="w-full h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500"
                        placeholder="Ingrese una descripción"
                        v-model="filtroDescripcion"
                      />
                    </li>
                    <li>
                      <label for="filtroFechaInicio">
                        <i class="fas fa-calendar-day mr-2"></i> Filtrar por
                        fecha de inicio
                      </label>
                      <div class="flex items-center">
                        <input
                          id="filtroFechaInicio"
                          type="date"
                          class="w-full h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500"
                          v-model="filtroFechaInicio"
                        />
                        <button
                          type="button"
                          class="ml-2 px-2 py-1 text-sm text-white bg-red-500 rounded-md hover:bg-red-700 focus:outline-none"
                          @click="clearFiltroFechaInicio"
                        >
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </li>
                    <li>
                      <label for="filtroFechaFin">
                        <i class="fas fa-calendar-day mr-2"></i> Filtrar por
                        fecha de fin
                      </label>
                      <div class="flex items-center">
                        <input
                          id="filtroFechaFin"
                          type="date"
                          class="w-full h-10 px-2 py-1 text-xl rounded-md focus:outline-none focus:border-blue-500"
                          v-model="filtroFechaFin"
                        />
                        <button
                          type="button"
                          class="ml-2 px-2 py-1 text-sm text-white bg-red-500 rounded-md hover:bg-red-700 focus:outline-none"
                          @click="clearFiltroFechaFin"
                        >
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </li>
                  </ul>
                </div>
              </ul>
            </div>
          </div>
        </div>
        <div v-for="(grupo, index) in gruposFiltrados" :key="index">
          <Card
            :nombreGrupo="grupo.nombre"
            :ruta="`/tasks/${grupo.id}`"
            :id="grupo.id"
            :descripcion="grupo.descripcion"
            :fecha_inicio="grupo.fecha_inicio"
            :fecha_fin="grupo.fecha_fin"
          />
        </div>
        <CardInput @grupo-agregado="agregarGrupo" />
      </div>
    </div>
  </section>
</template>

<script>
import Card from "@/components/CardComponent.vue";
import CardInput from "@/components/CardInputComponent.vue";
import LogoutButton from "@/components/LogOutButtonComponent.vue";

export default {
  name: "IndexPage",
  data() {
    return {
      grupos: [],
      filtroNombre: "",
      filtroDescripcion: "",
      filtroFechaInicio: "",
      filtroFechaFin: "",
      showDropdown: false,
    };
  },
  components: {
    LogoutButton,
    Card,
    CardInput,
  },
  mounted() {
    this.getGroups();
    document.title = "Grupos";
  },
  methods: {
    async getGroups() {
      await fetch(
        `http://localhost:8000/api/proyectos/${localStorage.getItem("usuario")}`
      )
        .then((response) => response.json())
        .then((data) => {
          data.map((group) => {
            this.grupos.push({
              nombre: group.titulo,
              id: group.id,
              descripcion: group.descripcion,
              fecha_inicio: group.fecha_inicio,
              fecha_fin: group.fecha_fin,
            });
          });
        });

      this.$emit("grupos-cargados", this.grupos);
    },
    agregarGrupo(grupo) {
      this.grupos.push({ nombre: grupo, id: this.grupos.length + 1 });
    },
    clearFiltroFechaInicio() {
      this.filtroFechaInicio = "";
    },
    clearFiltroFechaFin() {
      this.filtroFechaFin = "";
    },
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
  },
  computed: {
    gruposFiltrados() {
      const filtroNombre = this.filtroNombre.toLowerCase();
      const filtroDescripcion = this.filtroDescripcion.toLowerCase();
      const filtroFechaInicio = this.filtroFechaInicio;
      const filtroFechaFin = this.filtroFechaFin;

      return this.grupos.filter((grupo) => {
        const nombre = grupo.nombre.toLowerCase();
        const descripcion = grupo.descripcion.toLowerCase();
        const fechaInicio = grupo.fecha_inicio;
        const fechaFin = grupo.fecha_fin;

        return (
          nombre.includes(filtroNombre) &&
          descripcion.includes(filtroDescripcion) &&
          (filtroFechaInicio === "" || fechaInicio >= filtroFechaInicio) &&
          (filtroFechaFin === "" || fechaFin <= filtroFechaFin)
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
