<template>
  <div v-if="isAdOwner">
    <b-card header="New Ad" class="w-75 mb-5 mt-5 mx-auto">
      <b-form v-if="show" @submit="onSubmit" @reset="onReset">
        <b-form-group
          id="input-group-1"
          label="Picture of your item"
          label-for="input-1"
          description="A picture of the item you're trying to sell."
        >
          <vue-dropzone
            ref="ImagesDropzone"
            id="dropzone"
            :options="dropzoneOptions"
            @vdropzone-success="fileSuccess"
            @vdropzone-error="fileError"
          ></vue-dropzone>
        </b-form-group>

        <b-form-group
          id="input-group-2"
          label="Ad Title"
          label-for="input-2"
          description="What are you trying to sell?"
        >
          <b-form-input
            id="input-2"
            v-model="title"
            type="text"
            required
            placeholder="Enter title  "
          ></b-form-input>
        </b-form-group>

        <b-form-group
          id="input-group-3"
          label="Description"
          label-for="input-3"
          description="Describe the item you're trying to sell, Please do include things such as the items Condition And its Age."
        >
          <wysiwyg id="body" v-model="description" />
        </b-form-group>
        <b-form-group label="Categories:">
          <b-form-checkbox-group v-model="categories">
            <span v-for="cat in allCategories" :key="cat.id">
              <b-form-checkbox :value="cat.id">{{cat.tag}}</b-form-checkbox>
            </span>
          </b-form-checkbox-group>
        </b-form-group>

        <b-form-group label="Postal code">
          <b-form-input v-modal="postalCode" class="w-25" type="text" required placeholder="1234hz"></b-form-input>
        </b-form-group>

        <b-button type="submit" variant="primary">Edit</b-button>
        <b-button type="reset" variant="danger" class="ml-2">Clear</b-button>
      </b-form>
    </b-card>
  </div>
</template>
<script>
import vue2Dropzone from "vue2-dropzone";
import VueCookies from "vue-cookies";

import "vue2-dropzone/dist/vue2Dropzone.min.css";
import "vue-wysiwyg/dist/vueWysiwyg.css";

export default {
  name: "EditAd",
  data() {
    return {
      show: true,

      title: "",
      image: "",
      postalCode: "",
      description: "",
      categories: [],

      dropzoneOptions: {
        url: "http://localhost:8000/api/file/",
        thumbnailWidth: 150,
        maxFilesize: 10,
        maxFiles: 5,
        headers: { Authorization: `Bearer ${VueCookies.get("token")}` }
      }
    };
  },
  components: {
    vueDropzone: vue2Dropzone
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
    allCategories: {
      get() {
        return this.$store.getters.categories;
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
  mounted() {
    this.setData(this.ad);
  },
  watch: {
    ad(ad) {
      this.setData(ad);
    }
  },
  methods: {
    setData(ad) {
      if (!ad) return;
      this.categories = [];
      this.title = ad.title;
      this.postalCode = ad.postalCode;
      this.image = ad.image.join(",");
      this.description = ad.description;
      ad.categories.forEach(cat => {
        this.categories.push(cat.id);
      });
      this.dropzoneOptions.maxFiles =
        this.ad.image.length <= 0 ? 5 : Math.abs(this.ad.image.length - 5);

      if (this.dropzoneOptions.maxFiles === 0) {
        this.$refs.ImagesDropzone.disable();
      }
    },
    fileSuccess(file, res) {
      this.image.length > 0
        ? (this.image += `,${res.url}`)
        : (this.image = res.url);
    },
    fileError() {
      this.dropzoneOptions.maxFiles++;
      this.$bvToast.toast(
        `An error occured uploading your image, Please try again.`,
        {
          title: `Error`,
          variant: "danger",
          solid: true,
          autoHideDelay: 5000
        }
      );
    },

    onSubmit(evt) {
      evt.preventDefault();

      const data = {
        id: this.ad.id,
        title: this.title,
        image: this.image,
        postalCode: this.postalCode,
        description: this.description,
        categories: this.categories
      };

      this.$store
        .dispatch("editAd", { ad: data })
        .then(res => {
          this.$bvToast.toast(`Ad Edited`, {
            title: `Success`,
            variant: "success",
            solid: true,
            autoHideDelay: 5000
          });

          let ad = res.data.data;
          let img = this.image;
          img = img.split(",");
          ad.image = img;

          let adIndex = this.ads.findIndex(x => x.id === ad.id);

          this.ads[adIndex] = ad;

          this.$router.push(`/ad/${ad.id}`);
        })
        .catch(e => {
          this.$bvToast.toast(`An error occured, Please try again.`, {
            title: `Error`,
            variant: "danger",
            solid: true,
            autoHideDelay: 5000
          });
          console.log(e);
        });
    },
    onReset(evt) {
      evt.preventDefault();

      this.title = "";
      this.description = "";
      this.categories = [];
      this.postalCode = "";

      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  }
};
</script>
