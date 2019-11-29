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

          <b-button @click="redirect('user', ad.user.id)" variant="primary">Go to user</b-button>
        </b-card>
      </b-col>

      <b-modal id="deleteModal" title="Delete this ad" hide-footer>
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
    </b-row>
  </div>
</template>

<script>
// import axios from "axios";

export default {
  name: "ShowAd",

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
    }
  },
  methods: {
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