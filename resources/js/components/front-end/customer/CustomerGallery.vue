<template>
    <div class="container py-5">
        <div class="row d-flex justify-content-between">

            <div class="col-12 text-right d-none">
                <button
                    class="btn add-photo-tour custom-font-family  add-post-modal-btn btn-primary text-white px-3 py-0 ct-p "
                    title="Add Photo" data-toggle="modal" data-target="#addPostModal" style="min-height: 40px">
                    <i class="ti-plus font-weight-800"></i> Add Photo
                </button>
            </div>

            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="ct-card-header gallery-heading custom-font-family" style="font-weight: bold;">Gallery</h1>
                    <button
                        class="btn mt-4 add-gallery-btn add-post-modal-btn btn-primary  ct-p "
                        title="Add Photo" data-toggle="modal" data-target="#addPostModal">
                        <i class="ti-plus font-weight-800"></i> Add Photo
                    </button>
                </div>
            </div>


            <div class="col-md-12">
                

                <div class="btn-group mr-2 float-right mb-3" role="group" aria-label="First group">
                    <button v-if="backupPosts.length > 0" type="button" class="btn border pb-2"
                        @click="toggleViewPort(4)"><img src="../../../public/4x4.png" alt="" width="20"
                            height="20"></button>
                    <button v-if="backupPosts.length > 0" type="button" class="btn border pb-2"
                        @click="toggleViewPort(2)"><img src="../../../public/2x2.png" alt="" width="20"
                            height="20"></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group cursor-pointer">
                    <li class="list-group-item ct-pl" :class="isActive == 'all' ? 'active' : ''" @click="filter('all')"
                        v-if="backupPosts.length != 0">All</li>
                    <li class="list-group-item ct-pl" :class="isActive == index ? 'active' : ''" @click="filter(index)"
                        :key="index" v-for="(post, index) in backupPosts">
                        {{ index }}
                    </li>
                </ul>
            </div>
            <div class="col-md-9 mt-md-0 mt-4">
                <div class="timeline">
                    <div class="timeline-item" v-for="(post, index) in posts">
                        <div>
                            <figure class="avatar avatar-sm bring-forward">
                                <span class="avatar-title bg-dark-bright text-dark rounded-circle">
                                    <i class="fa fa-circle"></i>
                                </span>
                            </figure>
                        </div>
                        <div>
                            <div class="line-container">
                                <div class="line"></div>
                                <div class="text">
                                    <h5><span class="ct-card-header">{{ index }}</span></h5>
                                </div>
                                <div class="line"></div>
                            </div>
                            <div class="row row-xs timeline-row">
                                <div :class="gridCols" class="mb-2" v-for="p in post">
                                    <router-link :to="'post/' + p.id">
                                        <div class="position-relative overflow-hidden">
                                            <img class="img-fluid image psot-image" :src="p.image" alt=""
                                                style="width: 100%;height: 300px!important;">
                                            <div class="position-absolute"
                                                style="background-image:linear-gradient(rgba(255, 0, 0, 0), black);height: 65px;width: 100%;bottom: 0">
                                            </div>
                                            <div class="position-absolute px-2 text-white align-items-center ct-pl"
                                                style=" bottom: 8px;width: 100%">
                                                <div class="col-md-12 d-flex justify-content-between">
                                                    <img :src="p.user.profile" class="me-2 img-fluid"
                                                        style="width: 30px;height: 30px;object-fit: cover;border-radius: 50%"
                                                        alt="">
                                                    <div>
                                                        {{ p.comments.length }} <i class="fa fa-comment"></i>
                                                    </div>
                                                </div>
                                                <div class="feed-user">
                                                    <p class="caption text-left ct-p mb-0 single-line ">{{ p.caption }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="posts.length == 0" class="col-md-12 text-center">
                <img src="../../../public/no_post.png" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CustomerGallery',
    props: {
        posts: {
            type: undefined,
            required: true,
        },
        backupPosts: {
            type: undefined,
            required: true,
        },
        isActive: {
            type: undefined,
            required: true,
        },
        filter: {
            type: undefined,
            required: true,
        },
    },
    data() {
        return {
            gridCols: 'col-lg-3 col-md-6 col-12',
        }
    }
}
</script>
