<template>
    <main class="main">
        <Panel>
            <template #header>
                <div class="panel-header">
                    <i class="pi pi-spin pi-book panel-icon"></i>
                    <h4 class="panel-icon">Pagar Creditos de Compra</h4>
                </div>
            </template>
        </Panel>
    </main>
</template>

<script>
import Panel from 'primevue/panel';
import useVuelidate from '@vuelidate/core';
import { required, minValue } from '@vuelidate/validators';

export default {
    setup () {
        return { 
            v$: useVuelidate() 
        }
    },

    data() {
        return {

        }
    },

    components: { 
        Panel,

    },

    computed: {

    },

    watch: {

    },

    methods: {

    },

    created() {

    },

    mounted() {

    },
}
</script>

<style scoped>
/* Panel */
.panel-header {
    display: flex;
    align-items: center;
    padding-right: 20px;
}

.panel-icon {
    font-size: 2rem;
    margin-right: 10px;
}

.panel-icon {
    font-size: 1.5rem;
    margin: 0;
}
</style>