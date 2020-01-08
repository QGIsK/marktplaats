<template>
  <div v-if="ad">
    <v-btn @click="featureAd">Feature ad</v-btn>
  </div>
</template>

<script>
export default {
  name: "BoostAd",
  data: () => ({}),
  computed: {
    ad: {
      get() {
        let ad = this.ads.filter(ad => {
          return String(ad.id) === this.$route.params.id;
        })[0];

        if (
          !ad.user_id === this.$store.getters.userId ||
          !this.$store.getters.isAdmin
        )
          return;

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
      this.$store.dispatch("featureAd", {});
    }
  }
};
</script>