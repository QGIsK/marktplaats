<template>
  <div>
    <b-jumbotron
      bg-variant="dark"
      style="border-radius: 0"
      text-variant="white"
      border-variant="dark"
      id="top"
    >
      <b-container>
        <h1 class="text-center mb-5">Home</h1>

        <b-form class="mx-auto">
          <b-form-input v-model="search" class="w-75 mx-auto" size="lg" placeholder="Search"></b-form-input>
        </b-form>
      </b-container>
    </b-jumbotron>

    <!-- img-src="https://picsum.photos/600/300/?image=25" -->
    <b-container>
      <b-row v-if="Object.keys(ads).length">
        <b-card-group columns>
          <b-col v-for="ad in ads" :key="ad.id">
            <!-- {{ad}} -->
            <b-card
              :title="ad.title"
              :img-src="ad.image[0]"
              img-alt="Image of ad"
              img-top
              style="min-width: 20rem; max-width: 20rem;"
              class="mx-auto my-4"
            >
              <b-card-text v-html="ad.description"></b-card-text>
              <b-button @click="redirect('ad', ad.id)" variant="primary">View Ad</b-button>
            </b-card>
          </b-col>
        </b-card-group>
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
      <b-row v-if="Object.keys(ads).length">
        <b-col cols="8">
          <b-pagination
            class="ml-5 my-4"
            v-model="currentPage"
            v-if="ads.length > perPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="my-table"
          ></b-pagination>
        </b-col>
        <b-col cols="4">
          <b-button
            class="mr-5 my-4 float-right"
            color="secondary"
            v-scroll-to="'#navbar'"
          >Back to top</b-button>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      search: "",
      perPage: 25,
      currentPage: 1
    };
  },
  computed: {
    rows: {
      get() {
        return this.allAds.length / 1;
      }
    },
    ads: {
      get() {
        const max = this.currentPage * this.perPage;
        const min = max - this.perPage;

        return this.$store.getters.ads.slice(min, max);
      }
    },
    allAds: {
      get() {
        let ads = this.$store.getters.ads;
        if (!ads || ads == null) return;
        for (let i = 0; ads > i; i++) {
          if (ads[i].image == null)
            return (ads[i].image =
              "https://images.unsplash.com/photo-1578333607401-9e1f5503fd11?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80");
          let img = ads[i].image;
          img = img.replace('"', "").replace('"', "");
          img = img.split(",");
          ads[i].image = img;
        }

        if (this.search.length > 0)
          return this.$store.getters.ads.filter(ad =>
            ad.title.toLowerCase().includes(this.search.toLowerCase())
          );

        // console.log(this.$store.getters.ads[0]);
        // if (this.$store.getters.ads) {
        //   this.$store.getters.ads.forEach(ad => {
        //     let image = ad.image;
        //     image = image.replace('"', "").replace('"', "");
        //     image = image.split(",");
        //     ad.image = image;
        //   });
        // }

        return this.$store.getters.ads.slice(0, 25);
      }
    }
  },
  mounted() {
    // this.$store.dispatch("getAds");
  },
  methods: {
    redirect(type, id) {
      this.$router.push(`/${type}/${id}`);
    }
  }
};
</script>

