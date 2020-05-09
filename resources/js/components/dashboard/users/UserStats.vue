<template>
    <div class="row justify-content-center">
        <div class="col-3" style="min-width: 250px;">
            <div class="card text-center">
                <span class="card-sub-title">Total Users</span>
                <div class="card-body">
                    <h2>{{ total }}</h2>
                </div>
            </div>
            <small>
                Latest: {{ latest.name }} - {{ latest.time }}
            </small>
        </div>
        <div class="col-3" style="min-width: 250px;">
            <div class="card text-center">
                <span class="card-sub-title">Users Today</span>
                <div class="card-body">
                    <h2>{{ today }}</h2>
                </div>
            </div>
        </div>
        <div class="col-3" style="min-width: 250px;">
            <div class="card text-center">
                <span class="card-sub-title">Users This Month</span>
                <div class="card-body">
                    <h2>{{ month }}</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserStats",
        props: {
            url: {type: String, required: true},
            type: {type: String, required: true},
            apiToken: {type: String, required: true}
        },
        data() {
            return {
                total: null,
                today: null,
                month: null,
                latest: {
                    name: null,
                    time: null,
                }
            }
        },
        created() {
            axios.get(this.url, {
                params: {
                    type: this.type,
                    api_token: this.apiToken
                }
            }).then(data => {
                console.log(data);

                data = data.data;
                this.total = data.total;
                this.today = data.today;
                this.week = data.week;
                this.month = data.month;
                this.latest = data.latest;

            }).catch(error => console.log(error));
        }
    }
</script>
