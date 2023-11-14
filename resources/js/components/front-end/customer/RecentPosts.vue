<template>
    <h1 class="feed-title font-size-13 ct-card-header mt-2 mb-2 d-block d-md-none gallery-heading-mobile">Recent Gallery
        Activity and Uploads</h1>
    <button
        class="btn float-right mb-3 add-photo-tour gallery-heading-mobile add-post-in-tour  d-block d-md-none add-post-modal-btn btn-primary text-white px-3 py-0 ct-p "
        title="Add Photo" data-toggle="modal" data-target="#addPostModal" style="min-height: 40px">
        <i class="ti-plus font-weight-800"></i> Add Photo
    </button>
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3" style="margin:auto -15px">
        <h1 class="feed-title ct-card-header mt-2 mb-2 d-md-block d-none">Recent Gallery Activity and Uploads</h1>
        <button
            class="btn add-photo-tour add-post-in-tour  add-post-modal-btn d-md-block d-none btn-primary text-white px-3 py-0 ct-p "
            title="Add Photo" data-toggle="modal" data-target="#addPostModal" style="min-height: 40px">
            <i class="ti-plus font-weight-800"></i> Add Photo
        </button>

    </div>
    <div class="recent-posts-tour">
        <div class="col-md-12 text-center" v-if="isLoading">
            <i class="fa fa-spinner fa-spin fa-5x ct-card-header"></i>
        </div>

        <div id="carouselExampleIndicatorsTablet" class="carousel slide position-relative" data-interval="false"
            data-ride="carousel">
            <div class="carousel-inner">
                <div v-for="groupIndex in Math.ceil(posts.length / postsPerItem)" :key="groupIndex" class="carousel-item"
                    :class="(groupIndex === 1 ? 'active' : '')">
                    <div class="d-flex row justify-content-start">
                        <div v-for="item in postsPerItem" :key="(groupIndex - 1) * postsPerItem + item"
                            class="carousel-item-inner col-lg-3 col-md-6 col-12">
                            <div v-if="(groupIndex - 1) * postsPerItem + item - 1 < posts.length"
                                class="post post-on-tablet" :class="(groupIndex === 1 ? 'recent-post-single-tour' : '')">
                                <!-- Display post content here -->

                                <router-link v-if="!isAdmin"
                                    :to="'post/' + posts[(groupIndex - 1) * postsPerItem + item - 1].id" class="card">
                                    <img class="w-100" style="min-height:300px; max-height:300px;object-fit:cover"
                                        :src="posts[(groupIndex - 1) * postsPerItem + item - 1].image">
                                    <div class="recent-post-caption my-3 py-1 ct-pl">

                                        <PostCaption :caption="posts[(groupIndex - 1) * postsPerItem + item - 1].caption" />

                                        <span class="p-0 m-0" style="white-space:nowrap">
                                            <img class="post-comment-img" src="../../../front-end-assets/comment-icon.svg">
                                            {{ posts[(groupIndex - 1) * postsPerItem + item - 1].comments.length }}
                                        </span>
                                    </div>
                                    <div class="author-details text-left ml-2 ">
                                        <img :src="posts[(groupIndex - 1) * postsPerItem + item - 1].user.profile"
                                            class="me-2 mt-2 profile" width="30" height="30" style="border-radius: 50%">
                                        <h6 class="ct-pl ml-2">{{ posts[(groupIndex - 1) * postsPerItem + item -
                                            1].user.fullName
                                        }}</h6>
                                    </div>
                                </router-link>

                                <div v-else
                                    class="card">
                                    <img class="w-100" style="min-height:300px; max-height:300px;object-fit:cover"
                                        :src="posts[(groupIndex - 1) * postsPerItem + item - 1].image">
                                    <div class="recent-post-caption my-3 py-1 ct-pl">

                                        <PostCaption :caption="posts[(groupIndex - 1) * postsPerItem + item - 1].caption" />

                                        <span class="p-0 m-0" style="white-space:nowrap">
                                            <img class="post-comment-img" src="../../../front-end-assets/comment-icon.svg">
                                            {{ posts[(groupIndex - 1) * postsPerItem + item - 1].comments.length }}
                                        </span>
                                    </div>
                                    <div class="author-details text-left ml-2 ">
                                        <img :src="posts[(groupIndex - 1) * postsPerItem + item - 1].user.profile"
                                            class="me-2 mt-2 profile" width="30" height="30" style="border-radius: 50%">
                                        <h6 class="ct-pl ml-2">{{ posts[(groupIndex - 1) * postsPerItem + item -
                                            1].user.fullName
                                        }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a v-if="posts.length > 0" class="carousel-control-prev carousel-slide-buttons left-side"
                href="#carouselExampleIndicatorsTablet" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a v-if="posts.length > 0" class="carousel-control-next carousel-slide-buttons right-side"
                href="#carouselExampleIndicatorsTablet" role="button" data-slide="next">
                <span class="carousel-control-next-icon" ariahidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>



        <div v-if="!isLoading && !posts.length" class="row recent-posts-tour align-items-center">
            <div class="col-md-12 text-center">
                <img src="../../../public/no_post.png" alt="" class="img-fluid">
            </div>
        </div>

        <div class="col-md-12 text-center mt-2 mb-5" v-show="!isAdmin && posts.length > 4">
            <router-link class="btn btn-primary add-post-modal-btn ct-p customer-show-all-posts" v-if="!showAll"
                to="/gallery">View
                Gallery</router-link>
            <router-link class="btn btn-primary add-post-modal-btn ct-p customer-show-all-posts" v-else :to="showAll">View
                Gallery</router-link>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import PostCaption from './PostCaption.vue';

export default {
    props: {
        posts: {
            type: Array,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false
        },
        showAll: {
            type: String,
            required: false,
        },
        isAdmin: {
            type: Boolean,
            required: false,
            default: false
        },
    },
    components: {
        PostCaption
    },
    methods: {
        formattedDate(stamp) {
            return moment(stamp).format("DD/MM/YYYY");
        }
    },
    computed: {
        postsPerItem() {
            if (window.innerWidth < 768) {
                return 1;
            } else if (window.innerWidth < 1024) {
                return 2;
            } else {
                return 4;
            }
        },
    },
};
</script>

<style scoped>
a.carousel-slide-buttons {
    position: absolute;
    /*top: 35%;*/

}

.carousel-control-prev,
.carousel-control-next {
    width: 50px;
    height: 50px;
    background-color: black;
    margin-right: -50px;
    margin-left: -50px;
    top: 40%;
    border-radius: 50%;
}
</style>
