<template>
  <div>
    <b-row class="mt-5 mx-5" v-if="ad">
      <b-col cols="8">
        <!-- img-src="https://images.unsplash.com/photo-1574321756605-4611fe4ed1a6?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1360&q=80" -->
        <b-card
          :title="ad.title"
          :img-src="ad.image"
          img-alt="Image of ad"
          img-top
          class="w-75 mx-auto my-4"
        >
          <b-card-text v-html="ad.description"></b-card-text>

          <b-button @click="redirect('ad', ad.id)" variant="primary">Go to ad</b-button>
        </b-card>
      </b-col>
      <b-col cols="4">
        <b-card
          :title="ad.user.name"
          img-src="https://images.unsplash.com/photo-1574321756605-4611fe4ed1a6?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1360&q=80"
          img-alt="Image of ad"
          img-top
          class="w-50 my-4"
        >
          <b-card-text>
            Location: {{ad.user.location}}
            <br>
            Member since: {{ad.user.created_at || formatDate}}
          </b-card-text>

          <b-button @click="redirect('user', ad.user.id)" variant="primary">Go to user</b-button>
        </b-card>
      </b-col>
    </b-row>
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
        })[0];
      }
    },
    allAds: {
      get() {
        return this.$store.getters.ads;
      }
    }
  },
  methods: {
    redirect(type, id) {
      this.$router.push(`/${type}/${id}`);
    }
  }
};
</script>