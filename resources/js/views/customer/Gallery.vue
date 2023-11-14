<template>
    <div class="page" style="position: relative">
        <AddPost :showAddPostBtn="false" :fetchPosts1="fetchPosts"/>
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
            </div>
           <CustomerGallery :posts="posts" :backupPosts="backupPosts" :filter="filter" :isActive="isActive" />
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
    import AddPost from '../../components/front-end/customer/AddPost.vue';
    import CustomerGallery from '../../components/front-end/customer/CustomerGallery.vue';
    import Footer from '../../components/front-end/Footer.vue';
    import Loader from "../front-end/Loader.vue";

    export default {
        name: 'Gallery',
        data(){
            return {
                posts:{},
                backupPosts:[],
                isActive:'all',
                user:{},                
                err:'',
            }
        },
        components: {
            CustomerGallery,
            Sidebar,
            AddPost,
            Footer,
            Loader
        },
        methods:{
            fetchuser()
            {
                this.axios.get('user/info').then((response) => {
                    this.user = response.data.data;
                })
            },
            filter(data)
            {
                this.isActive=data;
                if (data=='all'){
                    this.posts=this.backupPosts;
                }else{
                    this.posts={
                        [data]:this.backupPosts[data]
                    };
                }
                setTimeout(() => { this.$updateFont(); }, 0);
            },
            fetchPosts()
            {
                this.isLoading=true;
                this.axios.post('post/timeline').then((response) => {
                    this.isLoading=false;
                    this.posts = response.data.data;
                    this.backupPosts = response.data.data;
                }).catch(() => {
                    this.isLoading=false;
                }).finally(()=>{ this.$updateFont(); });
            },
            toggleViewPort(col){
                if (col==2){
                    this.gridCols='col-md-6 col-12';
                }
                if (col==4){
                    this.gridCols='col-md-3 col-6';
                }
            }
        },
        mounted()
        {
            this.fetchuser();
            this.fetchPosts();
        }
    }
</script>
<style scoped>
.timeline-image{
    height: 200px;
    width: 100%;
    object-fit: cover;
}
.line-container {
    display: flex;
    align-items: center;
}

.line {
    flex-grow: 1;
    height: 1px;
    border:1px dashed #ccc;
}
.text {
    padding: 0 10px;
    margin-top: 4px;
    color: #333;
}
.timeline-row{
    height: 600px;
    overflow-y: scroll;
}
</style>
