<template>
  <div>
    <b-jumbotron
      bg-variant="dark"
      style="border-radius: 0"
      text-variant="white"
      border-variant="dark"
    >
      <b-container>
        <template v-slot:header>Search</template>

        <b-form class="mx-auto">
          <b-form-input class="w-75 mx-auto" size="lg" placeholder="Search"></b-form-input>
        </b-form>
      </b-container>
    </b-jumbotron>

    <!-- img-src="https://picsum.photos/600/300/?image=25" -->
    <b-container>
      <b-row v-if="Object.keys(ads).length">
        <b-col v-for="ad in ads" :key="ad.id">
          <!-- {{ad}} -->
          <b-card
            :title="ad.title"
            :img-src="ad.image"
            img-alt="Image of ad"
            img-top
            style="min-width: 20rem; max-width: 20rem;"
            class="mx-auto my-4"
          >
            <b-card-text>{{ad.description}}</b-card-text>

            <b-button @click="redirect('ad', ad.id)" variant="primary">Go to ad</b-button>
          </b-card>
        </b-col>
      </b-row>
      <b-row v-else>
        <b-col>
          <b-card
            title="No ads yet"
            img-src="https://picsum.photos/600/300/?image=25"
            img-alt="Image of ad"
            img-top
            style="min-width: 20rem; max-width: 20rem;"
            class="mx-auto my-4"
          >
            <b-card-text>Theres no ads listed at this time.</b-card-text>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {};
  },
  computed: {
    ads: {
      get() {
        return this.$store.getters.ads;
      }
    }
  },
  mounted() {
    this.$store.dispatch("getAds");
  },
  methods: {
    redirect(type, id) {
      this.$router.push(`/${type}/${id}`);
    }
  }
};
</script>

