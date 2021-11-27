<template>
  <v-app>
    <v-main>
      <Home />
    </v-main>
  </v-app>
</template>

<script>
import Home from "@/views/Home.vue";

export default {
  name: "App",
  components: {
    Home,
  },
  data: () => ({}),
};
</script>

<style>
#app {
  font-family: Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: black;
}

html {
  box-sizing: border-box;
  background: linear-gradient(rgb(0, 0, 114), rgb(0, 0, 216));
}
*,
::after,
::before {
  box-sizing: inherit;
}
.v-main {
  background: url(assets/sky.jpeg) no-repeat center center fixed;
  background-size: cover;
  display: flex;
  justify-content: center;
  align-items: start;
}
body {
  text-rendering: optimizeLegibility;
  max-width: 992px;
  margin-left: auto;
  margin-right: auto;
}
</style>
