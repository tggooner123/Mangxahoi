<template>
    <div>
        <!-- form post  -->
        <div class="form border border-secondary">
            <textarea @change="status" name="status" id="status" cols="80" rows="5" v-model="status" placeholder="Bạn đang nghĩ gì"></textarea>
            <div class="row">
                <div class="col-md-1 offset-md-1">
                    <img v-if="image" :src="image" class="img-responsive">
                </div>
                <div class="col-md-7">
                    <input @change="validate" class="visi-hid" :accept="acceptImage" type="file" ref="fileInput" />
                    <button type="button" class="btn btn-outline-primary" id="fake-input">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-image" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                         <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9c0 .013 0 .027.002.04V12l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094L15 9.499V3.5a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm4.502 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                        </svg>
                        Chọn hình

                    </button>
                </div>
                <button type="button" @click="postStatus" class="btn btn-outline-primary col-md-2" :disabled="!isValidated">Đăng</button>
            </div>
        </div>
        <!-- end form post  -->
        <!-- list posts  -->
    
        <div class="card mt-3" v-for="(post, index) in posts" :key="index" style="margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 5px rgba(0, 0, 0, 0.24);">
            
            <div class="card-header alert alert-primary row header-post">

                <div class="user_avatar col-md-1">
                    <img class="avatar" :src="user.avatar" alt=""/>
                </div>
                <div class="col-md-10">
                    <a :href="'/profile/' + post.user.id">{{ post.user.name }}</a>
                </div> 
                <div class="col-md-1 action"  @click="post.action = !post.action" v-if="post.user_id == user.id">
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

            <!-- comment block -->
            <div class="comment-block">
                <img class="avatar-comment" :src="user.avatar" alt=""/>
                <input @keyup.enter="storeComment(post.id, user.id)" v-model="comment" class="input-comment" placeholder="Viết bình luận..."/>
                <button @click="storeComment(post.id, user.id)" type="button" class="btn btn-outline-primary">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-square-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 8.5a.5.5 0 0 1 0-1h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5z"></path>
                    </svg>
                </button>
                <div v-for="(comment, index) in comments" :key="index">
                    <div @mouseover="comment.hover = 1" @mouseleave="comment.hover = 0" class="comment-contain" v-if="comment.post_id == post.id">
                        <img class="avatar-comment" :src="comment.user.avatar" alt=""/>
                        <a :href="'/profile/'+ comment.user.id">{{comment.user.name}}</a>
                        <svg v-if="(comment.hover && user.id == post.user.id) || (comment.hover && user.id == comment.user.id)"
                            width="1em" height="1em"
                            viewBox="0 0 16 16" class="bi bi-three-dots" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg" @click="comment.active = !comment.active">
                            <path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                        </svg>
                        <span class="edit" v-if="comment.isEdit">Đã chỉnh sửa</span>
                        <p v-if="!comment.action">{{ comment.content }}</p>
                        <div v-if="comment.active" class="action-comment">
                            <p v-if="comment.user.id == user.id" @click="(comment.action = !comment.action) && (comment.active = !comment.active)">Sửa</p>
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
            <!-- end comment  -->
            
        </div>
        <!-- end list posts  -->
    </div>

</template>
<script>
    export default {
        data(){
            return {
                status: [],
                user: [],
                posts: [],
                comment: '',
                image: '',
                comments: [],
                isValidated: false,
                acceptImage: ['.jpg', '.png'],
            }
        },
        mounted() {
            this.getUser();
            this.getPosts();
            this.getComments();
        },
        watch: {
            status: function() {
                if(this.status.length > 0) 
                    this.isValidated = true;
                else 
                    this.isValidated = false;
            }
        },
        methods: {
            postStatus: function() {
                if(this.status.length > 0 && this.image.length === 0) {
                    axios.post('/postContent', {
                        status: this.status,
                        user_id: this.user.id
                    }).then(response => {
                        this.getPosts();
                        this.status = '';
                        this.image = '';
                        this.$refs.fileInput.value = '';
                        this.isValidated = false;
                    }).catch(error => {
                        console.log(error);
                    }); 
                }
                else if(this.status.length === 0 && this.image.length > 0) {
                    axios.post('/postImage', {
                        user_id : this.user.id,
                        image: this.image
                    })
                    .then(response => {
                        console.log(response.data);
                        this.getPosts();
                        this.status = '';
                        this.image = '';
                        this.$refs.fileInput.value = '';
                        this.isValidated = false;
                    })
                    .catch(error => {
                        console.log(error);
                    }); 
                }
                else if(this.status.length > 0 && this.image.length > 0){
                    axios.post('/post', {
                        status :this.status,
                        user_id : this.user.id,
                        image: this.image
                    })
                    .then(response => {
                        this.getPosts();
                        this.status = '';
                        this.image = '';
                        this.$refs.fileInput.value = '';
                        this.isValidated = false;
                    })
                    .catch(error => {
                        console.log(error);
                    }); 
                }
                
            },
            getUser: function() {
                axios.get('/api/profile')
                    .then( (response) => {
                        this.user = response.data;
                    });
                   
            },

            getPosts: function() {
                axios.get('/api/posts')
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
            //post image
            validate: function(e) {
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

            editPost(post) {
                post.showedit = !post.showedit;
                axios.post('editPost', post)
                .then((response) => {
                    
                });
            }, 

            deletePost: function(post, index) {
                axios.post('deletePost', post)
                .then((response) => {
                    this.posts.splice(index, 1);
                });
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
            editComment(comment) {
                comment.action = !comment.action;
                axios.post('/editComment', comment)
                .then((response) => {
                    this.getComments();
                });
            },
            deleteComment: function(comment, index) {
                axios.post('deleteComment', comment)
                .then((response) => {
                    this.comments.splice(index, 1);
                });
            },
        },

    }
</script>

<style lang="scss" scoped>
.form {
    border-radius: 10px;
    height: 200px;

    #status {
        margin: 10px 40px;
        outline: 0;
        border: none;
        resize: none;
    }
    .visi-hid {
        opacity: 0;
        position: relative;
        z-index: 1000;
    }
    #fake-input {
        position: absolute;
        top: 0;
        left: 16px;
        height: 35px;
        width: 294px;
    }
    
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
        }
        p:hover {
            background: #9d9d9d;
            border-radius: 7px;
        }
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
    margin-right: 10px;
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