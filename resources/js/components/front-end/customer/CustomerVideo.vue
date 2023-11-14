<template>

    <div class="col-md-12 video-section-mobile text-center p-0">
        <video :poster="customer.video.poster"  class="shadow-sm customer-video-banner customer-video-tour-new" controls disablepictureinpicture controlslist="nodownload noplaybackrate" :src="customer.video.file" v-if="customer.video"></video>
        <img class="customer-video-tour-new" v-else src="../../../public/video-thumbnail.png" alt="" style="width: 100%; object-fit: cover;">

    </div>

</template>

<script>
export default {
    name: 'CustomerVideo',
    props: {
        customer: {
            type: undefined,
            required: true,
        }
    },


}
</script>

<style>
video.customer-video-banner {
    width: 100%;
    height: 100%;
    max-height: 500px !important;
}
</style>
