<template>
    <div>
        <span v-for="msg in chat" :key="msg.id">
            mine? {{ msg.self }}
            {{ msg.message }}
        </span>
    </div>
</template>

<script>
export default {
    name: "Chat",
    data: () => ({
        chat: []
    }),
    methods: {
        getChat() {
            this.$http({
                url: `/api/chats/${this.$route.params.id}`,
                method: "get"
            })
                .then(res => {
                    res.data.messages.forEach(message => {
                        message.user === this.$store.getters.userId
                            ? this.chat.push({
                                  id: message.id,
                                  chat_room: message.chat_room,
                                  self: true,
                                  message: message.message
                              })
                            : this.chat.push({
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
