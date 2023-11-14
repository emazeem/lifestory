<template>
    <div id="main">
        <Sidebar/>
        <div class="main-content">
            <CustomerGallery :posts="posts" :backupPosts="backupPosts" :filter="filter" :isActive="isActive" />

        </div>
    </div>
</template>

<script>
import Sidebar from '../../components/admin/Sidebar.vue';
import Header from '../../components/admin/Header.vue';
import CustomerGallery from "../../components/front-end/customer/CustomerGallery.vue";

export default {
    name: "AsCustomerGallery",
    components: {
        CustomerGallery,
        Header,
        Sidebar,
    },
    data() {
        return {
            userId:'',
            posts:{},
            backupPosts:[],
            isActive:'all',
        };
    },
    methods: {
        fetchPosts()
        {
            this.axios.post('post/timeline?id='+this.userId).then((response) => {
                this.posts = response.data.data;
                this.backupPosts = response.data.data;
            }).catch(() => {
                this.isLoading=false;
            }).finally(()=>{ this.$updateFont(); });
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
    },
    created() {
        this.userId=this.$route.params.id;
        this.fetchPosts();

    },
};
</script>

