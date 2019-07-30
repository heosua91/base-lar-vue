<template>
    <div class="table-div mgt-20" ref="scrollBe">
    <table :class="classTable">
        <thead>
        <tr>
            <th v-if="isHaveCheckboxCheckAll">
                <label class="label-checkbox-table">
                    <input value="1" type="checkbox" id="chk_all" @change="$emit('checkAll')"  :checked="checkAllPage[currentPage]">
                    選択
                </label>
            </th>
            <th :class="column.sortAble ? 'text-blue' : ''" v-for="column in columns" :key="column.name" @click="column.sortAble ? $emit('sort', column.name) : ''">
                <a href="javascript:void(0)" class="sorting_default" v-if="column.sortAble">{{column.label}}</a>
               <span v-else v-html="$commonHelper.sanitizeHtml(column.label)"></span>
            </th>
        </tr>
        </thead>
        <slot></slot>
    </table>
    </div>
</template>
<script>
    import { EventBus } from '../../events/eventBus'

    export default {
        props: {
            columns: {type: Array},
            sortOrders: {type: Object},
            checkAllItem: {type: Boolean, default: false},
            isHaveCheckboxCheckAll: {type: Boolean, default: false},
            currentPage: {type: Number, default: 0},
            displayAble: {type: Boolean, default: true},
            classTable: {type: String, default: 'table table-bordered table-narrow-padding'}
        },
        data() {
            return {
                checkAllPage: [],
            }
        },
        watch: {
            checkAllItem: function (newVal, oldVal) {
                if (typeof this.checkAllPage !== 'undefined') {
                    this.checkAllPage[this.currentPage] = newVal
                }
            },
            currentPage: function (newVal, oldVal) {
                if (typeof this.checkAllPage[newVal] === 'undefined') {
                    this.checkAllPage[newVal] = false
                } else {
                    this.checkAllPage[newVal] = this.checkAllItem
                }
            }
        },
        created() {
            EventBus.$on('dataChanged', this.scrollHorizontal);
        },
        methods: {
            scrollHorizontal() {
                if (this.$refs.scrollBe) {
                    this.$refs.scrollBe.scrollLeft -= 99999999
                }
            }
        },
        destroyed() {
            EventBus.$off('dataChanged', this.scrollHorizontal);
        },
    }
</script>
<style scoped>
    .sorting_default:hover {
        color: #2a6496;
    }

    .sorting_default:focus {
        color: #2a6496 !important;
    }

    .text-header {
        color: #333;
        font-weight: 600;
    }
</style>