<template>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>name</th>
            <th>beta</th>
            <th>file</th>
            <th>date</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="loading">
            Loading...
        </tr>
        <tr v-for="(version, index) in data" v-else>
            <th scope="row">{{ index + 1 }}</th>
            <td>{{ version.name }}</td>
            <td>{{ version.beta }}</td>
            <td>{{ version.file }}</td>
            <td>{{ version.date }}</td>
            <td>
                <a class="btn btn-primary"
                   :href="`${url}/${version.id}?api_token=${apiToken}`"
                   role="button"><i class="fas fa-download"></i></a>
                <a class="btn btn-danger" v-on:click="deleteVersion(version.id)"
                   role="button"><i class="fas fa-trash text-white"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "VersionsTable",
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
            },
            deleteVersion(id) {
                axios.delete(`${this.url}/${id}`, {
                    data: {
                        api_token: this.apiToken
                    }
                }).then(data => {
                    console.log(data);
                    this.fetchData();
                }).catch(error => console.log(error));
            }
        }
    }
</script>
