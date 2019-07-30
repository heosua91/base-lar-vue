export const formMixin = {
    created() {
        this.$eventBus.$on('validateFailed', this.scrollVertical);
    },
    destroyed() {
        this.$eventBus.$off('validateFailed', this.scrollVertical);
    },
    methods: {
        scrollVertical() {
            window.scrollTo(0, 0);
        }
    }
};
