<template>
    <div>
        <b-row class="mt-5">
            <b-col cols="6" offset="3" class="mt-5">
                <b-card
                    class="w-75 mx-auto my-4"
                    header="All chats"
                    header-bg-variant="primary"
                    align="center"
                    header-text-variant="white"
                >
                    <b-card-text>
                        <b-list-group flush>
                            <b-list-group-item
                                v-for="chat in chats"
                                :key="chat.id"
                                style="cursor:pointer"
                            >
                                <span @click="redirect(chat.id)">
                                    {{ chat.name }}
                                </span>
                                <!-- <hr style="margin-bottom:-1vh" /> -->
                                <!-- <br /> -->
                                <!-- <small class="pt-1 text-muted"
                                    >Hey, how big is that moon you're
                                    selling?</small
                                > -->
                            </b-list-group-item>
                        </b-list-group>
                    </b-card-text>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
    Name: "chatIndex",
    data: () => ({
        chats: []
    }),

    methods: {
        redirect(id) {
            this.$router.push("/chats/" + id);
        },
        getChats() {
            this.$http({ url: "/api/chats", method: "get" })
                .then(res => {
                    res.data.data.forEach(chat => {
                        chat.member1 === this.$store.getters.UserId
                            ? this.chats.push({
                                  name: chat.member2,
                                  id: chat.id
                              })
                            : this.chats.push({
                                  name: chat.member1,
                                  id: chat.id
                              });
                    });

                    console.log(this.chats);
                })
                .catch(e => console.log(e));
        }
    },
    mounted() {
        this.getChats();
    }
};
</script>

<style scoped></style>
