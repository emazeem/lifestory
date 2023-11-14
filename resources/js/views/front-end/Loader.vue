<template>
    <div v-if="show">
        <img src="../../public/loading.gif" alt="">
    </div>
</template>
<script >
export default {
    name:'Loader',
    props:{
      show:'',
    },
}
</script>
