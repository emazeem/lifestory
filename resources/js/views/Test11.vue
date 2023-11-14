s<template>
  <div class="container">
    <div class="card shadow-lg mt-5">
      <div class="card-body">
        <input type="file" @change="select"/>
      </div>
    </div>
  </div>
</template>
<script>
    export default {
      name: "Test",
        data() {
            return {
                file: null,
                chunks: []
            };
        },

        computed: {
            formData() {
                let formData = new FormData;
                formData.set('is_last', this.chunks.length === 1);
                formData.set('file', this.chunks[0], `${this.file.name}.part`);

                return formData;
            },
            config() {
                return {
                    method: 'POST',
                    data: this.formData,
                    url: 'video-upload',
                    headers: {
                        'Content-Type': 'application/octet-stream'
                    }
                };
            }
        },

        methods: {
            select(event) {
                this.file = event.target.files.item(0);
                this.createChunks();
            },
            createChunks() {
                let size = 2048*30;
                let chunks = Math.ceil(this.file.size / size);

                for (let i = 0; i < chunks; i++) {
                    this.chunks.push(this.file.slice(
                        i * size, Math.min(i * size + size, this.file.size), this.file.type
                    ));
                    this.axios(this.config).then(() => {
                      this.chunks.shift();
                    });
                }
            }
        }
    }
</script>