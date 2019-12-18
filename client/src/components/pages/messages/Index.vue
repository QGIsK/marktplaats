<template>
  <div>
    <b-row class="mt-5">
      <b-col cols="4" class="mt-5">
        <b-card
          class="w-75 mx-auto my-4"
          header="All chats"
          header-bg-variant="info"
          align="center"
          header-text-variant="white"
        >
          <b-card-text>
            <b-list-group flush>
              <b-list-group-item style="cursor:pointer" @click="changeChat(1)">
                Cras justo odio
                <br />
                <small class="pt-1 text-muted">Hey, how big is that moon you're selling?</small>
              </b-list-group-item>
              <b-list-group-item style="cursor:pointer" @click="changeChat(2)">
                Cras justo odio
                <br />
                <small class="pt-1 text-muted">Hey, how big is that moon you're selling?</small>
              </b-list-group-item>
              <b-list-group-item style="cursor:pointer" @click="changeChat(3)">
                Cras justo odio
                <br />
                <small class="pt-1 text-muted">Hey, how big is that moon you're selling?</small>
              </b-list-group-item>
            </b-list-group>
          </b-card-text>
        </b-card>
      </b-col>
      <b-col cols="8" class="mt-5">
        <b-card
          header="Current chat"
          header-bg-variant="info"
          align="center"
          header-text-variant="white"
          class="w-75 mx-auto my-4"
          title="Chats"
        ></b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  Name: "MessagesIndex",
  data: () => ({}),

  methods: {
    changeChat(id) {
      this.$router.push("/messages/" + id);
    }
  }
};
</script>

<style scoped>
</style>