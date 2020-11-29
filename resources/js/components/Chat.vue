<template>
    <div>
        <div class="chat_header">
            <div v-if="user.id != friend.user1.id">
                <img :src="friend.user1.avatar" alt="">
                <span>{{friend.user1.name}}</span>
            </div>
            <div v-if="user.id != friend.user2.id">
                <img :src="friend.user2.avatar" alt="">
                <span>{{friend.user2.name}}</span>
            </div>
            <div class="cancel" @click="friend.show_chat = !friend.show_chat">
                <svg height="26px" width="26px" viewBox="-4 -4 24 24">
                    <line stroke="#bec2c9" stroke-linecap="round" stroke-width="2" x1="2" x2="14" y1="2" y2="14"></line><line stroke="#bec2c9" stroke-linecap="round" stroke-width="2" x1="2" x2="14" y1="14" y2="2"></line>
                </svg>
            </div>
        </div>
        <div class="chat_content">
            <div v-for="(message, index) in messages" :key="index">
                <div class="message_other" v-if="message.user_send != user.id">
                    <p>{{message.message}}</p>               
                </div>
                <br />
                <div class="message_own" v-if="message.user_send == user.id">
                    <p>{{message.message}}</p>
                </div>
                <br />
            </div>
        </div>
        <div class="input">
            <input type="text" class="text_input" placeholder="Nhập tin nhắn..." v-model="message"/>
            <button class="btn btn-primary" @click="saveMessage">Gửi</button>
        </div>
    </div>
    
</template>

<script>
export default {
    data() {
        return {
            message: '',
            messages: []
        }
    },
    props: {
        friend: Object,
        user: Object
    },
    mounted() {
        this.getMessage();
        Echo.channel('laravel_database_private-chatroom')
        .listen('MessagePosted', (data) => {
            console.log(data.message);
        });
    },
    methods: {
        getMessage() {
            if(this.friend.user1.id !== this.user.id) {
                axios.post('/api/getMessages', {id: this.friend.user1.id})
                .then((response)=> {
                    this.messages = response.data;
                });
            }
            else {
                axios.post('/api/getMessages', {id: this.friend.user2.id})
                .then((response)=> {
                    this.messages = response.data;
                })
            }
        },
        saveMessage: function() {
            if(this.message.length > 0) {
                if(this.friend.user1.id != this.user.id) {
                    this.sendMessage(this.friend.user1.id, this.user.id, this.message);
                }
                else {
                    this.sendMessage(this.friend.user2.id, this.user.id, this.message);
                }
            }
            
        },
        sendMessage: function(friendUser, user, message) {
            axios.post('/sendMessage', {friendUser: friendUser, user: user, message: message})
            .then((response) => {
                this.message = '';
                this.getMessage();
            });
        }
    }
}
</script>

<style lang="scss" scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
.chat_header {
    background: #3498db;
    color: #1c1e21;
    padding: 10px;
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
    display: flex;
    justify-content: space-between;
    .cancel {
        background: #1c1e21;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        align-items: center;
        cursor: pointer;
    }
    img {
    width: 30px;
    height: 30px;  
    border-radius: 50%;
    }
}
.chat_content {
    max-width: 300px;
    height: 240px;
    background: #FFF;
    overflow-y: scroll;
    .message_other {
        background: #95a5a6;
        padding: 10px;
        border-radius: 10px;
        margin-left: 10px;
        margin-bottom: 5px;
        display: inline-block;
        p {
            width: 200px;
            word-break: break-all;
        }
    }
    .message_own {
        background: #2980b9;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 5px;
        color: #FFF;
        display: inline-block;
        margin-left: 50px;
        p {
            width: 200px;
            word-break: break-all;
        }
    }
}
.input {
    text-align: center;
    position: absolute;
    bottom: 5px;
    left: 10px;
    .text_input {
        width: 240px;
        border-radius: 10px;
        outline: 0;
    }
}
</style>