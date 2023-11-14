<template>
    <div class="page" style="position: relative">
        <button class="btn btn-dark px-4 py-2 add-new-post-tour" data-toggle="modal" data-target="#addPostModal" style="border-radius:7px;font-size:20px;white-space:nowrap;position: fixed;right: 30px;top: 85vh;z-index: 10000">
            <!-- <i class="fa fa-plus"></i> -->
            <span class="ct-p">Add a New Post</span>
        </button>
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
                <div id="home" class="customer-story">
                    <div>
                        <div class="row m-0">
                            <div class="col-lg-12 text-center p-0">
                                <video style="height:80vh" class="customer-video-banner w-100" controls
                                    :src="customer.video.file" v-if="customer.video"></video>
                                <img v-else  src="../../public/video-thumbnail.png" alt="" style="width: 100%;height: 80vh;object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Services-->
            <div class="section customizable" id="our-services">
                <div v-if="errors" class="alert col-md-8 mx-auto alert-danger alert-dismissible fade show position-relative" role="alert">
                    <ul>
                        <li v-for="(e,index) in errors" :key="index"> {{ e }} </li>
                    </ul>
                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                        &times;
                    </p>
                </div>
                <div class="row overflow-hidden m-0">
                    <div class="col-md-6 align-items-center p-5 about-life-story">
                        <div class="text-center">
                            <h3 class="ct-p">About {{ customer.fullName }}</h3>

                            <div class="mx-5" v-show="customer.id==userInfo.id">
                                <textarea v-model.lazy="bio" @change="updateBio()" rows="4" style="height:auto;box-shadow:none" class="form-control bg-transparent mx-5 border-0 ct-form-control bio-tour " placeholder="Write something about yourself..."></textarea>
                            </div>
                            <div class="mx-5" v-show="customer.id!=userInfo.id">
                                <textarea rows="4" style="height:auto;box-shadow:none" class="form-control ct-p bg-transparent mx-5 border-0 bio-tour ct-form-control " placeholder="Write something about yourself...">{{ customer.details ? customer.details.bio : '' }}</textarea>
                            </div>


                            <a href="#contact" class="btn d-none btn-pill btn-create-your-story btn-w-md py-2 me-2">Contact Lifestory</a>
                        </div>
                    </div>
                    <div class="col-md-6 p-5 customer-image profile-details-tour customize-image ">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="card bg-transparent border-0 shadow-none">
                                    <div class="card-body ct-p">
                                        <div class="row mb-2">
                                            <div class="col-4 text-muted">Full Name:</div>
                                            <div class="col-6">{{customer.fullName}}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-4 text-muted">Email:</div>
                                            <div class="col-6">{{customer.email}}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-4 text-muted">Role:</div>
                                            <div class="col-6">{{customer.role}}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-4 text-muted">Phone:</div>
                                            <div class="col-6">{{customer.contact}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box" v-if="userInfo.id==customer.id">
                                    <label :for="(userInfo.id==customer.id?'customer_image':'')" class="d-flex justify-content-end">
                                        <img class="img-fluid cursor-pointer p-2" :src="customer.profile" alt="">
                                        <i class="fa fa-camera"></i>
                                    </label>
                                </div>
                                <div v-else>
                                    <img class="img-fluid cursor-pointer p-2" :src="customer.profile" alt="" style="width: 150px!important;height: 150px!important;object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input accept=".png, .jpeg, .jpg" id="customer_image" type="file" class="d-none" @change="imageChanged($event)">
                </div>

            </div>

            <div class="container-fluid border-top px-md-5 bg-light-c py-5 ">

                <div class="row text-center justify-content-center align-items-center">
                    <div class="col-lg-8">
                        <h3 class="mb-5 feed-title ct-card-header">Feed</h3>
                    </div>
                    <div v-if="userInfo.role=='Sub User'" class="btn-group mr-2 float-right" role="group" aria-label="First group">
                        <button type="button" class="btn " :class="(toggleValue=='all'?'btn-primary':'bg-white border')" @click="togglePosts('all')"><i class="fa fa-bars"></i> All Memories</button>
                        <button type="button" class="btn " :class="(toggleValue=='my'?'btn-primary':'bg-white border')" @click="togglePosts('my')"><i class="fa fa-bars"></i> My Memories</button>
                    </div>
                </div>
                <div class="container">
                    <div v-if="!isLoading && !posts.length" class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <img src="../../public/no_post.png" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center" v-if="isLoading">
                            <i class="fa fa-spinner fa-spin fa-5x ct-card-header"></i>
                        </div>
                        <div class="col-xl-3 feed-section text-center" v-for="(post,index) in posts" :class="(index==0?'feed-tour':'')">
                            <router-link :to="'post/'+post.id">
                                <div class="position-relative">
                                    <img class="img-fluid image" :src="post.image" alt="">

                                    <div class="position-absolute" style="background-image:linear-gradient(rgba(255, 0, 0, 0), black);height: 40px;width: 100%;bottom: 0">
                                    </div>
                                    <div class="position-absolute text-white ct-pl" style=" bottom: 8px; right: 16px;">
                                        {{post.comments.length}} <i class="fa fa-comment"></i>
                                    </div>
                                </div>
                                <p class="caption text-left ct-p mt-2 mb-0  single-line ">{{post.caption}}</p>
                                <div class="feed-user d-flex mt-1">
                                    <img :src="post.user.profile" class="me-2 mt-2 profile" alt="">
                                    <div class="author-details text-left ml-2">
                                        <h6 class="ct-pl">{{post.user.fullName}}</h6>
                                        <p class="date text-gray ct-p" style="font-size: 12px">on {{  formattedDate(post.created_at)}}</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>
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
    <div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add your memory
                    </h5>
                    <button type="button" class="close" id="modal-hide" data-dismiss="modal" @click="" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div v-if="err" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                        <ul>
                            <li v-for="e in err">{{e}}</li>
                        </ul>
                        <p class=" position-absolute top-0 right-0 mr-2 cursor-pointer" @click="err=''" aria-hidden="true">&times;</p>
                    </div>

                    <form id="addPostForm" method="post" @submit.prevent="storePost()">


                        <div class="form-group">
                            <label class="col-form-label">Caption</label>
                            <input type="text" v-model="formData.caption" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">When was photo taken?</label>
                            <input type="month" v-model="formData.month" class="form-control">
                        </div>


                        <div class="col-md-12 text-center mt-5">
                            <div id="image-container" class="text-center" onclick="document.getElementById('image').click()"></div>
                            <input type="file" style="display: none" id="image" @change="readImageData($event)">
                            <i class="img-thumbnail cursor-pointer p-3" onclick="document.getElementById('image').click()">
                                Click to Upload Image
                            </i>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-9 text-right">
                                <button type="submit" id="btn-save" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Sidebar from '../../components/front-end/Sidebar.vue';
    import Footer from '../../components/front-end/Footer.vue';
    import Loader from "../front-end/Loader.vue";
    import moment from 'moment';
    import Pusher from 'pusher-js';



    export default {
        name: 'Customer',
        data(){
            return {
                pusher_data:'',
                customer:{},
                activeCustomer:{},
                isLoading:true,
                err: '',
                errors: '',
                userInfo:{},
                posts:[],
                backupPosts:[],
                bio:"",
                video:'',
                formData:{
                    id:'',
                    caption:'',
                    month:'',
                    image:'',
                },
                toggleValue:'all',
            }
        },
        components: {
            Sidebar,
            Footer,
            Loader
        },
        methods:{
            fetchCustomer(){
                this.axios.post('user/fetch-customer-switch').then((response) => {
                    this.customer = response.data.data;
                });
            },
            formattedDate(stamp) {
                return moment(stamp).format("DD/MM/YYYY");
            },
            togglePosts(filter){
                this.toggleValue=filter;
                if (filter=='all'){
                    this.posts=this.backupPosts;
                }
                if (filter=='my'){
                    this.posts=this.backupPosts.filter((item)=>{
                        return item.user_id==this.userInfo.id;
                    })
                }

            },
            async imageChanged()
            {
                this.errors = '';
                const formData = new FormData();
                formData.append('profile', event.target.files[0]);

                await this.axios.post('user/update-profile-image', formData)
                .then(response => {
                    this.$moshaToast({
                    title: "Profile image updated successfully",
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
                    });
                    this.fetchUserInfo();
                    this.fetchCustomer();
                })
                .catch(error => {
                    this.$moshaToast({title: 'Something went wrong',}, {type: 'danger', showIcon: true, hideProgressBar: true,});
                    this.errors = this.$flattenErrors(error);
                });
            },
            updateBio()
            {
                if(this.bio.length>300)
                {
                    this.$moshaToast({title: 'Bio should be not be more than 300 characters',}, {type: 'danger', showIcon: true, hideProgressBar: true,});
                    return;
                }
                this.axios.post('user/update-bio',{bio:this.bio}).then((response) => {
                    this.fetchUserInfo();
                    this.$moshaToast({title: 'Bio updated successfully',}, {type: 'success', showIcon: true, hideProgressBar: true,});
                });
            },

            fetchUserInfo()
            {
                this.axios.get('user/info').then((response) => {
                    this.bio = response.data.data.details.bio;
                    this.userInfo = response.data.data;
                });
            },
            fetchPosts()
            {
                this.isLoading=true;
                this.axios.post('post/fetch').then((response) => {
                    this.posts       = response.data.data;
                    this.backupPosts = response.data.data;
                }).finally(()=>{ this.$updateFont(); });
                this.isLoading=false;
            },



            readImageData(event)
            {
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function(event) {
                    const imageUrl = event.target.result;
                    const image = document.createElement('img');
                    image.src = imageUrl;
                    image.style = "height:300px;width:300px;object-fit:cover";
                    var imageContainer=document.getElementById('image-container');
                    imageContainer.innerHTML='';
                    imageContainer.appendChild(image);
                };

                reader.readAsDataURL(file);
            },

            storePost()
            {
                var self=this;
                var image=document.getElementById('image');
                const formDatum = new FormData();
                formDatum.append('image', image.files[0]);
                formDatum.append('caption', this.formData.caption);
                formDatum.append('month', this.formData.month);

                Array.from(document.getElementsByClassName("form-control")).forEach((element) => element.classList.remove("is-invalid"));
                this.axios.post('/post/store',formDatum).then(function (xhr) {
                    self.err='';
                    document.getElementById('modal-hide').click();
                    self.$moshaToast({title: xhr.data.message,},{type: 'success',showIcon: true,hideProgressBar: true});
                    self.formData = {};
                    var imageContainer=document.getElementById('image-container');
                    imageContainer.innerHTML='';
                    self.fetchPosts();
                }).catch(function (error){
                    self.err=self.$flattenErrors(error);
                });
            },

            startTour()
            {
                var enjoyhint_instance = new EnjoyHint({});

                enjoyhint_instance.set([
                    {
                        'next .navbar-tour': 'Website menu.',
                    },
                    {
                        'next .customer-video-banner': 'Customer life story video.',
                    },
                    {
                        'next .bio-tour': 'Your bio.',
                    },
                    {
                        'next .profile-details-tour': 'Your profile details.',
                    },
                    {
                        'next .feed-tour': 'Feed contains your and customer memories.',
                    },
                    {
                        'next .add-new-post-tour': 'Here you can add new post',
                    }
                ]);

                enjoyhint_instance.run();
                localStorage.setItem("tour-viewed", true);
            }
        },
        mounted() {
            this.fetchUserInfo();
            this.fetchPosts();
            this.fetchCustomer();
            if(!localStorage.getItem("tour-viewed"))
            this.startTour();
        }
    }

</script>

<style scoped>
.btn.btn-primary:not(:disabled):not(.disabled):focus {
     -webkit-box-shadow:none!important;
    -moz-box-shadow: none!important;
     box-shadow: none!important;
}
.feed-section .image{
    width: 100%;
    height: 300px;
    object-fit: cover;
}


</style>
