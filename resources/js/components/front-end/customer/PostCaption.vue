<template>
    <div>
        <p class="ct-p pt-2 pb-0 recent-post-max-caption" @mouseover="showFullCaption = true" @mouseleave="showFullCaption = false">
          <span v-text="showFullCaption ? fullCaption : truncatedCaption"></span>
        </p>
      </div>
</template>
  
<script>
export default {
    name: 'PostCaption',
    props: {
        caption: String,
    },
    data() {
        return {
            showFullCaption: false,
        };
    },
    computed: {
        truncatedCaption() {
            return this.caption.length > 10 ? this.caption.slice(0, 10) + '...' : this.caption;
        },
        fullCaption() {
            return this.caption;
        },
    },
};
</script>
  