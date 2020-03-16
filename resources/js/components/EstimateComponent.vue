<template>
    <div>
        <div>
            <small v-if="saving">Saving...</small>
            <small v-else>All changes are saved</small>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <estimate-section v-for="(section, index) in sections" :key="index" :section="section" @sectionRemoved="removeSection(index, 'text')"></estimate-section>
            </div>

            <div class="col-sm-12">
                <button class="btn btn-primary" @click="addSection()">Add Text Section</button>
                <button class="btn btn-success">Add Prices Section</button>
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
            priceSections: []
        }
    },

    mounted() {
        this.getSections();
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
                'text': '',
                'type': type,
            });
        },

        showSavingLabel() {
            this.saving = true;

            setTimeout(() => {
                this.saving = false;
            }, 500);
        },

        removeSection(index, type) {

            if(type == 'text') {
                this.sections.splice(index, 1);
            }

        }

    }

}
</script>