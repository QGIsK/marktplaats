<template>
  <div v-if="ad">
    <b-button @click="featureAd">Feature ad</b-button>
  </div>
</template>

<script>
export default {
  name: "BoostAd",
  data: () => ({}),
  computed: {
    ad: {
      get() {
        let ad = this.$store.getters.ads.filter(ad => {
          return String(ad.id) === this.$route.params.id;
        })[0];

        return ad;
      }
    },
    isAdOwner: {
      get() {
        if (!this.ad) return false;
        return (
          this.$store.getters.isAdmin ||
          this.$store.getters.userId === this.ad.user_id
        );
      }
    }
  },
  methods: {
    featureAd() {
      this.$store.dispatch("featureAd", { ad: this.ad });
    }
  }
};
</script>