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
          <b-form-input v-model="search" class="w-50 mx-auto" size="lg" placeholder="Search"></b-form-input>
        </b-form>
        <b-form inline class="mt-3 mx-auto" @submit.prevent="searchDistance()">
          <div class="mx-auto">
            <b-form-input v-model="postal" prepend class="w-50" size="lg" placeholder="Postal code"></b-form-input>
            <b-form-select
              :value="null"
              class="ml-3"
              v-model="maxDistance"
              :options="{ '5': '5 < ', '10': '10 < ', '15': '15 < ', '20': '20 <', '25': '25 <' }"
              id="inline-form-custom-select-pref"
            >
              <template v-slot:first>
                <option :value="null">Distance</option>
              </template>
            </b-form-select>
            <div class="mx-auto mt-3">
              <b-form-group>
                <b-form-checkbox-group v-model="filterCategories">
                  <span v-for="cat in categories" :key="cat.id">
                    <b-form-checkbox :value="cat.id">{{cat.tag}}</b-form-checkbox>
                  </span>
                </b-form-checkbox-group>
              </b-form-group>
            </div>
          </div>
        </b-form>
      </b-container>
    </b-jumbotron>

    <!-- img-src="https://picsum.photos/600/300/?image=25" -->

    <b-container>
      <b-row v-if="Object.keys(ads).length">
        <b-card-group columns>
          <b-col class="mr-3" v-for="ad in ads" :key="ad.id">
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
      currentPage: 1,
      filterCategories: [],
      postal: "",
      maxDistance: null
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

        return this.allAds.slice(min, max);
      }
    },
    categories: {
      get() {
        return this.$store.getters.categories;
      }
    },
    allAds: {
      get() {
        let ads = this.$store.getters.ads;
        if (this.search.length > 0) {
          ads = ads.filter(ad =>
            ad.title.toLowerCase().includes(this.search.toLowerCase())
          );
        }

        if (this.filterCategories.length > 0) {
          ads = ads.filter(ad =>
            ad.categories.find(category => {
              return this.filterCategories.includes(category.id);
            })
          );
        }

        return ads;
      }
    }
  },
  mounted() {
    // this.$store.dispatch("getAds");
  },
  methods: {
    redirect(type, id) {
      this.$router.push(`/${type}/${id}`);
    },
    searchDistance() {
      this.$http({
        url: "/api/ads/search",
        method: "POST",
        data: {
          postalCode: this.postal,
          distance: this.maxDistance
        }
      })
        .then(res => {
          let ads = res.data;

          for (let i = 0; ads.length > i; i++) {
            let img = ads[i].image;
            if (img === null) continue;
            img = img.replace('"', "").replace('"', "");
            img = img.split(",");
            ads[i].image = img;
          }

          this.$store.state.ads = ads;
        })
        .catch(e => console.log(e));
    }
  }
};
</script>
