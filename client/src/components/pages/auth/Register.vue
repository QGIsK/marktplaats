<template>
  <div>
    <b-card header="Register" class="w-50 mt-5 mx-auto">
      <b-form v-if="show" @submit="onSubmit" @reset="onReset">
        <b-form-group
          id="input-group-1"
          label="Email address:"
          label-for="input-1"
          description="We'll never share your email with anyone else."
        >
          <b-form-input
            id="input-1"
            v-model="form.email"
            type="email"
            required
            placeholder="Enter your name"
          ></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-2" label="Name:" label-for="input-2">
          <b-form-input
            id="input-2"
            v-model="form.name"
            type="text"
            required
            placeholder="Enter your name"
          ></b-form-input>
        </b-form-group>

        <b-form-group id="input-group-3" label="Your password:" label-for="input-3">
          <b-form-input
            id="input-3"
            v-model="form.password"
            type="password"
            required
            placeholder="Enter your password"
          ></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-4" label="Confirm your password:" label-for="input-4">
          <b-form-input
            id="input-4"
            v-model="form.password_confirmation"
            type="password"
            required
            placeholder="Confirm your password"
          ></b-form-input>
        </b-form-group>

        <b-button type="submit" variant="primary">Register</b-button>
        <b-button type="reset" variant="danger" class="ml-2">Clear</b-button>
      </b-form>
    </b-card>
  </div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      form: {
        email: "",
        name: "",
        password: "",
        password_confirmation: ""
      },
      show: true
    };
  },
  mounted() {},
  methods: {
    onSubmit(evt) {
      evt.preventDefault();

      let email = this.form.email;
      let name = this.form.name;
      let password = this.form.password;
      let password_confirmation = this.form.password_confirmation;

      this.$store
        .dispatch("register", {
          email,
          name,
          password,
          password_confirmation
        })
        .then(() => {
          this.form.email = "";
          this.form.name = "";
          this.form.password = "";
          this.form.password_confirmation = "";

          this.show = false;
          this.$nextTick(() => {
            this.show = true;
          });

          this.$bvToast.toast(`Registered`, {
            title: `Success`,
            variant: "success",
            solid: true,
            autoHideDelay: 5000
          });

          this.$router.push("/");
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

      this.form.email = "";
      this.form.name = "";
      this.form.password = "";
      this.form.password_confirmation = "";

      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  }
};
</script>