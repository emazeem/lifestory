<template>
    <div class="page" style="position: relative">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
            </div>
            <!-- Our Services-->
            <div class="section customizable" id="our-services">
                <div v-if="errors" class="alert col-md-8 mx-auto alert-danger alert-dismissible fade show position-relative"
                    role="alert">
                    <ul>
                        <li v-for="(e, index) in errors" :key="index"> {{ e }} </li>
                    </ul>
                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                        &times;
                    </p>
                </div>
            </div>
            <div class="container-fluid px-md-5 bg-light-c py-5 ">
                <div class="col-md-12 text-center" v-if="isLoading">
                    <i class="fa fa-spinner fa-spin fa-5x"></i>
                </div>
                <div class="row" v-if="post" v-show="!isLoading">

                    <div class="col-md-6 bg-white feed-section p-0">
                        <div class="feed-user d-flex justify-content-between mt-3 border-bottom">
                            <div class="row align-items-center">
                                <img :src="post.user.profile" class="profile ml-4 mb-2" alt="">
                                <h6 class="ct-pl ml-2">{{ post.user.fullName }}</h6>
                            </div>
                            <div class="author-details mr-2">
                                <p class="date text-gray ct-p">&nbsp; {{ formattedDate(post.created_at) }}</p>
                            </div>
                        </div>
                        <p class="caption font-size-40 text-left mt-2 ml-4 mb-0 single-line ct-card-header">{{ post.caption
                        }}
                        </p>
                        <hr>
                        <img class="img-fluid image bg-gray-100" :src="post.image" alt="" style="height: 50vh;">
                    </div>
                    <div class="col-md-6 border p-0">

                        <div class="bg-light col-md-12 p-2 comment-box-wrap">
                            <div v-if="isLoadingComments" class="p-4 text-center">
                                <i class="fa fa-circle-o-notch fa-2x fa-spin"></i>
                            </div>
                            <div v-if="!isLoadingComments" v-for="c in comments"
                                class="border-bottom d-flex flex-column flex-md-row  py-2 px-md-2">
                                <div class="pl-small-5 post-comment-w">
                                    
                                    <div class="d-flex justify-content-between pr-2">
                                        <h6 class="m-0 ct-pl mb-3 d-flex align-items-center" style="white-space: nowrap">
                                            <img :src="c.user.profile" alt="" width="35" height="35"
                                                style="object-fit: cover;border-radius: 50%">
                                            <span class="ml-2">{{ c.user.fullName }}</span>
                                        </h6>
                                        <p class="my-0 text-primary  ct-p mt-2 ml-2" v-if="showEditDelete(c)">
                                            <b class="mr-2 cursor-pointer" @click="editComment(c)">Edit</b>
                                            <b class="cursor-pointer" @click="deleteComment(c.id)">Delete</b>
                                        </p>
                                    </div>
                                    <p style="line-height: 1.5rem"><span class="m-0 py-0 ct-card-header ">{{ c.comment
                                    }}</span></p>
                                    <p class="text-center ct-p ml-5 d-lg-none d-block float-right mr-1" style="white-space: nowrap">{{ c.created_at_h }}</p>
                                </div>
                                <div class="post-comment-datetime text-right mr-2">
                                    <!--                                    <p class="my-0 ct-p"><span>{{ formattedDate(post.created_at)}}</span></p>
                                    <p class="my-0 ct-p"><span>{{ formattedTime(post.created_at)}}</span></p>
                                    -->
                                    <p class="my-0 ct-p d-lg-block d-none" style="white-space: nowrap">{{ c.created_at_h }}</p>

                                </div>
                            </div>
                        </div>

                        <form class="col-md-12  p-0 m-0">
                            <div class="input-group">
                                <input type="text"
                                    class="form-control box-shadow-0 border-gray-400 rounded-0 ct-form-control" id="comment"
                                    placeholder="Write something..." v-model="comment">
                                <div class="input-group-append position-relative">
                                    <button v-if="mode == 'add'" class=" btn btn-light border rounded-0"
                                        @click.prevent="storeComment()"><i class="fa fa-send ct-form-control"></i></button>
                                    <button v-if="mode == 'edit'" class=" btn btn-light border rounded-0"
                                        @click.prevent="updateComment()"><i
                                            class="fa fa-refresh ct-form-control"></i></button>
                                    <i class="fa fa-times-circle position-absolute"
                                        style="z-index: 10;right: -5px;top: -5px" v-if="mode == 'edit'"
                                        @click="cancelEditing()"></i>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="">
            <div class="side-app">
                <div class="main-container">
                    <Footer></Footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Sidebar from '../../components/front-end/Sidebar.vue';
import Footer from '../../components/front-end/Footer.vue';
import moment from 'moment';
import Loader from "../front-end/Loader.vue";

export default {
    name: 'Post',
    data() {
        return {
            id: '',
            post: '',
            isLoading: true,
            isLoadingComments: true,
            err: '',
            errors: '',
            bio: "",
            comment: '',
            comments: '',
            mode: 'add',
            editId: 0,
        }
    },
    components: {
        Sidebar,
        Footer,
        Loader
    },

    watch: {
        '$route.params.id': function (newId, oldId) {
            this.fetchPost();
            this.fetchComments();
        },
    },
    computed: {
        myBusinessValue() {
        }
    },

    methods: {

        formattedDate(stamp) {
            return moment(stamp).format("MM/DD/YYYY");
        },
        formattedTime(stamp) {
            return moment(stamp).format("hh:mmA");
        },
        showEditDelete(c) {
            var authId = JSON.parse(localStorage.getItem('user')).id;
            return authId == c.user_id;
        },
        fetchPost() {
            this.isLoading = true;
            this.axios.post('post/fetchSingle', { 'id': this.$route.params.id }).then((response) => {
                this.isLoading = false;
                this.post = response.data.data;
            }).finally(() => {
                this.isLoading = false;
            });
        },
        fetchComments() {
            this.isLoadingComments = true;
            this.axios.post('comment/fetch', { 'id': this.$route.params.id }).then((response) => {
                this.comments = response.data.data;
                this.isLoadingComments = false;

            }).catch(() => {
                this.isLoadingComments = false;
            }).finally(() => { this.$updateFont(); });
        },

        storeComment() {
            if (this.comment != '') {

                this.axios.post('comment/store', { 'post_id': this.id, 'comment': this.comment }).then((response) => {
                    this.fetchComments();
                    this.comment = '';
                    this.$moshaToast({ title: response.data.message, }, { type: 'success', showIcon: true, hideProgressBar: true });
                });
            }
            else this.$moshaToast({ title: 'Please type a comment' }, { type: 'danger', showIcon: true, hideProgressBar: true });

        },
        updateComment() {

            this.axios.post('comment/update', { 'comment_id': this.editId, 'comment': this.comment }).then((response) => {
                this.fetchComments();
                this.comment = '';
                this.$moshaToast({ title: response.data.message, }, { type: 'success', showIcon: true, hideProgressBar: true });
                this.cancelEditing();
            });
        },
        deleteComment(id) {
            swal({
                title: 'Are you sure to delete this comment?',
                text: 'It will be permanently deleted!',
                type: 'warning',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
                .then((result) => {
                    if (result) {
                        this.axios.post('comment/delete', { 'id': id })
                            .then((response) => {
                                this.fetchComments();
                                this.comment = '';
                                this.$moshaToast({ title: response.data.message }, { type: 'success', showIcon: true });
                                this.cancelEditing();
                            });
                    }
                });

        },

        editComment(c) {
            this.comment = c.comment;
            this.mode = 'edit';
            this.editId = c.id;
        },
        cancelEditing() {
            this.comment = null;
            this.mode = 'add';
            this.editId = 0;
        },


    },
    mounted() {
        this.fetchPost();
        this.fetchComments();
    },
    created() {
        this.id = this.$route.params.id;
    }
}

</script>

<style scoped>
.feed-section .image {
    width: 100%;
    height: 450px;
    object-fit: contain;
}

.comment-box-wrap {
    height: 75vh;
    overflow-y: scroll
}

.post-comment-datetime {
    width: 20%;
}

@media (max-width: 400px) {
    .pl-small-5 {
        padding-left: 20px;
    }

    .comment-box-wrap {
        height: auto;
    }

    .post-comment-datetime {
        width: 100%;
    }
}

.date {
    margin-bottom: -3px;
}
</style>
