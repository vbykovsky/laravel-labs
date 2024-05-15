<template>
    <div v-show="this.article != null">
        <a class="d-block alert alert-primary" role="alert" v-bind:href="'/article/' + this.article?.id">
            Добавлена новая статья <strong>{{this.article?.title}}</strong>
        </a>
    </div>
</template>

<script>
export default {
    data() { return { article: null } },
    created() {
        window.Echo.channel('my-channel').listen('ArticleEvent', (data) => {
            this.article=data;
            console.log(data, this.article);
            // alert('Добавлена новая статья!');
        })
    }
}
</script>
