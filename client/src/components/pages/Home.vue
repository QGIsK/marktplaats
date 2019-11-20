<template>
  <div>
    <b-jumbotron
      bg-variant="dark"
      style="border-radius: 0"
      text-variant="white"
      border-variant="dark"
    >
      <b-container>
        <template v-slot:header>Search</template>

        <b-form class="mx-auto">
          <b-form-input class="w-75 mx-auto" size="lg" placeholder="Search"></b-form-input>
        </b-form>
      </b-container>
    </b-jumbotron>

    <b-container>
      <b-row v-if="ads">
        <b-col col="4"  v-for="card in [1, 2, 3]" :key="card">
          <b-card
            title="Ad"
            img-src="https://picsum.photos/600/300/?image=25"
            img-alt="Image"
            img-top
            tag="ad"
            style="min-width: 20rem; max-width: 20rem;"
            class="mx-auto my-4"
          >
            <b-card-text>Some quick example text to build on the card title and make up the bulk of the card's content.</b-card-text>

            <b-button href="#" variant="primary">Go somewhere</b-button>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  name: "Home",
  data () {
    return {
      ads: true
    }
  }

};
</script>

