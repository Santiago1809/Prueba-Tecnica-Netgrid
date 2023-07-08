<template>
  <div>
    <div class="fixed top-0 right-0 mt-4 mr-4" ref="container">
      <div class="relative inline-block text-left">
        <div>
          <button
            type="button"
            class="inline-flex justify-center items-center w-8 h-8 rounded-full bg-blue-500 text-white focus:outline-none"
            @click="toggleDropdown"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              class="h-4 w-4"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              ></path>
            </svg>
          </button>
        </div>
        <div
          v-show="showDropdown"
          class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
          ref="dropdown"
        >
          <div
            class="py-1"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="options-menu"
          >
            <a
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              role="menuitem"
              @click="logOut"
            >
              <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "LogOutButton",
  data() {
    return {
      showDropdown: false,
    };
  },
  mounted() {
    document.addEventListener("click", this.closeDropdownOnClickOutside);
  },
  beforeDestroy() {
    document.removeEventListener("click", this.closeDropdownOnClickOutside);
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
    closeDropdownOnClickOutside(event) {
      const container = this.$refs.container;
      const dropdown = this.$refs.dropdown;

      if (
        this.showDropdown &&
        container &&
        dropdown &&
        !container.contains(event.target) &&
        !dropdown.contains(event.target)
      ) {
        this.showDropdown = false;
      }
    },
    logOut() {
      localStorage.removeItem("usuario");
      this.$router.push("/");
    },
  },
};
</script>