<template>
    <div>
        {{ad}}

        <!-- {{allAds}} -->
    </div> 
</template>

<script>
export default {
  name: "ShowAd",

  computed: {
    ad: {
      get() {
        return this.allAds.filter(ad => {
          return String(ad.id) === this.$route.params.id;
        });
      }
    },
    allAds: {
      get() {
        return this.$store.getters.ads
      }
    }
  }
}
</script>