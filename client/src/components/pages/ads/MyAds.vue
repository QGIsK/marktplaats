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
          <b-form-input v-model="search" class="w-75 mx-auto" size="lg" placeholder="Search"></b-form-input>
        </b-form>
      </b-container>
    </b-jumbotron>

    <b-container>
      <b-row v-if="Object.keys(ads).length">
        <b-col v-for="ad in ads" :key="ad.id">
          <b-card
            :title="ad.title"
            :img-src="ad.image[0]"
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
  name: "MyAds",
  data() {
    return { search: "" };
  },
  computed: {
    ads: {
      get() {
        let ads = this.$store.getters.ads.filter(
          ad => ad.user.id === this.userId
        );

        // for (let i = 0; ads > i; i++) {
        //   let img = ads[i].image;
        //   img = img.replace('"', "").replace('"', "");
        //   img = img.split(",");
        //   ads[i].image = img;
        // }

        if (this.search.length > 0) {
          ads = ads.filter(ad =>
            ad.title.toLowerCase().includes(this.search.toLowerCase)
          );
        }

        return ads;
      }
    },
    userId: {
      get() {
        return this.$store.getters.userId;
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

