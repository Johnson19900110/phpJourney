<template>
    <div class="comments-area">
        <div id="respond" class="comment-respond">
            <h3 id="reply-title" class="comment-reply-title">发表评论</h3>
            <div class="comment-form">
                <form :model="myForm" id="commentform" class="comment-form" novalidate>
                    <p class="comment-notes">
                        <span id="email-notes">电子邮件地址不会被公开。</span> 必填项已用<span class="required">*</span>标注
                    </p>
                    <p class="comment-form-author">
                        <label for="name">姓名 <span class="required">*</span></label>
                        <input id="name" v-model="myForm.name" ref="name" type="text" value="" size="30" maxlength="245"
                               aria-required='true' required='required'/>
                    </p>
                    <p class="comment-form-email">
                        <label for="email">电子邮件 <span class="required">*</span></label>
                        <input id="email" v-model="myForm.email" ref="email" type="text" value="" size="30"
                               maxlength="100" aria-describedby="email-notes" aria-required='true' required='required'/>
                    </p>
                    <!--<p class="comment-form-url">-->
                        <!--<label for="url">站点</label>-->
                        <!--<input id="url" v-model="myForm.url" ref="url" type="text" value="" size="30" maxlength="200"/>-->
                    <!--</p>-->
                    <p class="mk-tips" style="clear: both;">Tips：支持markdown 语法 <a name="comment"></a></p>
                    <p class="comment-form-comment">
                        <label for="markdown">评论</label>
                        <textarea id="markdown" ref="markdown" v-model="myForm.markdown" cols="45" rows="8"
                                  maxlength="65525" aria-required="true" required="required"></textarea>
                    </p>
                    <p class="form-submit">
                        <button @click="comment" type="button" id="submit" class="submit" v-html="submitText"></button>
                    </p>
                </form>
                <!--<div class="commentPreview" v-html="commentPreview"></div>-->
            </div>
        </div><!-- #respond -->
        <div class="commentshow">
            <!-- .comment-list -->
            <ol class="commentlist">

                <li class="comment" v-for="comment in comments">
                    <article class="comment-body comment-body-parent">
                        <div class="comment-author">
                            <img src="/images/avatar.jpg"
                                 class="avatar avatar-96" height="96" width="96">
                        </div>
                        <div class="comment-content">
                            <div class="comment-entry">
                                <span class="name author" itemprop="author">
                                    <span>{{  comment.name }}：</span>
                                </span>
                                <section v-html="comment.content"></section>
                            </div>
                            <div class="comment-head">
                                <span class="date"><time :datetime="comment.created_at">{{ comment.created_at }}</time></span>
                                <!--<a rel='nofollow' class='comment-reply-link' href="#comment" :aria-label="'回复'+comment.name" @click="reply(comment.name)">回复</a>-->
                                <!--<a class="comment-edit-link" href="javascript:void(0)">删除</a>-->
                            </div>
                        </div>
                    </article>
                </li><!-- #comment-## -->

            </ol>
        </div>
    </div>
</template>
<script type="text/ecmascript-6">
    export default{
        data: function () {
            return {
                loading: false,
                submitText: '发表评论',
                comments: [],
                myForm: {
                    post_id: this.post,
                    parent_id: 0,
                    name: '',
                    email: '',
                    markdown: ''
                }
            }
        },
        props: ['post'],
        computed: {
            /*commentPreview: function () {
                return marked(this.myForm.markdown, {sanitize: true});
            }*/
        },
        methods: {
            comment: function () {
                let _this = this;
                for (let key in _this.myForm) {
                    if (_this.myForm[key] === '') {
                        _this.$refs[key].focus();
                        return false;
                    }
                }
                _this.submitText = '提交中...';
                window.axios.post('/comments', _this.myForm).then(function (response) {
                    let res = response.data;
                    if (res.status === 0) {
                        _this.comments.push(res.comment);
                        _this.myForm = {
                            post_id: _this.post,
                            name: '',
                            email: '',
                            markdown: ''
                        };
                        console.log(_this.myForm);
                    }
                    _this.submitText = '发表评论';
                }).catch(function (error) {
                    let res = error.response;
                    if (res) {
                        if (res.status === 422) {
                            for (let index in res.data.errors) {
                                alert(res.data.errors[index][0]);
                            }
                        }
                    } else {
                        console.log(error);
                    }
                    _this.submitText = '发表评论';

                });
                return false;
            },
            getData: function () {
                var _this = this;
                window.axios.get('/comments/' + _this.post).then(function (response) {
                    let res = response.data;
                    if(res.status === 0) {
                        _this.comments = res.comments;
                    }

                }).catch(function (error) {
                    console.log(error);
                });
            },
            reply: function (name) {
                var at = '@' + name + ' ';
                this.myForm.markdown += at;
                return false;
            }
        },
        mounted: function () {
            this.getData();
        }
    }
</script>
<style type="text/css">
    #respond {
        color: #888;
        font-size: 12px;
        background: none;
        margin: 3em 0;
        position: relative;
    }

    #comments {
        margin: 30px 0;
    }

    #comments p {
        line-height: 1.8em;
    }

    #comments-navi {
        border-bottom: 1px solid #f2f2f2;
        padding-bottom: 5px;
    }

    #comments-navi {
        margin: 20px 0;
        font-size: 16px;
    }

    .comment_list {
        padding: 0 10px;
    }

    .comment-body {
        position: relative;
        font-size: 13.5px;
        min-height: 34px;
        margin-bottom: 8px;
        /*border-bottom: 1px #eee dashed;*/
        padding-top: 10px;
    }

    .comment-body-parent {
        min-height: 48px;
        margin-bottom: 18px;
    }

    .comment-author,
    .children .ajax-live .comment-author {
        float: left;
        margin-right: 8px;
    }

    .comment-author img,
    .children .ajax-live .comment-author img {
        border-radius: 50%;
        height: 32px;
        width: 32px;
    }

    .comment-body-parent .comment-author,
    .ajax-live .comment-author {
        margin-right: 18px;
    }

    .comment-body-parent .comment-author img,
    .ajax-live .comment-author img {
        height: 48px;
        width: 48px;
    }

    .comment-author cite.fn {
        font-style: normal;
    }

    .comment-author span.says {
        display: none;
    }

    .comment-head {
        margin: 0 0 5px 0;
    }

    .name {
        line-height: 1.8em;
    }

    .children .name {
        float: left;
    }

    .comment-entry {
        margin: 5px 0 4px 0;
        padding-bottom: 4px;
    }

    .comment-body-parent .comment-entry {
        margin: 0 0 4px 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 4px;
    }

    .comment-entry .floor {
        float: right;
        margin-top: -18px;
        color: #BBB;
        font-size: 11.2px;
    }

    .comment-entry .wp-smiley {
        margin-top: -2px;
    }

    .comment-entry img {
        max-width: 80%;
    }

    .depth-1 .comment-content {
        overflow: hidden;
    }

    .children {
        margin-left: 68px;
    }

    .depth-2 .children {
        margin-left: 0;
    }

    a.comment-reply-link {
        margin-left: 1em;
        color: #BBB;
        font-size: 12px;
    }

    .children a.comment-reply-link {
        float: right;
    }

    #respond {
        color: #888;
        font-size: 12px;
        background: none;
        margin: 3em 0;
        position: relative;
    }

    .children #respond {
        margin: 1em 0 3em;
    }

    #respond textarea {
        width: 98%;
        padding: 5px;
    }

    li.depth-1 #respond {
        margin-left: 68px;
    }

    li.depth-2 #respond {
        margin-left: 0;
    }

    .comment_list #respond textarea {
        width: 95%;
    }

    .comment-form-author,
    .comment-form-email,
    .comment-form-url {
        position: relative;
        float: left;
        margin: 12px 2% 12px 0;
        width: 23%;
    }

    .comment-form-url {
        width: 48%;
    }

    #markdown {
        height: 88px;
    }

    #commentform {
        padding-top: 20px;
    }

    #commentform p label {
        line-height: 1px;
        top: 0px;
        position: absolute;
        left: 6px;
        background-color: #fff;
        padding: 0 5px;
        font-weight: normal;
        font-size: 12px;
    }

    #commentform input {
        width: 96%;
        padding: 5px;
        display: block;
        line-height: 22px;
    }

    #commentform input#submit,
    #commentform input#url {
        width: 100%;
    }

    #submit {
        padding: 5px 15px;
        cursor: pointer;
        font-size: 14px;
        color: #fff;
        text-shadow: 1px 1px #424558;
        border: 1px solid #424558;
        border-radius: 4px;
        background: #33495d;
        /*background: url("../images/header.jpg") #33495d no-repeat;
          background-size: cover;*/
    }

    #submit:active {
        color: #fff;
        /*text-shadow: 1px 1px #fff;*/
        border: 1px solid #ccc;
        background: #5a6977;
        background: -moz-linear-gradient(top, #fff, #f1f1f1);
        background: -webkit-gradient(linear, 0 0, 0 100%, from(#4a5c6d), to(#3f5467));
    }

    .form-submit {
        margin-top: 10px;
    }

    .reply {
        padding-bottom: 10px;
        font-size: 12px;
    }

    .commentlist li.depth-1 {
        margin-bottom: 24px;
        line-height: 18px;
    }

    .comment_list li p {
        clear: both;
        margin-bottom: 5px;
    }

    #commentform input#comment_mail_notify {
        display: inline;
        width: 15px;
    }

    #comment-edit-link,
    .comment-notes,
    .comment-form-comment label,
    .form-allowed-tags {
        display: none;
    }

    input[type^="text"],
    input[type^="password"],
    textarea {
        border: 1px solid #ddd;
    }

    .butterBar {
        margin-left: 36%;
        max-width: 640px;
        position: fixed;
        text-align: center;
        top: 0;
        width: 58%;
        z-index: 800;
    }

    .butterBar--center {
        left: 50%;
        margin-left: -320px;
    }

    .butterBar-message {
        background: rgba(255, 255, 255, 0.97);
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.25), 0 0 1px rgba(0, 0, 0, 0.35);
        display: inline-block;
        font-size: 14px;
        margin-bottom: 0;
        padding: 12px 25px;
    }
</style>
