<template>
    <div v-if="!isLoading && !posts.length" class="row recent-posts-tour align-items-center">
        <div class="col-md-12 text-center">
            <img src="../../../public/no_post.png" alt="" class="img-fluid">
        </div>
    </div>
    <p class="feed-title font-size-13 ct-card-header my-4 d-block d-md-none">Recent Gallery Activity and Uploads</p>
    <div class="d-flex justify-content-between align-items-center my-5">
        <h1 class="feed-title ct-card-header my-4 d-md-block d-none">Recent Gallery Activity and Uploads</h1>
        <button class="btn add-photo-tour  add-post-modal-btn btn-primary text-white px-3 py-0 ct-p " title="Add Photo"
            data-toggle="modal" data-target="#addPostModal" style="min-height: 40px">
            <i class="ti-plus font-weight-800"></i> Add Photo
        </button>

    </div>
    <div class="row align-items-center justify-content-center recent-posts-tour position-relative">
        <div class="col-md-12 text-center" v-if="isLoading">
            <i class="fa fa-spinner fa-spin fa-5x ct-card-header"></i>
        </div>
        <div class="scroll-btns" v-if="posts.length > 3">
            <button @click="scrollLeft" class="btn"><i class="fa-solid carosel-buttons fa-arrow-left"></i></button>
            <button @click="scrollRight" class="btn"><i class="fa-solid carosel-buttons fa-arrow-right"></i></button>
        </div>
        <div class="item-container" ref="container">
            <div class="col-lg-3 px-4 col-sm-12 col-12 " v-for="(post, index) in posts" :class="(index == 0 ? 'feed-tour' : '')">
                <div class="feed-section text-center">
                    <router-link v-if="!isAdmin" :to="'post/' + post.id" class="card recent-post-card">
                        <div>
                            <img class="img-fluid image recent-post-image" :src="post.image">
                            <div class="recent-post-caption my-3 ct-pl">
                                <p class="ct-p pt-2">{{ post.caption }}</p>
                                <span class="p-0 m-0"><img class="post-comment-img"
                                        src="../../../front-end-assets/comment-icon.svg" alt="">
                                    {{ post.comments.length }}</span>
                            </div>

                        </div>
                        <div class="author-details text-left ml-2 ">
                            <img :src="post.user.profile" class="me-2 mt-2 profile" alt="" width="30" height="30"
                                style="border-radius: 50%">
                            <h6 class="ct-pl ml-2">{{ post.user.fullName }}</h6>
                        </div>
                    </router-link>
                    <div v-else :to="'post/' + post.id" class="card">
                        <div>
                            <img class="img-fluid image recent-post-image" :src="post.image">
                            <div class="recent-post-caption my-3 ct-pl">
                                <p class="ct-p recent-post-caption-text">{{ post.caption }}</p>
                                <span><img class="post-comment-img" src="../../../front-end-assets/comment-icon.svg" alt="">
                                    {{ post.comments.length }}</span>
                            </div>

                        </div>
                        <div class="author-details text-left ml-2 ">
                            <img :src="post.user.profile" class="me-2 mt-2 profile" alt="" width="50" height="50"
                                style="border-radius: 50%">
                            <h6 class="ct-pl">{{ post.user.fullName }}</h6>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-md-12 text-center mt-3" v-show="!isAdmin && posts.length > 4">
            <router-link class="btn btn-primary ct-p customer-show-all-posts" v-if="!showAll" to="/gallery">View Gallery</router-link>
            <router-link class="btn btn-primary ct-p customer-show-all-posts" v-else :to="showAll">View Gallery</router-link>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    props: {
        posts: Array,
        isLoading: Boolean,
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
    methods: {
        formattedDate(stamp) {
            return moment(stamp).format("DD/MM/YYYY");
        },
        scroll(container, targetScrollLeft, duration) {
            const initialScrollLeft = container.scrollLeft;
            const startTime = performance.now();

            function scrollStep(timestamp) {
                const currentTime = timestamp - startTime;

                if (currentTime < duration) {
                    const scrollAmount =
                        initialScrollLeft +
                        ((targetScrollLeft - initialScrollLeft) * (currentTime / duration));
                    container.scrollLeft = scrollAmount;
                    requestAnimationFrame(scrollStep);
                } else {
                    container.scrollLeft = targetScrollLeft;
                }
            }

            requestAnimationFrame(scrollStep);
        },

        scrollLeft() {
            const container = this.$refs.container;
            const isMobile = this.isMobileDevice();
            const targetScrollLeft = container.scrollLeft - isMobile;
            const duration = isMobile == 345 ? 0 : 500;

            this.scroll(container, targetScrollLeft, duration);
        },

        scrollRight() {
            const container = this.$refs.container;
            const isMobile = this.isMobileDevice();
            const targetScrollLeft = container.scrollLeft + isMobile;
            const duration = isMobile == 345 ? 0 : 500;

            this.scroll(container, targetScrollLeft, duration);
        },

        isMobileDevice() {
            if (window.innerWidth <= 768) {
                return 345; // Adjust the mobile scroll distance as needed
            } else {
                return 400; // Adjust the desktop scroll distance as needed
            }
        }


    },
};
</script>

<style scoped>
.recent-post-caption p {
    width: 70% !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
</style>
