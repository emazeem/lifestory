<template>
    <div class="uploading-video text-center">
        <span :class="{
            'status-canceled': status == 'canceled'
        }"> {{ file.fileName }} </span>
        <br>
        <h4 v-if="status == 'success'" class="text-success">done!</h4>
        <h4 v-else-if="status == 'retrying'">fault, retrying...</h4>
        <h4 v-else-if="status == 'error'" class="text-danger">error! cannot upload file</h4>
        <h4 v-else-if="status == 'canceled'" class="text-danger">cancelled</h4>
        <h4 v-else class="text-warning">uploading {{ uploadedAmount }}%</h4>
        <br>

        <div class="d-flex justify-content-center">
            <span v-if="isUploading">
                <button class="btn mr-2 rounded btn-primary" @click="isPaused ? resume() : pause()">{{ isPaused ? "resume" : "pause" }}</button>
                <button class="btn rounded btn-danger" @click="cancel()">cancel</button>
            </span>
        </div>      
    </div>
</template>

<script>
export default {
    props: [
        'file',
        'status',
        'progress',
    ],
    data() {
        return {
            isPaused: false // we upload straight away by default
        }
    },
    computed: {
        isUploading() {
            return (this.status !== 'success' && this.status !== 'canceled')
        },
        uploadedAmount() {
            return (this.progress * 100).toFixed(2)
        },
    },
    methods: {
        upload() {
            this.file.resumableObj.upload()
            this.isPaused = false
        },
        pause() {
            this.file.pause()
            this.isPaused = true
        },
        resume() {
            this.pause() // not sure why, but we have to call pause again before upload will resume
            this.upload()
        },
        cancel() {
            this.$emit('cancel', this.file)
        }
    }
}
</script>

<style lang="scss">
.uploading-video {
    .status-canceled {
        text-decoration: line-through;
    }
}
</style>