<template>
  <div class="container mt-5">
    <div class="card shadow-lg">
      <div class="card-body">
        <input type="file" ref="fileInput" class="form-control" @change="uploadVideo" />
        <button class="btn btn-success mt-3" @click="startUpload">Start Upload</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedFile: null,
      fileReader: new FileReader(),
      chunkSize: 1024 * 512,
      chunks: [],
      currentChunkIndex: 0,
    };
  },
  methods: {
    uploadVideo() {
      this.selectedFile = this.$refs.fileInput.files[0];
      const { selectedFile, chunkSize } = this;
      let offset = 0;

      while (offset < selectedFile.size) {
        const chunk = selectedFile.slice(offset, offset + chunkSize);
        this.chunks.push(chunk);
        offset += chunkSize;
      }
    },
    startUpload() {
      this.uploadChunk();
    },
    async uploadChunk() {
      if (this.currentChunkIndex >= this.chunks.length) {
        if (this.currentChunkIndex === this.chunks.length) {
          await this.combineChunks();
        }
        return;
      }

      const chunk = this.chunks[this.currentChunkIndex];
      const formData = new FormData();
      formData.append('videoChunk', chunk);
      formData.append('currentChunkIndex', this.currentChunkIndex);

      await this.sendChunkToServer(formData);

        this.currentChunkIndex++;
        this.uploadChunk();

    },
    async sendChunkToServer(formData) 
    {
      await this.axios.post('sessions/video-upload', formData);
    },
    async combineChunks() 
    {  
      await this.axios.post('sessions/combine-chunks', {
          totalChunks: this.chunks.length,
        });
    },
  },
};
</script>
