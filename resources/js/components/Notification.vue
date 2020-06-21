<template>
    <notifications position="bottom center"
                   :duration="7000"
                   :width="700">
        <template slot="body" slot-scope="props">
            <div class="alert alert-success alert-dismissible" v-if="props.item.type === 'success'">
                <button type="button" class="close" @click="props.close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="alert-heading">
                    <i class="fas fa-check pr-1"></i>
                    {{ props.item.title }}
                </h4>
                <p class="mb-0" v-html="props.item.text"></p>
            </div>
            <div class="alert alert-danger alert-dismissible" v-else>
                <button type="button" class="close" @click="props.close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="alert-heading">
                    <i class="fas fa-exclamation pr-1"></i>
                    {{ props.item.title }}
                </h4>
                <p class="mb-0" v-html="props.item.text"></p>
            </div>
        </template>
    </notifications>
</template>


<script>
    export default {
        name: "Notification"
    }
</script>
