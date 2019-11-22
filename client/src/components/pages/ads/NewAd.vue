<template>
  <div>
    <b-card header="New Ad" class="w-75 mt-5 mx-auto">
      <b-form v-if="show" @submit="onSubmit" @reset="onReset">
        <b-form-group
          id="input-group-1"
          label="Picture of your item"
          label-for="input-1"
          description="A picture of the item you're trying to sell."
        >
          <vue-dropzone
            ref="myVueDropzone"
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
          <wysiwyg id="body" v-model="description"/>
        </b-form-group>

        <b-button type="submit" variant="primary">Create</b-button>
        <b-button type="reset" variant="danger" class="ml-2">Clear</b-button>
      </b-form>
    </b-card>
  </div>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";

import "vue2-dropzone/dist/vue2Dropzone.min.css";
import "vue-wysiwyg/dist/vueWysiwyg.css";

// import axios from "axios";

export default {
  name: "NewAd",
  components: {
    vueDropzone: vue2Dropzone
  },
  data() {
    return {
      title: "",
      description: "",
      image: "",

      show: true,
      dropzoneOptions: {
        url: "http://localhost:8000/api/file/upload",
        thumbnailWidth: 150,
        maxFilesize: 10,
        maxFiles: 1,
        headers: { Authorization: localStorage.getItem("token") }
      }
    };
  },
  computed: {
    ads: {
      get() {
        return this.$store.getters.ads;
      }
    }
  },
  methods: {
    fileSuccess(response) {
      this.image = response.path;
    },
    fileError() {
      this.$bvToast.toast(
        `An error occured uploading your image, Please try again.`,
        {
          title: `Error`,
          variant: "error",
          solid: true,
          autoHideDelay: 5000
        }
      );
    },

    onSubmit(evt) {
      evt.preventDefault();

      const data = {
        title: this.title,
        description: this.description,
        image: this.image
      };

      this.$store
        .dispatch("createAd", { post: data })
        .then(res => {
          console.log(res);
          this.$bvToast.toast(`Post created`, {
            title: `Success`,
            variant: "success",
            solid: true,
            autoHideDelay: 5000
          });

          this.ads.unshift(res.data.data);

          this.$router.push(`/ad/${res.data.data.id}`);
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
      // this.$http
      // axios({
      //   url: `/api/ads/`,
      //   data,
      //   crossdomain: true,
      //   method: "POST",
      //   headers: {
      //     "Access-Control-Allow-Origin": "*"
      //   }
      // })
      //   .then(res => {
      //     console.log(res.data);
      //     // this.$router.push("/");
      //   })
      //   .catch(e => {
      //     console.log(e);

      //     this.$bvToast.toast(`An error occured, Please try again.`, {
      //       title: `Error`,
      //       variant: "danger",
      //       solid: true,
      //       autoHideDelay: 5000
      //     });
      //   });
    },
    onReset(evt) {
      evt.preventDefault();

      this.title = "";
      this.description = "";

      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  }
};
</script>