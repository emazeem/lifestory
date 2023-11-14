<template>
    <button v-if="showAddPostBtn" class="btn btn-dark px-4 py-2 add-new-post-tour add-post-btn" title="Add a New Post"
        data-toggle="modal" data-target="#addPostModal">
        <i class="fa fa-plus d-md-none d-sm-block"></i>
        <span class="ct-p d-md-block d-none">Add Photo</span>
    </button>
    <div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10000;">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add your memory
                    </h5>
                    <button type="button" class="close" id="modal-hide" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="createPostError" class="alert alert-danger alert-dismissible fade show position-relative"
                        role="alert">
                        <ul>
                            <li v-for="e in createPostError">{{ e }}</li>
                        </ul>
                        <p class=" position-absolute top-0 right-0 mr-2 cursor-pointer" @click="createPostError = ''"
                            aria-hidden="true">&times;</p>
                    </div>
                    <form id="addPostForm" method="post" @submit.prevent="storePost()">
                        <div class="form-group">
                            <label class="col-form-label">Caption</label>
                            <input type="text" v-model="formData.caption" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">When was photo taken?</label>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <select id="monthSelect" v-model="formData.month" class="form-control"></select>
                            </div>
                            <div class="col">
                                <select id="yearSelect" v-model="formData.year" class="form-control"></select>
                            </div>
                        </div>

                        <div class="col-md-12 text-center mt-5">
                            <div id="image-container" class="text-center"
                                onclick="document.getElementById('image').click()"></div>
                            <input type="file" style="display: none" id="image" @change="readImageData($event)">
                            <i class="img-thumbnail cursor-pointer p-3" onclick="document.getElementById('image').click()">
                                Click to Upload Image
                            </i>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-9 text-right mt-3">
                                <button type="submit" :disabled="disabledBtnCreatePost" id="btn-save"
                                    class="btn btn-primary add-post-modal-btn mt-4">
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
export default {
    name: 'AddPost',
    props: {
        fetchPosts1: {
            type:Function,
            required:true,
        },
        showAddPostBtn: {
            type: Boolean,
            required: true,
            default: false
        },
    },
    data() {
        return {
            months: ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            years: ['', ...Array.from({ length: 130 }, (_, index) => new Date().getFullYear() - index)],
            monthMapping : {
                'January': 1,
                'February': 2,
                'March': 3,
                'April': 4,
                'May': 5,
                'June': 6,
                'July': 7,
                'August': 8,
                'September': 9,
                'October': 10,
                'November': 11,
                'December': 12
            },
            max: new Date().toISOString().slice(0, 7),
            disabledBtnCreatePost: false,
            formData: {
                id: '',
                caption: '',
                month: '',
                year: '',
                image: '',
            },
            createPostError: '',
        }
    },
    methods: {
        storePost() {
            this.disabledBtnCreatePost = true;
            var image = document.getElementById('image');
            const formDatum = new FormData();
            formDatum.append('image', image.files[0]);
            formDatum.append('caption', this.formData.caption);

            const selectedMonth = $('#monthSelect').val();
            const selectedYear = $('#yearSelect').val();

            if(selectedMonth)
            {
                this.formData.month =  this.monthMapping[selectedMonth];
                formDatum.append('month', this.formData.month);
            }
            if(selectedYear)
            {
                this.formData.year = selectedYear;
                formDatum.append('year', this.formData.year);
            }

            this.axios.post('post/store', formDatum).then((response) => {
                this.createPostError = '';
                document.getElementById('modal-hide').click();
                this.$moshaToast({ title: response.data.message }, { type: 'success', showIcon: true, hideProgressBar: true });
                this.formData = {
                    id: '',
                    caption: '',
                    month: '',
                    year: '',
                    image: '',
                };
                var imageContainer = document.getElementById('image-container');
                imageContainer.innerHTML = '';
                image.value = '';
                $("#monthSelect").val(null).trigger("change");
                $("#yearSelect").val(null).trigger("change");
                this.fetchPosts1();
            }).catch((error) => {
                this.createPostError = this.$flattenErrors(error);
            }).finally(() => {
                this.disabledBtnCreatePost = false;
            });
        },
        readImageData(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (event) {
                const imageUrl = event.target.result;
                const image = document.createElement('img');
                image.src = imageUrl;
                image.style = "height:300px;width:300px;object-fit:cover";
                var imageContainer = document.getElementById('image-container');
                imageContainer.innerHTML = '';
                imageContainer.appendChild(image);
            };

            reader.readAsDataURL(file);
        },
        initializeSelect2() {
            setTimeout(() => {
                $('#monthSelect').select2({
                    placeholder: 'month',
                    allowClear: false,
                    data: this.months,
                });
                $('#yearSelect').select2({
                    placeholder: 'yyyy',
                    allowClear: false,
                    data: this.years,
                });
            }, 0);
        }
    },
    mounted() {
        this.initializeSelect2();
    }
}
</script>
