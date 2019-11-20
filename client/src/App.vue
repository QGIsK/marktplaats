<template>
  <div id="app">
    <Navbar/>
    <router-view/>
  </div>
</template>

<script>
import Navbar from "@/components/layout/Navbar";

export default {
  name: "app",
  components: {
    Navbar
  },
  computed: {
  },
  mounted() {
    this.$store.dispatch('getAds');
  },
};
</script>

