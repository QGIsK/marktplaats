<template>
  <div>
    <b-card header="Login" class="w-50 mt-5 mx-auto">
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
            placeholder="Enter email"
          ></b-form-input>
        </b-form-group>

        <b-form-group id="input-group-2" label="Your Name:" label-for="input-2">
          <b-form-input
            id="input-2"
            v-model="form.password"
            type="password"
            required
            placeholder="Enter your password"
          ></b-form-input>
        </b-form-group>

        <b-button type="submit" variant="primary">Submit</b-button>
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
        name: ""
      },
      show: true
    };
  },
  methods: {
    onSubmit(evt) {
      evt.preventDefault();

      let email = this.form.email;
      let password = this.form.password;

      this.$store
        .dispatch("login", {
          email,
          password
        })
        .then(() => {
          console.log("successfull");

          this.form.email = "";
          this.form.password = "";

          this.show = false;
          this.$nextTick(() => {
            this.show = true;
          });

          this.$router.push("/");
        })
        .catch(e => {
          console.log(e);
          console.log("smth went wrong");
        });
    },
    onReset(evt) {
      evt.preventDefault();

      this.form.email = "";
      this.form.password = "";

      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  }
};
</script>