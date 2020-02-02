<template>
  <div></div>
</template>


<script>
export default {
  name: "error",
  mounted() {
    this.notFound();
  },
  methods: {
    notFound: () => {
      this.$bvToast.toast(`404 Page not found`, {
        title: `Error`,
        variant: "danger",
        solid: true,
        autoHideDelay: 5000
      });
      this.$router.push("/");
    }
  }
};
</script>