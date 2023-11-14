<template>
    <router-view></router-view>
</template>
<style>
.mt-100{
    margin-top: 100px;
}
.mb-100{
    margin-bottom: 100px;
}
.my-100{
    margin-top: 100px;
    margin-bottom: 100px;
}
.pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 5px;
    cursor: pointer;
}

.pagination li a {
    display: block;
    padding: 5px 10px;
    text-decoration: none;
    color: #000;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.pagination li.active a {
    background-color: #007bff;
    color: #fff;
}

.pagination li:hover a {
    background-color: #ddd;
}

tr.text-white th {
    color: white;
}

.footer-ul li a {
    color: white !important;
}
</style>


<script>

export default {
    name:'App',
};

</script>

