<template>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="card col-md-4 bg-white shadow-md p-5" v-if="isLoading" style="height: 70vh;display: flex;justify-content: center;align-items: center">
            <loader :show="isLoading"></loader>
        </div>
        <div v-else-if="!isLoading && error==''" class="card col-md-4 bg-white shadow-md p-5">
            <div class="mb-4 text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="text-success bi bi-check-circle"
                    width="75"
                    height="75"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"
                    />
                    <path
                        d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"
                    />
                </svg>
            </div>
            <div class="text-center">
                <h1>Success !</h1>
                <p>Zoom authorized successfully. </p>
                <router-link to="/dashboard" class="btn btn-outline-success">Dashboard</router-link>
            </div>
        </div>
        <div v-if="error!=''" class="card col-md-4 bg-white shadow-md p-5">
            <div class="mb-4 text-center">
                <h4>Something went wrong</h4>
            </div>
            <div class="text-center">
                <h1>Failed !</h1>
                <p>Try again</p>
                <router-link to="/Settings" class="btn btn-outline-success">Authorize</router-link>
            </div>
        </div>
    </div>
</template>

<script>
import Loader from './front-end/Loader.vue';
export default {
    name: "ZoomCallback",
    data() {
        return {
            code: "",
            isLoading:true,
            error: ''
        };
    },
    components:{
        Loader
    },
    methods: {
        callback(code) {
            this.error = '';
            this.axios.get("zoom/callback/" + code).then((response) => {
                this.isLoading = false;
                    if (response.data.success) {
                        this.$moshaToast(
                            {
                                title: response.data.message,
                            },
                            {
                                position: "bottom-right",
                                type: "success",
                                transition: "slide",
                                timeout: 3333,
                                showIcon: true,
                                hideProgressBar: true,
                            }
                        );
                    } else {
                        this.$moshaToast(
                            {
                                title: "Something went wrong",
                            },
                            {
                                position: "bottom-right",
                                type: "danger",
                                transition: "slide",
                                timeout: 3333,
                                showIcon: true,
                                hideProgressBar: true,
                            }
                        );

                        this.error = response.data.message;
                    }
                })
                .catch(() => {
                    this.error = "Something went wrong";
                    this.isLoading = false
                    this.$moshaToast(
                        {
                            title: "Something went wrong",
                        },
                        {
                            position: "bottom-right",
                            type: "danger",
                            transition: "slide",
                            timeout: 3333,
                            showIcon: true,
                            hideProgressBar: true,
                        }
                    );
                });
        },
    },
    mounted() {
        this.code = this.$route.query.code;
        this.callback(this.code);
    },
};
</script>
