<template>
    <div class="row customer_details_tour" style="height: 100%;">
        <div class="card" style="height:100%;width: 100%">

            <div style="border-bottom:none !important;" class="card-body customer-profile-card">
                <div class="col-md-12 mt-2 d-flex justify-content-center">
                    <div class="customer-profile-image">
                        <div class="box" v-if="!isAdmin && userInfo1.id == customer.id">
                            <label :for="(userInfo1.id == customer.id ? 'customer_image' : '')"
                                class="d-flex justify-content-end">
                                <img class="img-fluid cursor-pointer customer-profile-image" :src="customer.profile">
                                <i class="fa fa-camera"></i>
                            </label>
                        </div>
                        <div v-else>
                            <img class="img-fluid cursor-pointer customer-profile-image" :src="customer.profile">
                        </div>
                    </div>
                </div>

                <div class="col-md-12 d-flex flex-column justify-content-center">
                    <h1 class="ct-card-header mt-3 customer-profiel-name customer-name-tour customer-name-tour-new">{{ customer.fullName }}</h1>

                    <p class="customer-membership">Member since {{ getMembership(customer.created_at) }}</p>

                    <h1 class="ct-card-header mt-3 d-none customer-profile-about">About</h1>

                    <div class="d-none">
                        <div v-show="!isAdmin && customer.id == userInfo1.id">
                            <div @blur="updateBio" @focus="clearPlaceholder" contenteditable="true"
                                class="bio-content-update p-0 ct-form-control" ref="bioDiv">
                                {{ customer.details ? (customer.details.bio ? customer.details.bio : placeholderText) :
                                    placeholderText }}
                            </div>
                        </div>
                        <div v-show="customer.id != userInfo1.id">
                            <div class="bio-content-update ct-p p-0 ct-form-control " disabled>{{ customer.details ?
                                customer.details.bio : '' }}</div>
                        </div>
                        <div v-show="isAdmin && customer.id == userInfo1.id">
                            <div class="bio-content-update ct-p ct-form-control p-0" disabled>{{ customer.details ?
                                customer.details.bio : 'Write something about yourself...' }}</div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card-footer bg-light py-3" style="border-top:none !important;">
                <div class="col-md-12 d-flex flex-column justify-content-center">
                    <p class="ct-card-header video-recording-date video-recording-date-tour mt-4" v-show="customer.video">
                        <img src="../../../front-end-assets/video-icon.svg" alt="">
                        {{ customer.video ? 'Video recording date ' + $date(customer.video.created_at) : '' }}
                    </p>
                    <a v-if="customer.video" target="_blank" class="btn cursor-pointer add-post-modal-btn btn-primary text-white my-2" :href="customer.video.file" download=""><i class="fa fa-download" aria-hidden="true"></i>Download Video</a>
                </div>
            </div>
        </div>

        <input accept=".png, .jpeg, .jpg" id="customer_image" type="file" class="d-none" @change="imageChanged($event)">
    </div>
</template>

<script>
import moment from 'moment';
export default {
    name: 'CustomerProfile',
    props: {
        isAdmin: {
            type: Boolean,
            required: false,
            default: false
        },
        customer: {
            type: undefined,
            required: true,
        },
        bio1: {
            type: undefined,
            required: true,
        },
        userInfo1: {
            type: undefined,
            required: true,
        },
        fetchUserInfo1: {
            type: undefined,
            required: true,
        },
        fetchCustomer1: {
            type: undefined,
            required: true,
        },
    },
    data() {
        return {
            placeholderText: 'Write something about yourself...',
        }
    },
    methods: {
        clearPlaceholder() {
            // Clear the placeholder text when focused
            this.$refs.bioDiv.textContent = '';
        },
        downloadVideo() {
            const videoFile = this.customer.video.file;
            const date = new Date();
            const fileExtension = videoFile.split('.').pop();
            const filename = `video_${date.getFullYear()}${(date.getMonth() + 1).toString().padStart(2, '0')}${date.getDate().toString().padStart(2, '0')}_${date.getHours().toString().padStart(2, '0')}${date.getMinutes().toString().padStart(2, '0')}${date.getSeconds().toString().padStart(2, '0')}.${fileExtension}`;
            const link = document.createElement('a');
            link.href = videoFile;
            link.download = filename;
            link.click();
        },

        getMembership(date) {
            // Convert the date from Laravel to a moment.js object
            const dateObject = moment(date, 'YYYY-MM-DD HH:mm:ss');

            // Format the date in the desired format (e.g., "Sep, 2023")
            return dateObject.format('MMM, YYYY');
        },
        async imageChanged() {
            this.errors = '';
            const formData = new FormData();
            formData.append('profile', event.target.files[0]);

            await this.axios.post('user/update-profile-image', formData)
                .then(() => {
                    this.$moshaToast({ title: "Profile image updated successfully" }, { type: 'success', showIcon: true, hideProgressBar: true });
                    this.fetchUserInfo1();
                    this.fetchCustomer1();
                }).catch(error => {
                    this.$moshaToast({ title: 'Something went wrong', }, { type: 'danger', showIcon: true, hideProgressBar: true, });
                    this.errors = this.$flattenErrors(error);
                });
        },
        updateBio(event) {

            const updatedBio = event.target.textContent;

            if (updatedBio.length > 300) {
                this.$moshaToast({ title: 'Bio should be not be more than 300 characters', }, { type: 'danger', showIcon: true, hideProgressBar: true, });
            }
            else if(updatedBio != '') {
                this.axios.post('user/update-bio', { bio: updatedBio }).then(() => {
                    this.fetchUserInfo1();
                    this.$moshaToast({ title: 'Bio updated successfully', }, { type: 'success', showIcon: true, hideProgressBar: true });
                });
            }
        }
    },
}
</script>
<style>
.form-control.bio-content-update.p-0.ct-form-control {
    max-height: 358px !important;
    overflow: auto;
}

.customer-profile-about {
    font-family: "osweld", serif;
    font-size: 15px;
    text-align: center;
    margin-bottom: 10px;
    font-weight: 600;
}

.customer-profile-card {
    border: 1px solid #ebe4e4c7 !important;
    border-radius: 4px;
}

.customer-profile-image {
    cursor: pointer;
    width: 104px !important;
    height: 104px !important;
    object-fit: cover;
    border-radius: 50%;
}

h1.ct-card-header.customer-profiel-name.customer-name-tour {
    font-family: "Oswald", serif;
    font-size: 30px !important;
    line-height: 33px;
    font-weight: 700;
    letter-spacing: 0em;
    text-align: center;
}

.video-recording-date {
    font-family: "Oswald", serif;
    font-size: 16px !important;
    font-weight: 300;
    line-height: 19px;
    letter-spacing: 0em;
    text-align: center;
    white-space: nowrap;
}

.customer-profile-hr {
    border: 0.5px solid #919191;
}

.customer-share-icons {
    margin-top: 10px;
    margin-bottom: 10px;
    gap: 15px;
}

.customer-share-icons img {
    border: 1px solid #919191;
    border-radius: 50%;
    padding: 10px;
}

.customer-share-icons i {
    border: 1px solid #919191;
    border-radius: 50%;
    padding: 10px;
}

.customer-membership {
    font-family: Oswald;
    font-size: 20px;
    font-weight: 300;
    line-height: 16px;
    letter-spacing: 0em;
    text-align: center;
    margin-top: 10px;
}</style>
