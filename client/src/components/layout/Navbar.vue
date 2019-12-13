<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="dark">
      <b-container>
        <b-navbar-brand to="/">Marktplaats</b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav v-if="isAuthenticated" class="ml-auto">
            <b-nav-item-dropdown text="Ads" right>
              <b-dropdown-item to="/ad/new">New Ad</b-dropdown-item>
              <b-dropdown-item to="/ad/my">My Ads</b-dropdown-item>
            </b-nav-item-dropdown>
            <b-nav-item-dropdown :text="user.name" right>
              <b-dropdown-item>Profile</b-dropdown-item>
              <b-dropdown-item href="#">Messages</b-dropdown-item>
              <b-dropdown-item href="#">Settings</b-dropdown-item>
              <b-dropdown-item @click="logout">Logout</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav>
          <b-navbar-nav v-else class="ml-auto">
            <b-nav-item to="/auth/login">Login</b-nav-item>
            <b-nav-item to="/auth/register">Register</b-nav-item>
          </b-navbar-nav>
        </b-collapse>
      </b-container>
    </b-navbar>
  </div>
</template>

<script>
export default {
  name: "Navbar",

  computed: {
    isAuthenticated: {
      get() {
        return this.$store.getters.isAuthenticated;
      }
    },
    user: {
      get() {
        return this.$store.getters.user;
      }
    }
  },
  methods: {
    logout() {
      this.$store.dispatch("logout").then(() => {
        this.$router.push("/");
        this.$bvToast.toast(`Logged out`, {
          title: `Success`,
          variant: "success",
          solid: true,
          autoHideDelay: 5000
        });
      });
    }
  }
};
</script>

