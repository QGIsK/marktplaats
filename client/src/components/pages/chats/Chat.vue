<template>
    <div v-if="chat">
        <b-row>
            <b-col offset="3" cols="6">
                <b-card class="mt-5">
                    <b-card-title>
                        {{
                            chat.member1 === userId
                                ? chat.member2
                                : chat.member1
                        }}
                    </b-card-title>
                    <span v-for="msg in messages" :key="msg.id">
                        <b-row v-if="msg.self">
                            <b-col cols="3" class="ml-auto">
                                <b-button variant="primary" class="m-1">
                                    {{ msg.message }}
                                </b-button>
                            </b-col>
                        </b-row>
                        <b-row v-else>
                            <b-col cols="3">
                                <b-button variant="secondary" class="m-1">
                                    {{ msg.message }}
                                </b-button>
                            </b-col>
                        </b-row>
                    </span>
                    <span>
                        <b-form-textarea
                            class="mt-5"
                            v-model="text"
                            placeholder="Enter something..."
                            rows="3"
                            max-rows="6"
                        ></b-form-textarea>
                        <b-button
                            @click="sendMessage()"
                            class="mt-2"
                            variant="info"
                            >Send</b-button
                        >
                    </span>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
    name: "Chat",
    data: () => ({
        chat: {},
        messages: [],
        text: ""
    }),
    computed: {
        userId: {
            get() {
                return this.$store.getters.userId;
            }
        }
    },
    methods: {
        sendMessage() {
            this.$http({
                url: `/api/chats/message/${this.$route.params.id}`,
                method: "POST",
                data: {
                    message: this.text
                }
            })
                .then(res => {
                    console.log(res);
                    this.text = "";
                })
                .catch(e => {
                    console.log(e);
                });
        },
        getChat() {
            this.$http({
                url: `/api/chats/${this.$route.params.id}`,
                method: "get"
            })
                .then(res => {
                    this.chat = res.data.chat;
                    res.data.messages.forEach(message => {
                        message.user === this.$store.getters.userId
                            ? this.messages.unshift({
                                  id: message.id,
                                  chat_room: message.chat_room,
                                  self: true,
                                  message: message.message
                              })
                            : this.messages.unshift({
                                  id: message.id,
                                  chat_room: message.chat_room,
                                  self: false,
                                  message: message.message
                              });
                    });
                })
                .catch(e => console.log(e));
        }
    },
    mounted() {
        this.getChat();
    }
};
</script>
