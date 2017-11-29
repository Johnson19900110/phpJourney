
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import ElementUI from 'element-ui';
import VueRouter from 'vue-router';

Vue.use(ElementUI);
Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import util from './lib/util';
import marked from 'marked';
import localforage from 'localforage';
Vue.prototype.util = util;
Vue.prototype.marked = marked;
Vue.prototype.localforage = localforage;

// Vue.component('example', require('./components/Example.vue'));
// Vue.component('login', require('./components/user/login.vue'));
import App from './App.vue';
import Login from './components/user/login.vue';
import Index from './components/main/index.vue';
import Main from  './components/main/main.vue';
import UserSetting from './components/user/userSetting.vue';
import Category from './components/category/category.vue';
import post from './components/post/post.vue'

const routes = [
    {
        path: '/login',
        component: Login,
        hidden: true
    },
    {
        path: '/',
        component: Index,
        name:'',
        iconCls: 'fa fa-home',
        leaf: true,
        children: [
            {path: '/index', component: Main, name: '仪盘表'}
        ]
    },
    {
        path: '/',
        component: Index,
        name:'文章',
        iconCls: 'fa fa-file-word-o',
        children: [
            {path: '/articles', component: Main, name: '文章管理'},
            {path: '/article/add', component: post, name: '发布文章'},
            {path: '/article/category', component: Category, name: '分类管理'}
        ]
    },
    {
        path: '/',
        component: Index,
        name:'拓展',
        iconCls: 'fa fa-external-link-square',
        children: [
            {path: '/navigation', component: Main, name: '导航管理'},
            {path: '/comment', component: Main, name: '评论管理'}
        ]
    },
    {
        path: '/',
        component: Index,
        name:'',
        iconCls: 'fa fa-cog',
        leaf: true,
        children: [
            {path: '/setting', component: Main, name: '设置'}
        ]
    },
    {
        path: '/',
        component: Index,
        name: '',
        leaf: true,
        hidden: true,
        children: [
            { path: '/user', component: UserSetting, name: '用户设置'}
        ]
    },
    {
        path: '/',
        component: Index,
        name: '',
        leaf: true,
        hidden: true,
        children: [
            { path: '/article/edit/:id', component: post, name: '修改文章'}
        ]
    },
];

const router = new VueRouter({
    history: true,
    routes
});

const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
}).$mount('#app');
