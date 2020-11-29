<template>
    <div>
        <div v-if="results.length == 0">
            Không tìm thấy kết quả nào
        </div>
        <div v-else v-for="(result, index) in results" :key="index">
            {{result.name}}
        </div>  
    </div>
</template>
<script>
export default {
    data() {
        return {
            results: []
        }
    },
    mounted() {
        this.getResult();
    },
    methods: {
        getResult() {
            axios.get('/api/result')
            .then((response) => {
                this.result = response.data;
                console.log(response);
            })
        }
    }
}
</script>
<style lang="scss" scoped>

</style>