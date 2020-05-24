<template>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>code</th>
            <th>referrals_count</th>
            <th>user</th>
            <th>date</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="loading">
            Loading...
        </tr>
        <tr v-for="(code, index) in data" v-else>
            <th scope="row">{{ index + 1 }}</th>
            <td>{{ code.code }}</td>
            <td>{{ code.referrals_count }}</td>
            <td>{{ code.user.name }}</td>
            <td>{{ code.date }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "ReferralCodesTable",
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
