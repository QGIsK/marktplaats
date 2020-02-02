<template>
  <div>
    <b-row class="mt-5 mx-5" v-if="ad">
      <b-col cols="8">
        <b-card class="w-75 mx-auto" outlined>
          <div class="mx-auto mt-2">
            <h2 class>{{ad.title}}</h2>
            <span v-for="category in ad.categories" :key="category.id">
              <b-badge variant="info" pill class="mr-2">{{category.tag}}</b-badge>
            </span>
          </div>
          <!-- :title="ad.title" -->
          <div class="w-100 mx-auto my-4" img-alt="Image of ad" img-top>
            <!-- :img-src="ad.image[0]" -->

            <b-carousel
              v-if="ad.image.length > 1"
              v-model="slide"
              :interval="4000"
              controls
              background="transparent"
              @sliding-start="onSlideStart"
              @sliding-end="onSlideEnd"
            >
              <b-carousel-slide v-for="image in ad.image" :key="image" :img-src="image"></b-carousel-slide>
            </b-carousel>
            <b-carousel
              v-else
              v-model="slide"
              :interval="4000"
              background="transparent"
              @sliding-start="onSlideStart"
              @sliding-end="onSlideEnd"
            >
              <b-carousel-slide v-for="image in ad.image" :key="image" :img-src="image"></b-carousel-slide>
            </b-carousel>
            <p class="my-4" v-html="ad.description"></p>
          </div>
        </b-card>
        <!-- <b-card title="Images of item" class="w-75 mx-auto my-4">
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
        </b-card>-->
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
            Location: {{ad.user.location || 'undefined'}}
            <br />
            Member since: {{ad.user.created_at || formatDate}}
          </b-card-text>

          <b-button @click="redirect('user', ad.user.id)" variant="info" disabled>View profile</b-button>
        </b-card>
        <!-- <b-col cols="4"> -->

        <b-card class="w-50 mt-5 my-4" v-if="isAuthenticated && isAdVisitor">
          <b-form @submit.prevent="addBid">
            <b-form-group id="input-group-1" label="Bid on item" label-for="input-1">
              <b-form-input
                id="input-2"
                v-if="disableBid"
                disabled
                type="text"
                required
                placeholder="Enter amount"
              ></b-form-input>
              <b-form-input
                id="input-2"
                v-else
                @keypress="isNumber($event)"
                v-model="newBid"
                type="text"
                required
                placeholder="Enter amount"
              ></b-form-input>
            </b-form-group>
            <b-button type="submit" variant="primary">Bid</b-button>
          </b-form>
        </b-card>
        <b-card class="w-50 mt-5 my-4" title="Bids on item" v-if="bids.length > 0">
          <b-table striped hover :items="bids" @row-clicked="messageUser"></b-table>
        </b-card>
        <b-card class="w-50 mt-5 my-4" title="Bids on item" v-else>
          <span>There are no bids on this item yet</span>
        </b-card>
        <div v-if="isAdOwner">
          <b-button
            v-if="isAdOwner"
            @click="path(`/ad/${ad.id}/boost`)"
            variant="info"
            class="mb-2 ml-3"
          >Boost back up for 10$</b-button>
          <br />
          <b-button v-if="isAdOwner" @click="editAd" variant="info" class="ml-3">Edit</b-button>
          <b-button v-if="isAdOwner" v-b-modal.deleteModal variant="danger" class="ml-2">Delete</b-button>
        </div>
      </b-col>
    </b-row>
    <b-row class="mt-5 mx-5" v-else>
      <b-col offset="2" cols="8">
        <b-card
          title="404 Not found"
          class="w-75 mx-auto my-4"
          img-src="https://source.unsplash.com/1600x900/?cat,dog"
          img-alt="Image of ad"
          img-top
        >
          <b-card-text>The ad you're looking for doesn't exist or has been moved ;(</b-card-text>
        </b-card>
      </b-col>
    </b-row>
    <!-- <b-row class="mt-5 mx-5" v-if="ad">
      <b-col cols="8">
        <b-card title="Images of item" class="w-75 mx-auto my-4">
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
    </b-col>-->
    <!-- <b-col cols="4">
        <b-card class="w-50 my-4" title="Bids on item">
          <b-table striped hover :items="items"></b-table>
        </b-card>
    </b-col>-->
    <!-- </b-row> -->

    <b-modal id="deleteModal" title="Delete this ad" v-if="ad && isAdOwner" hide-footer>
      <span>
        <p>
          Are you absolutely sure you want to delete
          <b>{{ad.title}}</b>?
          <br />
          <small class="text-danger">There's no going back afterwards..</small>
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
      editActive: true,
      slide: 0,
      sliding: null,
      bids: [],
      disableBid: false,
      fullBids: [],
      newBid: ""
    };
  },
  computed: {
    isAuthenticated: {
      get() {
        return this.$store.getters.isAuthenticated;
      }
    },
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
  mounted() {
    this.getBids();
  },
  methods: {
    editAd() {
      this.$router.push(`/ad/${this.ad.id}/edit`);
    },
    isNumber: function(evt) {
      evt = evt ? evt : window.event;
      let charCode = evt.which ? evt.which : evt.keyCode;
      if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        charCode !== 46
      ) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    messageUser(record, index) {
      console.log(record, index);
    },
    getBids() {
      this.$http(`/api/bid/${this.$route.params.id}`)
        .then(res => {
          this.fullBids = res.data.data;

          this.fullBids.forEach(bid => {
            this.bids.push({
              Amount: parseInt(bid.amount),
              Who: bid.user.name
            });
          });
        })
        .catch(() => {
          this.$bvToast.toast(`An error occured`, {
            title: `Error`,
            variant: "Danger",
            solid: true,
            autoHideDelay: 5000
          });
        });
    },
    addBid() {
      const data = {
        ad_id: this.ad.id,
        amount: this.newBid
      };

      this.$http({
        url: "/api/bid/",
        method: "post",
        data
      })
        .then(res => {
          this.newBid = "";
          this.disableBid = true;
          this.bids = [];

          this.fullBids.push(res.data.data);
          this.fullBids.forEach(bid => {
            this.bids.unshift({
              Amount: parseInt(bid.amount),
              Who: bid.user.name,
              WhoId: bid.user.id
            });
          });

          setInterval(() => {
            this.disableBid = false;
          }, 60000);
        })
        .catch(err => console.log(err));
    },
    onSlideStart() {
      this.sliding = true;
    },
    onSlideEnd() {
      this.sliding = false;
    },
    path(path) {
      this.$router.push(path);
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

