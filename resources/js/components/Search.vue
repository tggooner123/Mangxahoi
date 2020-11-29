<template>
    <div id="app">
        <nav class="navbar navbar-light bg-light">     
            <input v-model="name" class="form-control mr-sm-2 input-search" type="search" placeholder="Tìm bạn bè" aria-label="Search">
            <a class="btn btn-outline-success my-2 my-sm-0" href="/search">Tìm</a>
        </nav>
    </div>
</template>
<script>
export default {
    data() {
        return {
            name: ''
        }
    },
    methods: {
        search: function() {
            axios.post('/search', {name: this.name})
            .then((response) => {
                console.log(response);
            });
        }
    }

}
</script>
<style lang="scss" scoped>
.input-search {
    display: inline-block;
    width: 240px;
}
</style>