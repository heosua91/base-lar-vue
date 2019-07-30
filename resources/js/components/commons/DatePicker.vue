<template>
    <input class="datepicker" :placeholder="placeholderInput">
</template>
<script>
    export default {
        props : ['dateFormat', 'defaultDate', 'yearRange', 'startDate', 'endDate'],
        data() {
            return {
                placeholderInput: ''
            }
        },
        mounted: function() {
            var self = this;
            this.placeholderInput = this.placeholder ? this.placeholder : '';
            if (typeof self.defaultDate !== 'undefined' && self.defaultDate) {
                $(this.$el).datepicker({
                    format: (self.dateFormat) ? self.dateFormat : 'yyyy-mm-dd',
                    yearRange: this.yearRange ? this.yearRange : self.$commonHelper.generateDefaultYear(),
                    changeYear: true,
                    language: 'ja',
                    autoclose: true,
                    startDate: this.startDate ? this.startDate : new Date(1900, 1, 1),
                    endDate: this.endDate ? this.endDate : new Date(2500, 1, 1),
                    onSelect: function(date) {
                        self.$emit('input', date);
                        self.$emit('update-date', date);
                        self.$emit('update-layout', {self: self, date: date});
                    }
                }).datepicker('setDate', new Date(self.defaultDate));
            } else {
                $(this.$el).datepicker({
                    format: (self.dateFormat) ? self.dateFormat : 'yyyy-mm-dd',
                    yearRange: this.yearRange ? this.yearRange : self.$commonHelper.generateDefaultYear(),
                    changeYear: true,
                    language: 'ja',
                    autoclose: true,
                    startDate: this.startDate ? this.startDate : new Date(1900, 1, 1),
                    endDate: this.endDate ? this.endDate : new Date(2500, 1, 1),
                    onSelect: function(date) {
                        self.$emit('input', date);
                        self.$emit('update-date', date);
                        self.$emit('update-layout', {self: self, date: date});
                    }
                });
                $(this.$el).val('');
            }
            var _datepickerElement = this;
            $(this.$el).on('change', function(){
                let date = '';
                if (this.value !== '') {
                    date = new Date(this.value);
                    date = isNaN(date) ? new Date() : date;
                    let month = ((date.getMonth() + 1) < 10) ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1);
                    let day = ((date.getDate()) < 10) ? '0' + (date.getDate()) : (date.getDate());
                    date = date.getFullYear() + '-' + month + '-' + day;
                }
                $(self.$el).val(date);
                self.$emit('input', date);
                self.$emit('update-date', date);
                self.$emit('update-layout', {self: self, date: date});
            });
        },
        methods: {
            reCreateModal: function(dateModified) {
                var self = this;
                if (typeof dateModified !== 'undefined' && dateModified) {
                    $(this.$el).datepicker({
                        format: (self.dateFormat) ? self.dateFormat : 'yyyy-mm-dd',
                        yearRange: this.yearRange ? this.yearRange : self.$commonHelper.generateDefaultYear(),
                        changeYear: true,
                        language: 'ja',
                        autoclose: true,
                        startDate: this.startDate ? this.startDate : new Date(1900, 1, 1),
                        endDate: this.endDate ? this.endDate : new Date(2500, 1, 1),
                        onSelect: function(date) {
                            self.$emit('input', date);
                            self.$emit('update-date', date);
                            self.$emit('update-layout', {self: self, date: date});
                        }
                    }).datepicker('setDate', new Date(dateModified));
                } else {
                    $(this.$el).datepicker({
                        format: (self.dateFormat) ? self.dateFormat : 'yyyy-mm-dd',
                        yearRange: this.yearRange ? this.yearRange : self.$commonHelper.generateDefaultYear(),
                        changeYear: true,
                        language: 'ja',
                        autoclose: true,
                        startDate: this.startDate ? this.startDate : new Date(1900, 1, 1),
                        endDate: this.endDate ? this.endDate : new Date(2500, 1, 1),
                        onSelect: function(date) {
                            self.$emit('input', date);
                            self.$emit('update-date', date);
                            self.$emit('update-layout', {self: self, date: date});
                        }
                    });
                    $(this.$el).val('');
                }
            }
        },
        beforeDestroy: function() {
            $(this.$el).datepicker('hide').datepicker('destroy');
        }
    }
</script>
