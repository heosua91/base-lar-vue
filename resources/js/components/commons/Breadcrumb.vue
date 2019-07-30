<template>
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'IndexComponent'}">
                    {{$t('admin.common.first_text_breadcrumb')}}
                </router-link>
            </li>
            <li class="breadcrumb-item" v-if="textPage">
                <router-link v-if="textPageDeep && paramObj" :to="{ name: textPage.component, params: { placement_code: paramObj.value }}">
                    {{textPage.label}}
                </router-link>        
                <router-link :to="{ name: textPage.component}" v-else-if="textPageDeep">
                    {{textPage.label}}
                </router-link>
                <label v-if="!textPageDeep">{{textPage.label}}</label>
            </li>
            <li class="breadcrumb-item" v-if="textPageDeep">
                {{textPageDeep.label}}
            </li>
        </ol>
    </div>
</template>

<script>
    export default {
        props: ['textPage','textPageDeep', 'paramObj']
    }
</script>
<style scoped>
    .page-bar .page-breadcrumb > li {
        color: #999 !important;
    }
</style>