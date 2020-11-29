<template>
<div>
    <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="profile_cover">
                        <img class="card-img-top" :src="user.cover" alt="Card image cap">
                    </div>

                    <div class="profile_avatar">
                        <img @click="checkUser" :src="user.avatar" alt="">
                        
                    </div>                
                </div>
                
            </div>
            
            <h1 class="margin-top">{{ user.name }}</h1>
            
    </div>
    <div class="offset-md-8" v-if="currentUser.id != user.id">
        <a href="#" @click.prevent="sendRequest">Thêm bạn bè</a>
    </div>
    <div class="modal-wrapper" v-if="visible_modal">
        <span @click="visible_modal = !visible_modal">&#10008;</span>
        <input class="modal-input" @change="upAvatar" type="file" :accept="acceptImage" ref="fileInput" />
        <button type="button" class="btn btn-outline-primary modal-button">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-image" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9c0 .013 0 .027.002.04V12l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094L15 9.499V3.5a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm4.502 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
            </svg>
            Cập nhật ảnh đại diện
        </button>
        <div class="image-avatar">
            <img v-if="image" :src="image" class="img-responsive">
        </div>
        <button type="button" class="btn btn-outline-primary btn_ok" :disabled="!isValidated" @click="uploadAvatar">OK</button>
    </div>
    <div class="row justify-content-center" style="margin-top: 100px">
        <div class="card col-md-8" v-for="(post, index) in posts" :key="index" style="margin-bottom: 20px;">
                
                <div class="card-header alert alert-primary row header-post">
                    <div class="user_avatar col-md-1">
                        <img class="avatar" :src="user.avatar" alt=""/>
                    </div>
                    <div class="col-md-10">
                        <a :href="'/profile/' + post.user_id">{{user.name}}</a>
                    </div>
                    <div class="col-md-1 action"  @click="post.action = !post.action" v-if="post.user_id == currentUser.id">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                        </svg>
                        
                        <div class="action-post" v-if="post.action">
                            <p @click="post.showedit = !post.showedit">Sửa</p>
                            <p @click="deletePost(post, index)">Xóa</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p v-if="!post.showedit" class="card-text">{{post.status}}</p>
                    <div class="card-body" v-if="post.showedit">
                        <textarea v-if="post.showedit" cols="80" v-model="post.status"></textarea>
                        <button v-if="post.showedit" class="btn btn-primary" @click="editPost(post)">Sửa</button>
                        <button v-if="post.showedit" @click="post.showedit = !post.showedit" class="btn btn-primary">Hủy</button>
                    </div>
                    <div class="body-image"  v-if="post.img">
                        <img class="card-img-bottom" :src="post.img" />
                    </div>
                </div>
                
                <hr class="my-4" />

                <div class="comment-block">
                    <img class="avatar-comment" :src="currentUser.avatar" alt=""/>
                    <input @keyup.enter="storeComment(post.id, currentUser.id)" v-model="comment" class="input-comment" placeholder="Viết bình luận..."/>
                    <button @click="storeComment(post.id, currentUser.id)" type="button" class="btn btn-outline-primary">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-square-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 8.5a.5.5 0 0 1 0-1h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5z"></path>
                        </svg>
                    </button>
                    <div v-for="(comment, index) in comments" :key="index">
                        <div @mouseover="comment.hover = 1" @mouseleave="comment.hover = 0" class="comment-contain" v-if="comment.post_id == post.id">
                            <img class="avatar-comment" :src="comment.user.avatar" alt=""/>
                            <a :href="'/profile/'+ comment.user.id">{{comment.user.name}}</a>
                            <svg v-if="(comment.hover && currentUser.id == post.user_id) || (comment.hover && currentUser.id == comment.user.id)"
                                width="1em" height="1em"
                                viewBox="0 0 16 16" class="bi bi-three-dots" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg" @click="comment.active = !comment.active">
                                <path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                            </svg>
                            <span class="edit" v-if="comment.isEdit">Đã chỉnh sửa</span>
                            <p v-if="!comment.action">{{ comment.content }}</p>
                            <div v-if="comment.active" class="action-comment">
                                <p v-if="comment.user.id === currentUser.id" @click="(comment.action = !comment.action) && (comment.active = !comment.active)">Sửa</p>
                                <p @click="deleteComment(comment, index)">Xóa</p>
                            </div>
                        </div>
                        
                        <div class="card-body" v-if="comment.action">
                            <textarea cols="80" v-model="comment.content"></textarea>
                            <button class="btn btn-primary" @click="editComment(comment)">Sửa</button>
                            <button class="btn btn-primary" @click="comment.action = !comment.action">Hủy</button>
                        </div>
                    
                    </div>
                </div>
            </div>
    </div>
</div>
</template>
<script>
    export default {
        data(){
            return {
                id: '',
                user: [],
                currentUser: [],
                posts: [],
                comments: [],
                comment: '',
                image: '',
                isValidated: false,
                acceptImage: ['.jpg', '.png'],
                visible_modal: false,
                isValidated: false,
            }
        },
        mounted() {
            this.id = window.location.href.split('/')[window.location.href.split('/').length - 1];
            this.getUser();
            this.getCurrentUser();
            this.getPost();
            this.getComments();
        },
        methods: {
            getUser: function() {
                axios.get('/api/profile/' + this.id)
                    .then( (response) => {
                        this.user = response.data;
                    });
                   
            },
            getCurrentUser: function() {
                axios.get('/api/profile')
                .then((response) => {
                    this.currentUser = response.data;
                    console.log(this.currentUser);
                });
            },
            getPost: function() {
                axios.get('/api/postByUser/' + this.id)
                .then((response) => {
                    this.posts = response.data;
                });
            },
            getComments: function() {
                axios.get('/api/comments')
                .then((response) => {
                    this.comments = response.data;
                })
            },
            storeComment: function(postId, userId) {
                if(this.comment.length > 0) {
                    axios.post('/store', {post_id: postId, user_id: userId, content: this.comment})
                    .then((response) => {
                        this.comment = '';
                        this.getComments();
                    });
                }
                
            },
            //upload image
            upAvatar: function(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                {
                    return;
                }  
                this.createImage(files[0]);
                this.isValidated = true;
                
            },
            createImage: function(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            checkUser: function() {
                if(this.currentUser.id === this.user.id) {
                    this.visible_modal = !this.visible_modal;
                }
            },

            uploadAvatar: function() {
                axios.post('/uploadAvatar', {
                    id: this.currentUser.id,
                    image: this.image
                })
                .then((response) => {
                    this.getUser();
                    this.visible_modal = !this.visible_modal;
                    this.image = '';
                    this.$refs.fileInput.value = '';
                    this.isValidated = false;
                });
            },

            sendRequest: function() {
                axios.post('/friendRequest', {
                    user_send: this.currentUser.id,
                    to_user: this.user.id
                })
                .then((response) => {
                    
                });
            }
        },


    }
</script>
<style lang="scss" scoped>
.profile_avatar {
    cursor: pointer;
}
.header-post {
    position: relative;
    .action-post {
        display: inline-block;
        border: 1px solid black;
        background: whitesmoke;
        border-radius: 7px;
        height: auto;
        width: 150px;
        position: absolute;
        right: 30px;
        top: 20px;
        text-align: center;
        p {
            padding: 20px;
            margin-bottom: 0;
            color: black;
        }
        p:hover {
            background: #9d9d9d;
            border-radius: 7px;
        }
    }
}
.modal-wrapper {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 400px;
  height: 400px;
  z-index: 1000;
  border-radius: 2px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
  background: white;
  border-radius: 10px;
  span {
    background: #ebebeb;
    position: absolute;
    right: 20px;
    top: 20px;
    height: 30px;
    width: 30px;
    text-align: center;
    line-height: 30px;
    border-radius: 50%;
    cursor: pointer;
  }
  .modal-button {
    position: absolute;
    top: 80px;
    left: 100px;
  }
  .modal-input {
    position: absolute;
    top: 80px;
    left: 2px;
    z-index: 2000;
    opacity: 0;
    cursor: pointer;
  }
  .image-avatar {
      position: absolute;
      height: 200px;
      width: 200px;
      left: 50%;
      margin-left: -100px;
      top: 135px;
      border-radius: 50%;
      img {
          border-radius: 50%;
      }
  }
  .btn_ok {
      position: absolute;
      bottom: 20px;
      right: 20px;
  }
  
}
.action {
    cursor: pointer;
    z-index: 1000;
}
.body-image {
    width: 690px;
    height: 500px;
    img {
        width: 100%;
    }
}

.comment-block {
    margin-left: 10px;
    .avatar-comment {
        width: 30px;
        height: 30px;
        margin: 10px;
        border-radius: 50%;
    }
    .input-comment {
        border-radius: 20px;
        outline: none;
        width: 600px;
        border: 1px solid #9d9d9d;
    }
    .bi.bi-three-dots {
        margin-left: 540px;
        cursor: pointer;
    }
    p {
        margin-left: 50px;
        margin-right: 30px;
    }
    .comment-contain {
        background: #ebebeb;
        border: 1px solid #ebebeb;
        margin-right: 7px;
        border-radius: 20px;
        position: relative;
        .action-comment {
            display: block;
            border: 1px solid black;
            background: whitesmoke;
            border-radius: 7px;
            height: auto;
            width: 150px;
            position: absolute;
            right: 40px;
            top: 40px;
            text-align: center;
            z-index: 1000;
            p {
                padding: 20px;
                margin-bottom: 0;
                margin-left: 0;
                margin-right: 0;
            }
            p:hover {
                background: #9d9d9d;
                border-radius: 7px;
            }
        }
        .edit {
            margin-left: 50px;
            display: block;
            color: white;
            background: #9d9d9d;
            width: 100px;
            text-align: center;
            border-radius: 10px;
        }
        
    }

    
}
</style>