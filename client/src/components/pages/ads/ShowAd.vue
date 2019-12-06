<template>
  <div>
    <b-row class="mt-5 mx-5" v-if="ad">
      <b-col cols="8">
        <b-card
          :title="ad.title"
          class="w-75 mx-auto my-4"
          :img-src="ad.image[0]"
          img-alt="Image of ad"
          img-top
        >
          <b-card-text v-html="ad.description"></b-card-text>

          <b-button v-if="isAdOwner" v-b-modal.deleteModal variant="danger" class="mr-2">Delete</b-button>
          <b-button v-if="isAdOwner" v-b-modal.editModal variant="info">Edit</b-button>
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

          <b-button @click="redirect('user', ad.user.id)" variant="primary">View profile</b-button>
        </b-card>
        <!-- <b-col cols="4"> -->

        <b-card class="w-50 mt-5 my-4" v-if="isAdVisitor">
          <b-form @submit.prevent="addBid">
            <b-form-group id="input-group-1" label="Bid on item" label-for="input-1">
              <b-form-input
                id="input-2"
                v-model="newBid"
                type="text"
                required
                placeholder="Enter amount"
              ></b-form-input>
            </b-form-group>
            <b-button type="submit" variant="primary">Bid</b-button>
          </b-form>
        </b-card>
        <b-card class="w-50 mt-5 my-4" title="Bids on item">
          <b-table striped hover :items="items"></b-table>
        </b-card>
        <!-- </b-col> -->
      </b-col>
    </b-row>
    <b-row class="mt-5 mx-5" v-if="ad">
      <b-col cols="8">
        <b-card title="Images of item" class="w-75 h-50 mx-auto my-4">
          <b-carousel
            id="header-slider"
            v-model="slide"
            :interval="4000"
            controls
            background="transparent"
            @sliding-start="onSlideStart"
            @sliding-end="onSlideEnd"
          >
            <b-carousel-slide v-for="image in ad.image" :key="image" :img-src="image"></b-carousel-slide>
          </b-carousel>
        </b-card>
      </b-col>
      <!-- <b-col cols="4">
        <b-card class="w-50 my-4" title="Bids on item">
          <b-table striped hover :items="items"></b-table>
        </b-card>
      </b-col>-->
    </b-row>

    <b-modal id="deleteModal" title="Delete this ad" v-if="ad && isAdOwner" hide-footer>
      <span>
        <p>
          Are you absolutely sure you want to delete
          <b>{{ad.title}}</b>?
          <br>
          <small class="secondary">There's no going back afterwards..</small>
        </p>
        <b-button @click="deletePost()" variant="danger" class="mr-2">Delete Post</b-button>
        <b-button @click="$bvModal.hide('model-1')" variant="info">Cancel</b-button>
      </span>
    </b-modal>
  </div>
</template>

<script>
// import axios from "axios";

export default {
  name: "ShowAd",
  data() {
    return {
      slide: 0,
      sliding: null,
      items: [
        { user: "Demiann", amount: "100$" },
        { user: "Demiann", amount: "100$" },
        { user: "Demiann", amount: "100$" },
        { user: "Demiann", amount: "100$" },
        { user: "Demiann", amount: "100$" },
        { user: "Demiann", amount: "100$" }
      ],
      newBid: ""
    };
  },
  computed: {
    ad: {
      get() {
        return this.ads.filter(ad => {
          return String(ad.id) === this.$route.params.id;
        })[0];
      }
    },
    ads: {
      get() {
        return this.$store.getters.ads;
      }
    },
    isAdOwner: {
      get() {
        return (
          this.$store.getters.isAdmin ||
          this.$store.getters.userId === this.ad.user_id
        );
      }
    },
    isAdVisitor: {
      get() {
        return this.$store.getters.userId !== this.ad.user_id;
      }
    }
  },
  methods: {
    onSlideStart() {
      this.sliding = true;
    },
    onSlideEnd() {
      this.sliding = false;
    },
    redirect(type, id) {
      this.$router.push(`/${type}/${id}`);
    },
    deletePost() {
      this.$store
        .dispatch("deleteAd", { ad: this.ad })
        .then(() => {
          this.$bvToast.toast(`Ad has been deleted`, {
            title: `Success`,
            variant: "success",
            solid: true,
            autoHideDelay: 5000
          });

          let i = this.ads.findIndex(x => x.id == this.ad.id);
          this.ads.splice(i, 1);

          this.$router.push("/");
        })
        .catch(err => {
          console.log(err);
          this.$bvToast.toast(`An error has occured, Please try again later.`, {
            title: `Error`,
            variant: "danger",
            solid: true,
            autoHideDelay: 5000
          });
        });
    }
  }
};
</script>

