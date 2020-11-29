<template>
<div id="app">
    <h2>Bạn bè</h2>
    <div class="friend" v-if="friends.length == 0">
        <span>Bạn chưa có bạn bè nào</span>
    </div>
    <div class="friend" v-if="friends.length > 0">
        <div v-for="(friend, index) in friends" :key="index">
            <div class="friend_item" v-if="friend.user1.id != user.id">
                <img :src="friend.user1.avatar" alt="" />
                <a href="#" @click.prevent="friend.show_chat = !friend.show_chat">{{friend.user1.name}}</a>
            </div>
            <div class="friend_item" v-if="friend.user2.id != user.id">
                <img :src="friend.user2.avatar" alt="">
                <a href="#" @click.prevent="friend.show_chat = !friend.show_chat">{{friend.user2.name}}</a>
            </div>
            <Chat class="chat" v-if="friend.show_chat" :friend="friend" :user="user"></Chat>

        </div>
    </div>

</div>
</template>
<script>
import Chat from './Chat.vue';
export default {
    data() {
        return {
            friends: [],
            user: {}
        }
    },
    mounted() {
        this.getFriends();
        this.getUser();
    },
    methods: {
        getFriends: function() {
            axios.get('/api/friend')
            .then((response) => {
                this.friends = response.data;
            });
        },
        getUser: function() {
            axios.get('/api/profile')
            .then((response) => {
                this.user = response.data;
            });
        }
    },
    components: {
        Chat
    }
}
</script>
<style lang="scss" scoped>
h2 {
    position: fixed;
    top: 65px;
}
.friend {
    background: #ebebeb;
    position: fixed;
    top:100px;
    width: 315px;
    height: 500px;
    .friend_item {
        padding: 20px;
        img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        }
        a {
            margin-left: 10px;
        }
    }
    
}
.chat {
    position: fixed;
    bottom: 0px;
    right: 200px;
    width: 300px;
    height: 340px;
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
    border: 1px solid #95a5a6;
}
</style>