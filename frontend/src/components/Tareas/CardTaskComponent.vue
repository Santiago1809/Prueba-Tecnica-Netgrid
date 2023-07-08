<template>
  <div
    class="max-w-lg rounded overflow-hidden shadow-lg hover:bg-gray-300 cursor-pointer mx-6 my-2 relative"
  >
    <div class="px-6 py-4">
      <div class="font-bold text-xl mb-2">{{ nombreTarea }}</div>
      <small class="overflow-hidden text-xs w-1/2 block">{{ descripcionTarea }}</small>
      <small class="overflow-hidden text-sm"><strong>Estado:</strong> {{ estado }}</small>
    </div>
    <div class="absolute right-4 bottom-4 flex">
      <div class="mb-6">
        <ModalComponent
          :text="'fa-solid fa-pen-to-square'"
          :tituloTarea="nombreTarea"
          :descripcionTarea="descripcionTarea"
          :id="id_grupo"
          :id_tarea="id_tarea"
          :estado="estado"
          :EditarTarea="true"
          color="blue"
          @open-modal="openModal"
        />
      </div>
      <div>
        <ModalComponent
          :text="'fa-solid fa-trash'"
          :BorrarTarea="true"
          :id_tarea="id_tarea"
          :tituloTarea="nombreTarea"
          :descripcionTarea="descripcionTarea"
          :responsable="responsable"
          color="red"
          v-show="show"
        />
      </div>
    </div>
  </div>
</template>
<script>
import ModalComponent from '../ModalComponent.vue';
export default {
  name: "TaskCard",
  data(){
    return {
      show: true
    }
  },
  props: {
    id_tarea: Number,
    id_grupo: Number,
    nombreTarea: String,
    descripcionTarea: String,
    estado: String,
    responsable: Number

  },
  components: {
    ModalComponent,
  },
  mounted(){
    let user = JSON.parse(localStorage.getItem('usuario'))
    if(user.rol!=='administrador'){
      this.show = false
    }
  }
};
</script>
