<template>
    <div class="block-pagination text-center">
        <div class="pagination-wrapper">
            <div class="block-pagination text-center">
                <div class="pagination-wrapper" v-show="pagination.total > 0">
                    <ul class="pagination" v-show="pagination.lastPage > firstPage">
                        <!--button to first page-->
                        <li class="page-item prev" :class="!isEnableFirstPage ? 'disable-cursor' : ''"
                            @click="isEnableFirstPage ? clickChangePage(firstPage) : ''">
                            <a class="page-link" :class="isEnableFirstPage ? '' : 'page-link-disable'"
                               :href="isEnableFirstPage ? '#' : false">
                                {{$t('admin.common.first_btn_prev_paginate')}}
                            </a>
                        </li>
                        <!--button to prev page-->
                        <li class="page-item prev" :class="!isEnableFirstPage ? 'disable-cursor' : ''"
                            @click="isEnableFirstPage ? clickChangePage(currentPage - 1) : ''">
                            <a class="page-link" :class="isEnableFirstPage ? '' : 'page-link-disable'"
                               :href="isEnableFirstPage ? '#' : false">
                                {{$t('admin.common.btn_prev_paginate')}}
                            </a>
                        </li>

                        <!--list page-->
                        <li @click="page.clickable ? clickChangePage(page.number_page) : ''" class="page-item"
                            :class="(page.number_page === currentPage) ? 'active disable-cursor' : ''" aria-current="page"
                            v-for="(page, index) in arrayPage">
                            <a :key="index" class="page-link" href="#">{{page.number_page}}</a>
                        </li>

                        <!--button to next page-->
                        <li class="page-item next" :class="!isEnableLastPage ? 'disable-cursor' : ''"
                            @click="isEnableLastPage ? clickChangePage(currentPage + 1) : ''">
                            <a class="page-link" :class="isEnableLastPage ? '' : 'page-link-disable'"
                               :href="isEnableLastPage ? '#' : false">
                                {{$t('admin.common.btn_next_paginate')}}
                            </a>
                        </li>
                        <!--button to last page-->
                        <li class="page-item next" :class="!isEnableLastPage ? 'disable-cursor' : ''"
                            @click="isEnableLastPage ? clickChangePage(pagination.lastPage) : ''">
                            <a class="page-link" :class="isEnableLastPage ? '' : 'page-link-disable'"
                               :href="isEnableLastPage ? '#' : false">
                                {{$t('admin.common.last_btn_next_paginate')}}
                            </a>
                        </li>
                    </ul>
                    <div class="pagination-count">
                        {{pagination.currentPage}}/{{pagination.lastPage}} ({{$t('admin.common.pagination.all')}}
                        {{pagination.total}}{{$t('admin.common.pagination.in_the_piece')}})
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['pagination', 'clickChangePage', 'current'],
        data() {
            return {
                marginPrev: 2,
                marginNext: 2,
                firstPage: 1
            }
        },
        computed: {
            currentPage: {
                get: function () {
                    window.scrollTo(0, 0);
                    return this.current
                },
                set: function () {

                }
            },
            arrayPage() {
                let arrayPage = []
                arrayPage.push({
                    number_page: this.currentPage,
                    clickable: false,
                });
                for (let i = 1; i <= this.marginPrev; i++) {
                    let nextNumber = this.currentPage - i
                    if (nextNumber > 0) {
                        arrayPage.unshift({
                            number_page: nextNumber,
                            clickable: true,
                        });
                    }
                }

                for (let i = 1; i <= this.marginNext; i++) {
                    let nextNumber = this.currentPage + i
                    if ((nextNumber <= this.currentPage + this.marginNext) && (nextNumber <= this.pagination.lastPage)) {
                        arrayPage.push({
                            number_page: nextNumber,
                            clickable: true,
                        });
                    }
                }

                return arrayPage;
            },
            isEnableFirstPage () {
                return this.currentPage > this.firstPage
            },
            isEnableLastPage () {
                return this.currentPage < this.pagination.lastPage
            }
        },
    }
</script>
<style scoped>
    .page-item {
        cursor: pointer;
    }
    .page-link-disable,
    .page-link-disable:hover {
        opacity: 1 !important;
        color: rgba(28, 28, 59, 0.59) !important;
    }
    .disable-cursor {
        cursor: default !important;
    }
</style>

