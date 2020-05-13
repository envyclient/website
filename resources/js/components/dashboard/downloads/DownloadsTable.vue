<template>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>name</th>
            <th>file</th>
            <th>date</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="loading">
            Loading...
        </tr>
        <tr v-for="(download, index) in data" v-else>
            <th scope="row">{{ index + 1 }}</th>
            <td>{{ download.name }}</td>
            <td>{{ download.file }}</td>
            <td>{{ download.date }}</td>
            <td>
                <a class="btn btn-primary"
                   :href="`/api/downloads/${download.id}?api_token=${apiToken}`"
                   role="button">Download</a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "DownloadsTable",
        props: {
            url: {type: String, required: true},
            apiToken: {type: String, required: true}
        },
        data() {
            return {
                loading: true,
                data: []
            }
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.loading = true;
                axios.get(this.url, {
                    params: {
                        api_token: this.apiToken
                    }
                }).then(data => {
                    console.log(data);
                    this.data = data.data;
                    this.loading = false;
                }).catch(error => console.log(error));
            }
        }
    }
</script>
