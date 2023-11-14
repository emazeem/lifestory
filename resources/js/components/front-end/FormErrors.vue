<template>
    <div v-if="errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
        <ul>
            <li v-for="e in errors"> {{ e }} </li>
        </ul>
        <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
            &times;
        </p>
    </div>
</template>
<script>
export default {
    name:'FormErrors',
    props:{
        errors:''
    }
}
</script>
