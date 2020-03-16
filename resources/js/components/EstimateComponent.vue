<template>
    <div>
        <div>
            <small v-if="saving">Saving...</small>
            <small v-else>All changes are saved</small>
        </div>

        <div>
            <h3>Total $ {{ formattedTotal }}</h3>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <estimate-section v-for="(section, index) in sections" :key="index" :section="section" @sectionRemoved="removeSection(index, 'text')"></estimate-section>
            </div>

            <div class="col-sm-12">
                <button class="btn btn-primary" @click="addSection()">Add Text Section</button>
                <button class="btn btn-success" @click="addSection('prices')">Add Prices Section</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['estimate'],

    data() {
        return {
            saving: false,
            sections: [],
        }
    },

    mounted() {
        this.getSections();
    },

    computed: {
        total() {
            let total = this.sections.reduce((sum, section) => {
                return sum + (parseFloat(section.total) || 0); 
            }, 0);

            return parseFloat(total);
        },

        formattedTotal() {
            return this.total.toFixed(2);
        }
    },

    methods: {

        getSections() {
            let url = '/estimates/:estimate/sections';
            url = url.replace(':estimate', this.estimate);

            axios.get(url).then(({data}) => {
                this.sections = data;
            });
        },

        addSection(type = 'text') {
            this.sections.push({
                'id': null,
                'text': '',
                'type': type,
                'madeFirstInput': true,
                'items': [],
                'total': 0,
            });
        },

        showSavingLabel() {
            this.saving = true;

            setTimeout(() => {
                this.saving = false;
            }, 500);
        },

        removeSection(index, type) {
            this.sections.splice(index, 1);
        }

    }

}
</script>