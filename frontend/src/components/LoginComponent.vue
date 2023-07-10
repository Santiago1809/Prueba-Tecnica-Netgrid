<template>
  <section class="h-screen">
    <div class="h-full flex">
      <!-- Left column container with background -->
      <div class="w-full lg:w-6/12 xl:w-6/12 bg-slate-600">
        <div class="h-full flex-col flex items-center justify-center">
          <h1 class="text-9xl text-white">VueNotes</h1>
          <i class="fa-solid fa-note-sticky block text-white text-8xl"></i>
        </div>
      </div>

      <!-- Right column container -->
      <div
        class="w-full lg:w-6/12 xl:w-6/12 overflow-y-auto flex items-center justify-center"
      >
        <div class="w-full max-w-md">
          <div class="bg-gray-300 shadow rounded px-8 pt-6 pb-8 mb-4">
            <h2
              class="text-2xl font-semibold mb-6 text-center"
              v-show="!registro"
            >
              Inicio de sesión
            </h2>
            <h2
              class="text-2xl font-semibold mb-6 text-center"
              v-show="registro"
            >
              Registro
            </h2>
            <form
              @submit.prevent="
                registro ? registerForm($event) : loguinForm($event)
              "
            >
              <div class="mb-4">
                <label
                  class="block text-gray-700 text-sm font-bold mb-2"
                  for="nombre"
                  v-show="registro"
                >
                  Nombre
                </label>
                <input
                  v-show="registro"
                  type="text"
                  id="nombre"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                  placeholder="Nombre"
                  v-model="nombre"
                />
              </div>
              <div class="mb-4">
                <label
                  class="block text-gray-700 text-sm font-bold mb-2"
                  for="email"
                >
                  Correo
                </label>
                <input
                  type="email"
                  id="email"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                  placeholder="Correo"
                  v-model="correo"
                />
              </div>
              <div class="mb-6">
                <label
                  class="block text-gray-700 text-sm font-bold mb-2"
                  for="password"
                >
                  Contraseña
                </label>
                <input
                  type="password"
                  id="password"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                  placeholder="Contraseña"
                  v-model="contraseña"
                />
              </div>
              <div class="flex items-center justify-center">
                <button
                  type="submit"
                  class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700"
                >
                  {{ registro ? "Registrarme" : "Ingresar" }}
                </button>
              </div>
            </form>
          </div>
          <div class="text-center">
            <p class="text-gray-700">
              {{ registro ? "Ya tienes cuenta?" : "No estás registrado?" }}
              <span
                class="text-blue-500 cursor-pointer"
                @click="registro = !registro"
              >
                {{ registro ? "Inicia sesión aquí" : "Regístrate aquí" }}
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  name: "Login",
  data() {
    return {
      correo: "",
      contraseña: "",
      nombre: "",
      registro: false,
    };
  },
  watch: {
    registro(newRegistro) {
      this.getTitle();
    },
  },
  methods: {
    loguinForm() {
      fetch('http://localhost:8000/api/users', {
        method: "POST",
        body: JSON.stringify({
          email: this.correo,
          password: this.contraseña,
        }),
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data)
          if(data['mensaje']=="Successfully logged"){
            localStorage.setItem('usuario', JSON.stringify({id:data.usuario.id, rol:data.usuario.rol}))
            this.$router.push('/home');
          }
        }).catch((err) => {
            console.log(err)
        })
    },
    registerForm() {
        let data = JSON.stringify({
          name: this.nombre,
          email: this.correo,
          password: this.contraseña,
        })
      fetch("http://localhost:8000/api/users/register", {
        method: "POST",
        body: data,
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error en la solicitud");
          }
          return response.json();
        })
        .then((data) => {
          if(data.mensaje=='Usuario creado con éxito'){
            localStorage.setItem('usuario', data.usuario.id);
            this.$router.push('/home')
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getTitle() {
      document.title = this.registro ? "Registro" : "Inicio de sesión";
    },
    lookSesionActive() {
      if (localStorage.getItem("usuario")) {
        this.$router.push("/");
      }
    },
  },
  mounted() {
    this.getTitle();
    this.lookSesionActive();
  },
};
</script>
