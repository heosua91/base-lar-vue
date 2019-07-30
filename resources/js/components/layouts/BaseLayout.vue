<template>
    <div>
        <header>Header</header>
        <div class="main-content">
            <div class="content">
                <router-view :key="componentKey" @reRenderComponent="reRenderComponent"></router-view>
            </div>
        </div>              
        <footer>Footer</footer>
    </div>
</template>

<script>
    export default {
        components: {
        },
        data() {
            return {
                componentKey: 0
            }
        },
        methods : {
            reRenderComponent(){
                this.componentKey += 1
            },
        },
        name: "BaseLayout"
    }
</script>

<style scoped>

</style>