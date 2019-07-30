<template>
    <component :is="layout"></component>
</template>

<script>
    import EmptyLayout from './components/layouts/EmptyLayout'
    import BaseLayout from './components/layouts/BaseLayout'

    const defaultLayout = "base"

    export default {
        created() {
            document.title = this.titlePage
        },
        components: {
            'empty-layout': EmptyLayout,
            'base-layout': BaseLayout
        },
        name: "AppAdmin",
        computed: {
            layout() {
                return `${this.$route.meta.layout || defaultLayout}-layout`
            },
            titlePage() {
                return (this.$route.meta && this.$route.meta.title) ? this.$t(this.$route.meta.title) : this.$t('admin.title_page.default');
            }
        },
        watch: {
            '$route': function () {
                document.title = this.titlePage
            }
        }
    }
</script>